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


    public function showIndex(Request $request)
    {
        $threads = Thread::where('threadStatus', 'Approved')
            ->withCount('upvotes')
            ->orderBy('upvotes_count', 'desc')
            ->get();

        return response()->json($threads);
    }

    public function showIndexAuth(Request $request)
    {
        $userId = auth()->id();

        // Untuk user login (tambah info user sudah upvote atau belum)
        $threads = Thread::where('threadStatus', 'Approved')
            ->where('threadName', 'like', "%{$request->search}%")
            ->withCount('upvotes')
            ->orderBy('upvotes_count', 'desc')
            ->get()
            ->map(function ($t) use ($userId) {
                return [
                    'id' => $t->id,
                    'threadName' => $t->threadName,
                    'threadContent' => $t->threadContent,
                    'upvotes_count' => $t->upvotes_count,
                    'created_at_formatted' => $t->created_at->translatedFormat('d F Y - H.i'),
                    'hasUpvoted' => $t->upvotes()->where('user_id', $userId)->exists(),
                ];
            });

        return response()->json($threads);
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

    public function searchSortByDate(Request $request)
    {
        $threads = Thread::where('threadName', 'like', "%{$request->search}%")
            ->where('threadStatus', 'like', "%approved%")
            ->withCount('upvotes')
            ->with(['upvotes' => function ($q) {
                $q->where('user_id', auth()->id());
            }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'threadName' => $t->threadName,
                    'threadContent' => $t->threadContent,
                    'upvotes' => $t->upvotes,
                    'upvotes_count' => $t->upvotes_count,
                    'created_at_formatted' => $t->created_at->translatedFormat('d F Y - H.i'),
                ];
            });

        return response()->json($threads);
    }

    // Thread Detail Page
    public function show($id)
    {
        $thread = Thread::withCount('upvotes')->findOrFail($id);
        return view('threadDetailPage', compact('thread'));
    }

    // Create Thread Page

    // Edit Thread Page

    // Profile Page

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

    //
    public function adminIndex()
    {
        $threads = Thread::withCount('upvotes')
            ->orderBy('upvotes_count', 'desc')->get();
        return view('manageThreadPage', compact('threads'));
    }

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

    public function searchAdmin(Request $request)
    {
        $threads = Thread::where('threadName', 'like', "%{$request->search}%")
            ->withCount('upvotes')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($threads);
    }

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
