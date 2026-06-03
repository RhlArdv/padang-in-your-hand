<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    // -------------------------------------------------------
    // GET /api/admin/users
    // List semua user, filter by role, search by name/email
    // -------------------------------------------------------
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search by name atau email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $users,
        ]);
    }

    // -------------------------------------------------------
    // PUT /api/admin/users/{id}/role
    // Ubah role user (admin, super_admin)
    // -------------------------------------------------------
    public function updateRole(Request $request, int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        // Tidak bisa ubah role diri sendiri
        if ($user->id === $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak bisa mengubah role Anda sendiri.',
            ], 422);
        }

        $request->validate([
            'role' => ['required', 'in:super_admin,admin,operator,kontributor'],
        ]);

        $user->update(['role' => $request->role]);

        return response()->json([
            'success' => true,
            'message' => 'Role user "' . $user->name . '" berhasil diubah menjadi ' . $request->role,
            'data'    => $user,
        ]);
    }

    // -------------------------------------------------------
    // DELETE /api/admin/users/{id}
    // Hapus user (tidak bisa hapus diri sendiri)
    // -------------------------------------------------------
    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        // Tidak bisa hapus diri sendiri
        if ($user->id === $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak bisa menghapus akun Anda sendiri.',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User "' . $user->name . '" berhasil dihapus',
        ]);
    }
}
