<?php

namespace App\Http\Controllers;

use App\Models\Links;
use Illuminate\Http\Request;
use App\Services\LinksService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateLinkRequest;
use App\Http\Requests\IndexLinksRequest;
use Illuminate\Support\Facades\Redirect;

class LinksController extends Controller
{

    public $links_service;

    function __construct(LinksService $links_service)
    {
        $this->links_service = $links_service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexLinksRequest $request)
    {

        $request_data = $request->validated();
        $links = $this->links_service->viewLinksReport($request_data);

        return apiResponse(true, $links);
    }

    public function store(CreateLinkRequest $request)
    {
        $request_data = $request->validated();
        $request_data['user_id'] = Auth::user()->id;
        $link = $this->links_service->insert($request_data);

        return apiResponse(true, $link);
    }


    /**
     * Display the specified resource.
     */
    public function show(Links $links)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Links $links)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Links $links)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Links $links)
    {
        //
    }


    function redirectLink(Request $request, Links $link)
    {
        $this->links_service->log($link);
        return Redirect::away($link->link, 302);
    }
}
