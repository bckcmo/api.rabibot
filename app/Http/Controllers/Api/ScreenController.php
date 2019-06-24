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
        event(new ScreenRequested($screen, $data['data'], $request->user()));
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
    public function show(Request $request, $id)
    {
      $screen = Screen::find($id);
      if(!$screen) {
        return $this->sendError('Invalid ID');
      }

      if(!$request->user()->owns($screen)) {
        return $this->sendError('Authorization Error', 'User is not authorized to preform this action', 401);
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
    public function update($id)
    {
      return $this->sendError('Update method not supported');
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
        return $this->sendError('Invalid ID');
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
        return $this->sendError('Invalid ID');
      }

      if(!$user->owns($screen)) {
        return $this->sendError('Authorization Error', 'User is not authorized to preform this action', 401);
      }

      $message = (new ScreenEmail($screen))->onQueue('low');

      Mail::to($request->user()->email)->queue($message);

      return $this->sendResponse(['screen' => $screen], "Screen was emailed to {$user->email}");
    }
}
