<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware(['auth:api', 'scopes:admin'])->except(['login']);
    }

    public $successStatus = 200;

    public function login(){
        if(Auth::guard('admin')->attempt(['email' => request('email'), 'password' => request('password')])){
            $admin = Auth::guard('admin')->user();
            $success['token'] = $admin->createToken('adminToken', ['admin'])->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    public function details() {
        $admin = Auth::guard('admin-api')->user();
        return response()->json(['success' => $admin], $this->successStatus);
    }
}
