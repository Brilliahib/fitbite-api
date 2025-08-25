<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use Illuminate\Http\Request;

class MealPlanController extends Controller
{
    /**
     * Get all meal plans
     */
    public function index()
    {
        $mealPlans = MealPlan::all();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'List meal plans berhasil diambil'
            ],
            'data' => $mealPlans
        ], 200);
    }

    /**
     * Store new meal plan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'meal' => 'required|string',
            'gram' => 'required|string',
            'meal_date' => 'required|date',
            'meal_time' => 'required|date_format:H:i',
        ]);

        $mealPlan = MealPlan::create($validated);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 201,
                'message' => 'Meal plan berhasil ditambahkan'
            ],
            'data' => $mealPlan
        ], 201);
    }

    /**
     * Update meal plan
     */
    public function update(Request $request, $id)
    {
        $mealPlan = MealPlan::find($id);

        if (!$mealPlan) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'statusCode' => 404,
                    'message' => 'Meal plan tidak ditemukan'
                ],
                'data' => null
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'meal' => 'sometimes|string',
            'gram' => 'sometimes|string',
            'meal_date' => 'sometimes|date',
            'meal_time' => 'sometimes|date_format:H:i',
        ]);

        $mealPlan->update($validated);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Meal plan berhasil diperbarui'
            ],
            'data' => $mealPlan
        ], 200);
    }

    /**
     * Delete meal plan
     */
    public function destroy($id)
    {
        $mealPlan = MealPlan::find($id);

        if (!$mealPlan) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'statusCode' => 404,
                    'message' => 'Meal plan tidak ditemukan'
                ],
                'data' => null
            ], 404);
        }

        $mealPlan->delete();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Meal plan berhasil dihapus'
            ],
            'data' => null
        ], 200);
    }
}
