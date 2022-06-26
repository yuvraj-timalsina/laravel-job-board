<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClickRequest;
use App\Http\Requests\UpdateClickRequest;
use App\Models\Click;

class ClickController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClickRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClickRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Click  $click
     * @return \Illuminate\Http\Response
     */
    public function show(Click $click)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Click  $click
     * @return \Illuminate\Http\Response
     */
    public function edit(Click $click)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClickRequest  $request
     * @param  \App\Models\Click  $click
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClickRequest $request, Click $click)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Click  $click
     * @return \Illuminate\Http\Response
     */
    public function destroy(Click $click)
    {
        //
    }
}
