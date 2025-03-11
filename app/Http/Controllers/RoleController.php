<?php

namespace App\Http\Controllers;

use App\Models\role;
use App\Http\Requests\StoreMaster_roleRequest;
use App\Http\Requests\UpdateMaster_roleRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Storemaster_roleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(role $master_role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(role $master_role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatemaster_roleRequest $request, role $master_role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(role $master_role)
    {
        //
    }
}
