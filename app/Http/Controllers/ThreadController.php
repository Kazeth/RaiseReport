<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Http\Controllers\Controller;
use App\Models\Upvote;
use Illuminate\Http\Request;

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
        $threads = Thread::where('threadStatus', 'like', "%approved%")
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

    // Edit Thread Page

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
        $threads = Thread::where('threadStatus', 'like', "%pending%")->withCount('upvotes')
            ->orderBy('upvotes_count', 'desc')
            ->get()
            ->map(function ($t) {
                $t->created_at_formatted = $t->created_at->translatedFormat('d F Y - H.i');
                return $t;
            });
        return view('onHoldThreadsPage', compact('threads'));
    }








    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Thread $thread)
    {
        //
    }
}
