<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $threads = Thread::where('threadStatus', 'like', "%approved%")->orderBy('threadUpvote', 'desc')->get();
        return view('landingPage', compact('threads'));
    }

    public function search(Request $request)
    {
        $threads = Thread::where('threadName', 'like', "%{$request->search}%", "&&", 'threadStatus', 'like', "%approved%")
            ->orderBy('threadUpvote', 'desc')
            ->get();

        return response()->json($threads);
    }

    public function sortByDate(Request $request)
    {
        $threads = Thread::where('threadStatus', 'like', "%approved%")->orderBy('created_at', 'desc')->get();

        return view('threadsPage', compact('threads'));
    }

    public function searchSortByDate(Request $request)
    {
        $threads = Thread::where('threadName', 'like', "%{$request->search}%", "&&", 'threadStatus', 'like', "%approved%")
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($threads);
    }

    public function show($id)
    {
        $thread = Thread::findOrFail($id);
        return view('threadDetailPage', compact('thread'));
    }

    public function userIndex()
    {
        $userId = auth()->id();
        $threads = Thread::where('userId', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('userThreadsPage', compact('threads'));
    }

    public function upvote($id)
    {
        $thread = Thread::findOrFail($id);
        $thread->threadUpvote += 1;
        $thread->save();

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
