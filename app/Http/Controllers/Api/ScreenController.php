<?php

namespace App\Http\Controllers\Api;

use Validator;

use App\User;
use App\Screen;
use App\Mail\ScreenEmail;
use App\Bckcmo\EJScreenApi;
use Illuminate\Http\Request;
use App\Events\ScreenRequested;
use Illuminate\Support\Facades\Mail;
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
   * Checks for isGeocoded field and sends request to appropriate handler.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Bckcmo\EJScreenApi $api
   *
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, EJScreenApi $api)
  {
    if(array_key_exists('isGeocoded', $request->input())) {
      if($request->input()['isGeocoded']) {
        return $this->handleGpsScreen($request, $api);
      }
    }
    return $this->handleAddressScreen($request, $api);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request, $id)
  {
    $screen = Screen::find($id);
    if(!$screen) {
      return $this->sendError('Invalid ID', [], 400);
    }

    if(!$request->user()->owns($screen)) {
      return $this->sendError('Authorization Error', 'User is not authorized to preform this action', 401);
    }

    return $this->sendResponse(['screen' => $screen], 'Screen found');
  }

  /**
   * Update the specified resource in storage. Only the notes attribute can be updated.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $screen = Screen::find($id);
    if(!$screen) {
      return $this->sendError('Invalid ID', [], 400);
    }

    if(!$request->user()->owns($screen)) {
      return $this->sendError('Authorization Error', 'User is not authorized to preform this action', 401);
    }

    $screen->notes = $request->input()['notes'];
    $screen->save();

    return $this->sendResponse(['screen' => $screen], 'Screen updated');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, $id)
  {
    $screen = Screen::find($id);
    if(!$screen) {
      return $this->sendError('Invalid ID', [], 400);
    }

    if(!$request->user()->owns($screen)) {
      return $this->sendError('Authorization Error', 'User is not authorized to preform this action', 401);
    }

    $screen->delete();
    return $this->sendResponse(['screen' => $screen], 'Screen deleted');
  }

  /**
   * Email screen to user.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function send(Request $request, $id)
  {
    $user = $request->user();
    $screen = Screen::find($id);
    if(!$screen) {
      return $this->sendError('Invalid ID', [], 400);
    }

    if(!$user->owns($screen)) {
      return $this->sendError('Authorization Error', 'User is not authorized to preform this action', 401);
    }

    $message = (new ScreenEmail($screen))->onQueue('low');

    Mail::to($request->user()->email)->queue($message);

    return $this->sendResponse(['screen' => $screen], "Screen was emailed to {$user->email}");
  }

  /**
   * Validate request and use EJSCREEN api to get remote data from address.
   * Fire job to update screen with one mile and blockgroup report links.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  private function handleAddressScreen(Request $request, EJScreenApi $api) {
    $validator = Validator::make($request->all(), [
      'address' => 'required',
      'city' => 'required',
      'state' => 'required',
      'zip' => 'required|max:10',
    ]);

    if($validator->fails()){
      return $this->sendError('Validation Error', $validator->errors(), 400);
    }

    $input = $request->input();
    $results = $api->screenAddress($input);
    $data = array_merge($results['data'], $input, $request->user()->getUserData());

    if($results['success']) {
      return $this->sendResponse($this->makeScreen($data), 'EJ results');
    }

    return $this->sendError('An error occured during the request', [], 500);
  }

  /**
   * Validate request and use EJSCREEN api to get remote data from GPS coordinates.
   * Fire job to update screen with one mile and blockgroup report links.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  private function handleGpsScreen(Request $request, EJScreenApi $api) {
    $validator = Validator::make($request->all(), [
      'lat' => 'required|numeric|lte:90|gte:-90',
      'lng' => 'required|numeric|lte:120|gte:-120',
    ]);

    if($validator->fails()){
      return $this->sendError('Validation Error', $validator->errors(), 400);
    }

    $input = $request->input();
    $results = $api->screenCoordinates($input);
    $data = array_merge($results['data'], $input, $request->user()->getUserData());

    if($results['success']) {
      return $this->sendResponse($this->makeScreen($data), 'EJ results');
    }

    return $this->sendError('An error occured during the request', [], 500);
  }

  /**
   * Store a newly created resource in storage.
   * Fire job to update screen with one mile and blockgroup report links.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  private function makeScreen($data) {
    $screen = Screen::create($data);
    // fire event to update screen with report data
    event(new ScreenRequested($screen, $data));
    return $screen;
  }
}
