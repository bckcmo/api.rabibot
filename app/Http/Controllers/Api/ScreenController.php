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
      $screens = Screen::find($request->user()->id)->screens;
      $response['screens'] = $screens;
      $message = $screens ? 'No screens found' : "{$screens->count()} screens found" ;
      return $this->sendResponse($response, $message);
    }

    /**
     * Store a newly created resource in storage.
     * Fire job to update screen with one mile and blockgroup report links.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, EJScreenApi $api)
    {
      $validator = Validator::make($request->all(), [
        'address' => 'required',
        'city' => 'required',
        'state' => 'required',
        'zip' => 'required|max:10',
      ]);

      if($validator->fails()){
        return $this->sendError('Validation Error', $validator->errors());
      }

      $input = $request->input();
      $data = $api->get($input);
      if($data['success']) {
        // create screen
        $screen = new Screen;
        $screen->setAddress($input);
        $screen->ej_result = $data['data']['is_ej'];
        $request->user()->screens()->save($screen);
        // fire job to update screen with report data
        //
        return $this->sendResponse($screen, 'EJ results');
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
