<?php

namespace App\Http\Controllers;

use App\Models\CommunityChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommunityChatController extends Controller
{
    /**
     * Get all community chats (no filter by user_id)
     */
    public function index()
    {
        $chats = CommunityChat::with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'List community chat berhasil diambil'
            ],
            'data' => $chats
        ], 200);
    }

    public function detail($id)
    {
        $chat = CommunityChat::with('user:id,name')->find($id);

        if (!$chat) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'statusCode' => 404,
                    'message' => 'Community chat tidak ditemukan'
                ],
                'data' => null
            ], 404);
        }

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Detail community chat berhasil diambil'
            ],
            'data' => $chat
        ], 200);
    }

    /**
     * Store new community chat
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('community_chat', 'public');
            $validated['image'] = $path;
        }

        $chat = CommunityChat::create($validated);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 201,
                'message' => 'Pesan berhasil ditambahkan'
            ],
            'data' => $chat
        ], 201);
    }

    /**
     * Update community chat (only own message)
     */
    public function update(Request $request, $id)
    {
        $chat = CommunityChat::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$chat) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'statusCode' => 404,
                    'message' => 'Pesan tidak ditemukan atau bukan milik Anda'
                ],
                'data' => null
            ], 404);
        }

        $validated = $request->validate([
            'message' => 'sometimes|string',
            'image' => 'nullable|string',
        ]);

        $chat->update($validated);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Pesan berhasil diperbarui'
            ],
            'data' => $chat
        ], 200);
    }

    /**
     * Delete community chat (only own message)
     */
    public function destroy($id)
    {
        $chat = CommunityChat::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$chat) {
            return response()->json([
                'meta' => [
                    'status' => 'error',
                    'statusCode' => 404,
                    'message' => 'Pesan tidak ditemukan atau bukan milik Anda'
                ],
                'data' => null
            ], 404);
        }

        if ($chat->image && Storage::disk('public')->exists($chat->image)) {
            Storage::disk('public')->delete($chat->image);
        }

        $chat->delete();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Pesan berhasil dihapus'
            ],
            'data' => null
        ], 200);
    }
}
