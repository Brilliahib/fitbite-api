<?php

namespace App\Http\Controllers;

use App\Models\Makanan;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Makanan::all();

        return response()->json([
            "meta" => [
                "status" => "success",
                "statusCode" => 200,
                "message" => "List makanan berhasil diambil"
            ],
            "data" => $foods
        ], 200);
    }

    public function detail($id)
    {
        $food = Makanan::find($id);

        if (!$food) {
            return response()->json([
                "meta" => [
                    "status" => "error",
                    "statusCode" => 404,
                    "message" => "Makanan tidak ditemukan"
                ],
                "data" => null
            ], 404);
        }

        return response()->json([
            "meta" => [
                "status" => "success",
                "statusCode" => 200,
                "message" => "Detail makanan berhasil diambil"
            ],
            "data" => $food
        ], 200);
    }
}
