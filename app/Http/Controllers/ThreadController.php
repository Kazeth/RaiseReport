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
        $threads = Thread::orderBy('threadUpvote', 'desc')->get();
        return view('landingPage', compact('threads'));
    }

    public function search(Request $request)
    {
        $threads = Thread::where('threadName', 'like', "%{$request->search}%")
            ->orderBy('threadUpvote', 'desc')
            ->get();

        return response()->json($threads);
    }

    public function sortByDate(Request $request)
    {
        $threads = Thread::orderBy('created_at', 'desc')->get();

        return view('threadsPage', compact('threads'));
    }

    public function searchSortByDate(Request $request)
    {
        $threads = Thread::where('threadName', 'like', "%{$request->search}%")
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($threads);
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
     * Display the specified resource.
     */
    public function show(Thread $thread)
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
