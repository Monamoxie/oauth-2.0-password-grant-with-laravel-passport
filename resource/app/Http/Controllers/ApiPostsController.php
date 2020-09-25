<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class ApiPostsController extends Controller
{
    public function listResouceUserPosts(Request $request)
    {    
        return Post::all();
    }
}
