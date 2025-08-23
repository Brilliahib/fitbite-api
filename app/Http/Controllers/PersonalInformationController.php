<?php

namespace App\Http\Controllers;

use App\Models\PersonalInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalInformationController extends Controller
{
    /**
     * Tampilkan data personal information user.
     */
    public function index()
    {
        $info = PersonalInformation::where('user_id', Auth::id())->first();

        return response()->json([
            'meta' => [
                'status' => $info ? 'success' : 'error',
                'statusCode' => $info ? 200 : 404,
                'message' => $info ? 'Data personal information ditemukan' : 'Data personal information tidak ditemukan',
            ],
            'data' => $info,
        ], $info ? 200 : 404);
    }

    /**
     * Simpan personal information baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'age' => 'required|integer|min:1',
            'gender' => 'required|in:male,female',
            'weight' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'activity_level' => 'required|integer|min:1|max:5',
        ]);

        $info = PersonalInformation::create([
            'user_id' => Auth::id(),
            'age' => $request->age,
            'gender' => $request->gender,
            'weight' => $request->weight,
            'height' => $request->height,
            'activity_level' => $request->activity_level,
        ]);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 201,
                'message' => 'Personal information berhasil dibuat',
            ],
            'data' => $info,
        ], 201);
    }

    /**
     * Update personal information user.
     */
    public function update(Request $request)
    {
        $info = PersonalInformation::where('user_id', Auth::id())->first();

        if (!$info) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'statusCode' => 404,
                    'message' => 'Data personal information tidak ditemukan',
                ],
                'data' => null,
            ], 404);
        }

        $request->validate([
            'age' => 'sometimes|integer|min:1',
            'gender' => 'sometimes|in:male,female',
            'weight' => 'sometimes|numeric|min:1',
            'height' => 'sometimes|numeric|min:1',
            'activity_level' => 'sometimes|integer|min:1|max:5',
        ]);

        $info->update($request->only(['age', 'gender', 'weight', 'height', 'activity_level']));

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Personal information berhasil diperbarui',
            ],
            'data' => $info,
        ]);
    }

    /**
     * Hapus personal information user.
     */
    public function destroy()
    {
        $info = PersonalInformation::where('user_id', Auth::id())->first();

        if (!$info) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'statusCode' => 404,
                    'message' => 'Data personal information tidak ditemukan',
                ],
                'data' => null,
            ], 404);
        }

        $info->delete();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Personal information berhasil dihapus',
            ],
            'data' => null,
        ]);
    }

    /**
     * Cek apakah personal information sudah diisi user.
     */
    public function check()
    {
        $info = PersonalInformation::where('user_id', Auth::id())->exists();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => $info ? 'User sudah mengisi personal information' : 'User belum mengisi personal information',
            ],
            'data' => [
                'filled' => $info,
            ],
        ]);
    }
}
