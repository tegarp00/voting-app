<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIdeaRequest;
use App\Http\Requests\UpdateIdeaRequest;
use App\Models\Idea;
use App\Models\Vote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return response()
            ->view('idea.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIdeaRequest $request): RedirectResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea): Response
    {
        return response()
            ->view('idea.show', [
                'idea' => $idea,
                'votes_count' => $idea->votes()->count(),
                'backUrl' => url()->previous() !== url()->full() && url()->previous() !== route('login')
                    ? url()->previous()
                    : route('idea.index'),
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIdeaRequest $request, Idea $idea): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea): RedirectResponse
    {
        //
    }
}
