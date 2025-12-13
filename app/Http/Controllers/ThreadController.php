<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Upvote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\SupabaseStorage;


class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // Landing Page
    public function index()
    {
        $threads = Thread::where('threadStatus', 'Approved')
            ->withCount('upvotes')
            ->orderBy('upvotes_count', 'desc')
            ->get();

        return view('landingPage', compact('threads'));
    }

    // Threads Page
    public function sortByDate(Request $request)
    {
        $threads = Thread::where('threadStatus', 'Approved')
            ->withCount('upvotes')
            ->with(['upvotes' => function ($q) {
                $q->where('user_id', auth()->id());
            }])
            ->orderBy('created_at', 'desc')->get();

        return view('threadsPage', compact('threads'));
    }

    // Thread Detail Page
    public function show($id)
    {
        $thread = Thread::with(['files'])->withCount('upvotes')->findOrFail($id);

        if (!$thread) {
            return redirect()->route('threads')
                ->with('warning', 'The thread you were viewing has been deleted.');
        }

        return view('threadDetailPage', compact('thread'));
    }

    /* =============== */
    /* =====USER===== */
    /* =============== */

    // User's Threads Page
    public function userIndex()
    {
        $userId = auth()->id();
        $threads = Thread::where('userId', $userId)
            ->with(['upvotes' => function ($q) {
                $q->where('user_id', auth()->id());
            }])
            ->orderBy('created_at', 'desc')->withCount('upvotes')
            ->get();

        return view('userThreadsPage', compact('threads'));
    }

    // Create Thread Page
    public function store(Request $request)
    {
        $request->validate([
            'threadName'    => 'required|max:255',
            'threadContent' => 'required',
            'files.*'       => 'nullable|mimes:jpg,jpeg,png,pdf,mp4,mp3,wav,webm|max:51200',
        ]);

        $thread = Thread::create([
            'userId'        => auth()->id(),
            'threadName'    => $request->threadName,
            'threadContent' => $request->threadContent,
            'threadStatus'  => 'Pending',
        ]);

        if ($request->hasFile('files')) {

            $storage = new SupabaseStorage();

            foreach ($request->file('files') as $file) {

                $path = 'threads/' . uniqid() . '_' . $file->getClientOriginalName();

                $publicUrl = $storage->upload($file, $path);

                $thread->files()->create([
                    'fileName'  => $file->getClientOriginalName(),
                    'path'      => $publicUrl, // URL Supabase
                    'mime_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'extension' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        return redirect()
            ->route('userThreads')
            ->with('success', 'Thread created and awaiting approval!');
    }


    // Edit Thread Page
    public function edit($id)
    {
        $thread = Thread::with('files')->findOrFail($id);

        // Pastikan user hanya bisa edit thread miliknya
        if ($thread->userId !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('editThreadPage', compact('thread'));
    }

    public function update(Request $request, $id)
    {
        $thread = Thread::findOrFail($id);

        $thread->threadName = $request->threadName;
        $thread->threadContent = $request->threadContent;
        $thread->threadStatus = 'Pending';
        $thread->save();

        if ($request->remove_files) {

            $storage = new SupabaseStorage();

            foreach ($request->remove_files as $fileId) {

                $file = File::find($fileId);

                if ($file) {
                    $storage->delete($file->path);
                    $file->delete();
                }
            }
        }

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $uploadedFile) {

                // ⬅️ AMBIL SEMUA INFO SEBELUM move()
                $originalName = $uploadedFile->getClientOriginalName();
                $extension    = $uploadedFile->getClientOriginalExtension();
                $mimeType     = $uploadedFile->getClientMimeType();
                $size         = $uploadedFile->getSize();

                $fileName = uniqid() . '_' . $originalName;
                $uploadedFile->move(public_path('uploads'), $fileName);

                File::create([
                    'thread_id' => $thread->id,
                    'fileName'  => $originalName,
                    'path'      => 'uploads/' . $fileName,
                    'mime_type' => $mimeType,
                    'file_size' => $size,
                    'extension' => $extension,
                ]);
            }
        }

        return redirect()->route('detail', $thread->id)
            ->with('success', 'Thread updated successfully.');
    }


    // User's utilities
    public function upvote($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must log in to upvote.');
        }

        $userId = auth()->id();

        // Cek apakah user sudah pernah upvote thread ini
        $hasUpvoted = Upvote::where('user_id', $userId)
            ->where('thread_id', $id)
            ->exists();

        if ($hasUpvoted) {
            return redirect()->back()->with('error', 'You have already upvoted this thread.');
        }

        // Jika belum, buat upvote baru
        Upvote::create([
            'user_id' => $userId,
            'thread_id' => $id,
        ]);

        return redirect()->back()->with('success', 'Upvoted!');
    }

    public function destroy($id)
    {
        $thread = Thread::with('files')->findOrFail($id);

        if ($thread->userId !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $storage = new SupabaseStorage();

        foreach ($thread->files as $file) {
            $storage->delete($file->path); // ⬅️ hapus di Supabase
            $file->delete();               // ⬅️ hapus di DB
        }

        $thread->upvotes()->delete();
        $thread->delete();

        return redirect()->route('userThreads')
            ->with('success', 'Thread deleted successfully.');
    }

    /* =============== */
    /* =====ADMIN===== */
    /* =============== */

    // Manage Thread Page
    public function adminIndex()
    {
        $threads = Thread::withCount('upvotes')
            ->orderBy('upvotes_count', 'desc')->get();
        return view('manageThreadPage', compact('threads'));
    }
    // Admin's utilities
    public function approve($id)
    {
        $thread = Thread::findOrFail($id);
        $thread->threadStatus = 'Approved';
        $thread->save();

        return back()->with('success', 'Approved!');
    }

    public function reject($id)
    {
        $thread = Thread::findOrFail($id);
        $thread->threadStatus = 'Rejected';
        $thread->save();

        return back()->with('success', 'Rejected!');
    }

    public function revert($id)
    {
        $thread = Thread::findOrFail($id);
        $thread->threadStatus = 'Pending';
        $thread->save();

        return back()->with('success', 'Reverted!');
    }

    // On-Hold Threads Page
    public function showPending()
    {
        $threads = Thread::where('threadStatus', 'Pending')
            ->withCount('upvotes')
            ->orderBy('upvotes_count', 'desc')
            ->get()
            ->map(function ($t) {
                $t->created_at_formatted = $t->created_at->translatedFormat('d F Y - H.i');
                return $t;
            });
        return view('onHoldThreadsPage', compact('threads'));
    }
}
