<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class PostController extends Controller
{
    /**
     * Display a listing of the posts (Paginated).
     */
    public function index()
    {
        try {
            $posts = Post::paginate(10);
            return response()->json(['status' => 200, 'success' => true, 'data' => $posts], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'success' => false, 'message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Store a newly created post.
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:100',
                'content' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 422, 'success' => false, 'errors' => $validator->errors()], 422);
            }

            // Create post
            $post = Post::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return response()->json(['status' => 201, 'success' => true, 'message' => 'Post created', 'data' => $post], 201);

        } catch (Exception $e) {
            return response()->json(['status' => 500, 'success' => false, 'message' => 'Internal Server Error'.$e], 500);
        }
    }

    /**
     * Display the specified post.
     */
    public function show(string $id)
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return response()->json(['status' => 404, 'success' => false, 'message' => 'Post not found'], 404);
            }
            return response()->json(['status' => 200, 'success' => true, 'data' => $post], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'success' => false, 'message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Update the specified post.
     */
    public function update(Request $request, string $id)
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return response()->json(['status' => 404, 'success' => false, 'message' => 'Post not found'], 404);
            }

            if ($post->user_id !== Auth::id()) {
                return response()->json(['status' => 403, 'success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Validate request
            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'content' => 'sometimes|required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 422, 'success' => false, 'errors' => $validator->errors()], 422);
            }

            $post->update($request->only(['title', 'content']));
            return response()->json(['status' => 200, 'success' => true, 'message' => 'Post updated', 'data' => $post], 200);

        } catch (Exception $e) {
            return response()->json(['status' => 500, 'success' => false, 'message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Remove the specified post.
     */
    public function destroy(string $id)
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return response()->json(['status' => 404, 'success' => false, 'message' => 'Post not found'], 404);
            }

            if ($post->user_id !== Auth::id()) {
                return response()->json(['status' => 403, 'success' => false, 'message' => 'Unauthorized'], 403);
            }

            $post->delete();
            return response()->json(['status' => 200, 'success' => true, 'message' => 'Post deleted'], 200);

        } catch (Exception $e) {
            return response()->json(['status' => 500, 'success' => false, 'message' => 'Internal Server Error'], 500);
        }
    }
}