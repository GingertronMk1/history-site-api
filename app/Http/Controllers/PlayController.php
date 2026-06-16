<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayRequest;
use App\Http\Requests\UpdatePlayRequest;
use App\Models\Play;

class PlayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlayRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Play $play)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlayRequest $request, Play $play)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Play $play)
    {
        //
    }
}
