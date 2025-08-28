<?php

namespace App\Http\Controllers;

use App\Models\PersonalInformation;
use App\Models\WeeklyProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeeklyProgressController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $weeklyProgress = WeeklyProgress::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Daftar weekly progress berhasil diambil'
            ],
            'data' => $weeklyProgress
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'weight_end' => 'required|numeric|min:1',
        ]);

        $userId = Auth::id();

        $personalInfo = PersonalInformation::where('user_id', $userId)->firstOrFail();

        $weightStart = $personalInfo->weight;
        $weightEnd = $request->weight_end;

        $progressPercentage = 0;
        if ($weightStart > 0) {
            $progressPercentage = (($weightStart - $weightEnd) / $weightStart) * 100;
        }

        $weekly = WeeklyProgress::create([
            'user_id' => $userId,
            'weight_start' => $weightStart,
            'weight_end' => $weightEnd,
            'progress_percentage' => $progressPercentage,
        ]);

        $personalInfo->update([
            'weight' => $weightEnd
        ]);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 201,
                'message' => 'Weekly progress berhasil disimpan'
            ],
            'data' => $weekly
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'weight_end' => 'required|numeric|min:1',
        ]);

        $userId = Auth::id();
        $weekly = WeeklyProgress::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $personalInfo = PersonalInformation::where('user_id', $userId)->firstOrFail();

        $weightStart = $personalInfo->weight;
        $weightEnd = $request->weight_end;

        $progressPercentage = 0;
        if ($weightStart > 0) {
            $progressPercentage = (($weightStart - $weightEnd) / $weightStart) * 100;
        }

        $weekly->update([
            'weight_start' => $weightStart,
            'weight_end' => $weightEnd,
            'progress_percentage' => $progressPercentage,
        ]);

        // update personal info
        $personalInfo->update([
            'weight' => $weightEnd
        ]);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Weekly progress berhasil diperbarui'
            ],
            'data' => $weekly
        ]);
    }

    public function destroy($id)
    {
        $userId = Auth::id();
        $weekly = WeeklyProgress::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $weekly->delete();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Weekly progress berhasil dihapus'
            ]
        ]);
    }
}
