<?php
namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();

        return $this->sendResponse($posts, 'All Post Data.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,gif',
            ]
        );

        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->all());
        }

        // Handle image upload
        $img = $request->file('image');
        $imagename = time() . '.' . $img->getClientOriginalExtension();
        $img->move(public_path('uploads'), $imagename);

        // Create a new post in the database
        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagename,
        ]);

        return $this->sendResponse($post, 'Post Created Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->sendError('Post not found.');
        }

        return $this->sendResponse($post, 'Single Post Retrieved Successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'mimes:png,jpg,jpeg,gif', // Image is optional on update
            ]
        );

        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->all());
        }

        $post = Post::find($id);

        if (!$post) {
            return $this->sendError('Post not found.');
        }

        // Handle image upload and replacement
        if ($request->hasFile('image')) {
            $path = public_path('uploads/' . $post->image);

            if (file_exists($path)) {
                unlink($path);
            }

            $img = $request->file('image');
            $imagename = time() . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('uploads'), $imagename);
            $post->image = $imagename;
        }

        $post->title = $request->title;
        $post->description = $request->description;
        $post->save();

        return $this->sendResponse($post, 'Post Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->sendError('Post not found.');
        }

        $path = public_path('uploads/' . $post->image);

        if (file_exists($path)) {
            unlink($path);
        }

        $post->delete();

        return $this->sendResponse([], 'Post Deleted Successfully.');
    }
}
