<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostCOntroller extends Controller
{
    public function index()
    {
        $post = auth()->user()->posts()->find(id);


    }
}
