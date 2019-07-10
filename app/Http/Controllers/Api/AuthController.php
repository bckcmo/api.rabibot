<?php

namespace App\Http\Controllers\Api;

use Validator;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends ApiController
{
  /**
   * Handle user registration request.
   *
   * @return \Illuminate\Http\Response
   */
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required',
      'password_confirmation' => 'required|same:password',
    ]);

    if($validator->fails()){
      return $this->sendError('Validation Error', $validator->errors(), 400);
    }

    $input = $request->all();
    $input['password'] = bcrypt($input['password']);

    try {
      $user = User::create($input);
    } catch(QueryException $e) {
      return $this->sendError('Validation Error', ['email' => ["{$input['email']} is already in use"], 400]);
    }

    $response = ['access_token' => $user->createToken('authToken')->accessToken, 'name' =>  $user->name];

    return $this->sendResponse($response, 'User registration success');
  }

  /**
   * Handle user login request.
   *
   * @return \Illuminate\Http\Response
   */
  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'email|required',
      'password' => 'required'
    ]);

    if($validator->fails()){
      return $this->sendError('Validation Error', $validator->errors(), 400);
    }

    $input = $request->all();

    try {
      $model = User::where('email', $input['email'])->firstOrFail();
    } catch(ModelNotFoundException $e) {
      return $this->sendError('Invalid credentials', ['email' => ["{$input['email']} is not registered"]], 401);
    }

    if(!auth()->attempt($input)) {
      return $this->sendError('Invalid credentials', ['password' => ['Invalid password']], 401);
    }

    $accessToken = auth()->user()->createToken('authToken')->accessToken;
    $response = ['user' => auth()->user(), 'access_token' => $accessToken];

    return $this->sendResponse($response, 'User registration success');
  }

  /**
   * Handle user login request.
   *
   * @return \Illuminate\Http\Response
   */
  public function getUser(Request $request)
  {
    return $this->sendResponse($request->user(), 'User found');
  }
}
