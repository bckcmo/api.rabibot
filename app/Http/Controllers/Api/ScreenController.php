<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Screen;
use App\Bckcmo\EJScreenApi;
use Illuminate\Http\Request;
use App\Events\ScreenRequested;
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
      $screens = Screen::where('user_id', $request->user()->id)->get();
      $response['screens'] = $screens;
      $message = $screens->isEmpty() ? 'No screens found' : "{$screens->count()} screens found" ;
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
        // fire event to update screen with report data
        event(new ScreenRequested($screen, $data['data']));
        return $this->sendResponse($screen, 'EJ results');
      }

      return $this->sendError('An error occured during the request');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $screen = Screen::find($id);
      if(!$screen) {
        return $this->sendError('Invalid ID');
      }
      return $this->sendResponse(['screen' => $screen], 'Screen found');
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
      return $this->sendError('Update method not supported');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $screen = Screen::find($id);
      if(!$screen) {
        return $this->sendError('Invalid ID');
      }
      $screen->delete();
      return $this->sendResponse(['screen' => $screen], 'Screen deleted');
    }
}
