<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Comment;
use App\Http\Resources\Comment as CommentResource;
use App\Http\Resources\CommentCollection;

class CommentController extends Controller
{
    public function index()
    {
        return Comment::with("user")->orderBy('created_at', 'desc')->paginate(10);
    }

    public function show($id)
    {
        return Comment::find($id);
    }

    public function store(Request $request)
    {
        return Auth::user()->comments()->save(new Comment($request->all()));
    }

    public function update(Request $request, $id)
    {
        try {
            $article = Comment::findOrFail($id);
            $article->update($request->all());
            return $article;
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Resource comment not found'
            ], 404);
        }

    }

    public function delete(Request $request, $id)
    {
        try {
            $article = Comment::findOrFail($id);
            $article->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Resource comment not found'
            ], 404);
        }
        return response()->json([
            ''
        ], 200);
    }
}
