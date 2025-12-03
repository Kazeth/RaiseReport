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
    public function index()
    {
        //
        $threads = Thread::where('threadStatus', 'like', "%approved%")
            ->withCount('upvotes')
            ->with(['upvotes' => function ($q) {
                $q->where('user_id', auth()->id());
            }])
            ->orderBy('upvotes_count', 'desc')->get();
        return view('landingPage', compact('threads'));
    }

    public function search(Request $request)
    {
        $threads = Thread::where('threadName', 'like', "%{$request->search}%", "&&", 'threadStatus', 'like', "%approved%")
            ->withCount('upvotes')
            ->with(['upvotes' => function ($q) {
                $q->where('user_id', auth()->id());
            }])
            ->orderBy('upvotes_count', 'desc')
            ->get();

        return response()->json($threads);
    }

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
            ->get();

        return response()->json($threads);
    }

    public function show($id)
    {
        $thread = Thread::withCount('upvotes')->findOrFail($id);
        return view('threadDetailPage', compact('thread'));
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

    public function upvote($id)
    {
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
