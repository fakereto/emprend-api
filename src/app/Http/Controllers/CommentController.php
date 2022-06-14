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
        return new CommentCollection(Comment::orderBy('created_at', 'desc')->paginate(10));
    }

    public function show($id)
    {
        return Comment::find($id);
    }

    public function store(Request $request)
    {
        $this->validator($request->all())->validate();
        return new CommentResource(Auth::user()->comments()->save(new Comment($request->all())));
    }

    public function update(Request $request, $id)
    {

        $this->validator($request->all())->validate();

        try {
            $article = new CommentResource(Comment::findOrFail($id));
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

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'body' => ['required'],
        ]);
    }
}
