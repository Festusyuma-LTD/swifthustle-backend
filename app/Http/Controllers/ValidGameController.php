<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Service\ValidGameService;
use Illuminate\Http\Request;

class ValidGameController extends Controller
{
    protected $validGameService;

    public function __construct(ValidGameService $validGameService)
    {
        $this->middleware(['auth:api','isAdmin']);
        $this->validGameService = $validGameService;

    }

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $game = $this->validGameService->makeGame($request);
        if(is_array($game)) {
            return ResponseHelper::responseDisplay(400, 'operation fail', $game);
        }
        if ($game) {
            return ResponseHelper::responseDisplay(200, 'operation successful', $game);
        } else {
            return ResponseHelper::responseDisplay(400, 'operation fail');
        }

    }

    public function isGameActive(Request $request)
    {
        $game = $this->validGameService->isGameValid($request);
        if(is_array($game)) {
            return ResponseHelper::responseDisplay(400, 'operation fail', $game);
        }
        if ($game) {
            return ResponseHelper::responseDisplay(200, 'operation successful', $game);
        } else {
            return ResponseHelper::responseDisplay(400, 'operation fail');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
