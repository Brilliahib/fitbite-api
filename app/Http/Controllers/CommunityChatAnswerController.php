<?php

namespace App\Http\Controllers;

use App\Models\CommunityChatAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityChatAnswerController extends Controller
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

    /**
     * Display a listing of the resource.
     */
    public function index($communityChatId)
    {
        $answers = CommunityChatAnswer::with('user:id,name')
            ->where('community_chat_id', $communityChatId)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($answers->isEmpty()) {
            return $this->responseFormat('success', 200, 'Belum ada jawaban untuk community chat ini', []);
        }

        return $this->responseFormat('success', 200, 'List jawaban community chat berhasil diambil', $answers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $community_chat_id)
    {
        $request->validate([
            'message' => 'required|string',
            'image' => 'nullable|string',
        ]);

        $answer = CommunityChatAnswer::create([
            'community_chat_id' => $community_chat_id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'image' => $request->image,
        ]);

        return $this->responseFormat(
            'success',
            201,
            'Community chat answer berhasil ditambahkan',
            $answer
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $answer = CommunityChatAnswer::find($id);

        if (!$answer) {
            return $this->responseFormat('error', 404, 'Community chat answer tidak ditemukan');
        }

        $request->validate([
            'message' => 'sometimes|required|string',
            'image' => 'nullable|string',
        ]);

        $answer->update([
            'message' => $request->message ?? $answer->message,
            'image' => $request->image ?? $answer->image,
        ]);

        return $this->responseFormat('success', 200, 'Community chat answer berhasil diperbarui', $answer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $answer = CommunityChatAnswer::find($id);

        if (!$answer) {
            return $this->responseFormat('error', 404, 'Community chat answer tidak ditemukan');
        }

        $answer->delete();

        return $this->responseFormat('success', 200, 'Community chat answer berhasil dihapus');
    }
}
