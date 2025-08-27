<?php

namespace App\Http\Controllers;

use App\Models\Calory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CaloryController extends Controller
{
    private function responseFormat($status, $statusCode, $message, $data = null)
    {
        return response()->json([
            'meta' => [
                'status' => $status,
                'statusCode' => $statusCode,
                'message' => $message,
            ],
            'data' => $data,
        ], $statusCode);
    }

    // Index: ambil semua kalori milik user yang login
    public function index()
    {
        $calories = Calory::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->responseFormat('success', 200, 'Data kalori berhasil diambil', $calories);
    }

    // Create / Store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'portion' => 'nullable|string',
            'calories' => 'required|integer|min:0',
        ]);

        $calory = Calory::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'portion' => $request->portion,
            'calories' => $request->calories,
        ]);

        return $this->responseFormat('success', 201, 'Kalori berhasil ditambahkan', $calory);
    }

    // Update
    public function update(Request $request, $id)
    {
        $calory = Calory::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'portion' => 'nullable|string',
            'calories' => 'required|integer|min:0',
        ]);

        $calory->update([
            'name' => $request->name,
            'portion' => $request->portion,
            'calories' => $request->calories,
        ]);

        return $this->responseFormat('success', 200, 'Kalori berhasil diperbarui', $calory);
    }

    // Delete
    public function destroy($id)
    {
        $calory = Calory::where('user_id', Auth::id())->findOrFail($id);
        $calory->delete();

        return $this->responseFormat('success', 200, 'Kalori berhasil dihapus');
    }

    // Get Calories Today
    public function getCaloriesToday()
    {
        $totalCalories = Calory::where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->sum('calories');

        return $this->responseFormat('success', 200, 'Total kalori hari ini berhasil diambil', [
            'total_calories_today' => $totalCalories
        ]);
    }

    // Get Calories Week (7 hari terakhir)
    public function getCaloriesWeek()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();

        $calories = Calory::where('user_id', Auth::id())
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, SUM(calories) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return $this->responseFormat('success', 200, 'Total kalori minggu ini berhasil diambil', $calories);
    }

    // Get summary calories
    public function getSummaryCalories()
    {
        $userId = Auth::id();

        // Get Personal Information DB
        $personalInfo = DB::table('personal_information')
            ->where('user_id', $userId)
            ->first();

        if (!$personalInfo || !$personalInfo->max_calories) {
            return $this->responseFormat('error', 404, 'Data personal information atau max_calories tidak ditemukan');
        }

        $maxCalories = $personalInfo->max_calories;

        // sum calories today
        $totalToday = Calory::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->sum('calories');

        // rest calories
        $restCalories = max($maxCalories - $totalToday, 0);

        // percentage
        $percentageCalories = $maxCalories > 0 ? ($totalToday / $maxCalories) * 100 : 0;
        $percentageRestCalories = $maxCalories > 0 ? ($restCalories / $maxCalories) * 100 : 0;

        return $this->responseFormat('success', 200, 'Summary kalori berhasil diambil', [
            'max_calories' => $maxCalories,
            'rest_calories' => $restCalories,
            'calories_today' => $totalToday,
            'percentage_calories' => round($percentageCalories, 2),
            'percentage_rest_calories' => round($percentageRestCalories, 2),
        ]);
    }
}
