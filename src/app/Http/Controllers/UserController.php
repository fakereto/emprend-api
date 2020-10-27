<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\User as UserResource;
use App\User;

use App\Http\Resources\CommentCollection;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return new UserResource(User::find($request->user()->id));
    }

    public function show($id)
    {
        return new UserResource(User::find($id));
    }

    public function userComments(Request $request)
    {
        $user = $request->user();
        return new CommentCollection($user->comments);

    }
}
