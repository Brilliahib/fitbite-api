<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::all();

        return response()->json([
            "meta" => [
                "status" => "success",
                "statusCode" => 200,
                "message" => "List blog berhasil diambil"
            ],
            "data" => $blogs
        ], 200);
    }

    public function detail($slug)
    {
        $blog = Blog::where('slug', $slug)->first();

        if (!$blog) {
            return response()->json([
                "meta" => [
                    "status" => "error",
                    "statusCode" => 404,
                    "message" => "Blog tidak ditemukan"
                ],
                "data" => null
            ], 404);
        }

        return response()->json([
            "meta" => [
                "status" => "success",
                "statusCode" => 200,
                "message" => "Detail blog berhasil diambil"
            ],
            "data" => $blog
        ], 200);
    }
}
