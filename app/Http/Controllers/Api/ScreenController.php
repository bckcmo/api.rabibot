<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Screen;
use App\Bckcmo\EJScreenApi;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ScreenController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $screens = Screen::where('user_id', $request->user()->id);
      $response['screens'] = $screens;
      $message = $screens ? 'No screens found' : "{$screens->count()} screens found" ;
      return $this->sendResponse($screens, $message);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, EJScreenApi $api)
    {
      $validator = Validator::make($request->all(), [
        'address' => 'required',
        'city' => 'required',
        'zip' => 'required|max:10',
      ]);

      if($validator->fails()){
        return $this->sendError('Validation Error', $validator->errors());
      }

      $data = $api->get($request->input());
      if($data['success']) {
        return $this->sendResponse($data, 'EJ results');
      }

      return $this->sendError('Error connecting to EJSCREEN API');
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
