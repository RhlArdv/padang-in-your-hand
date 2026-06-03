<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // -------------------------------------------------------
    // POST /api/auth/register
    // -------------------------------------------------------
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'no_hp'    => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'no_hp'    => $request->no_hp,
            'role'     => 'kontributor', // default role
        ]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data'    => [
                'user'  => $this->formatUser($user),
                'token' => $token,
            ],
        ], 201);
    }

    // -------------------------------------------------------
    // POST /api/auth/login
    // -------------------------------------------------------
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        $user  = Auth::user();

        // Hapus token lama supaya tidak numpuk
        $user->tokens()->delete();

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data'    => [
                'user'  => $this->formatUser($user),
                'token' => $token,
            ],
        ]);
    }

    // -------------------------------------------------------
    // POST /api/auth/logout
    // Header: Authorization: Bearer {token}
    // -------------------------------------------------------
    public function logout(Request $request): JsonResponse
    {
        // Hapus token yang sedang dipakai
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    // -------------------------------------------------------
    // GET /api/auth/me
    // Ambil data user yang sedang login
    // -------------------------------------------------------
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->formatUser($request->user()),
        ]);
    }

    // -------------------------------------------------------
    // PUT /api/auth/profile
    // Update profil user
    // -------------------------------------------------------
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'name'     => ['sometimes', 'string', 'max:255'],
            'no_hp'    => ['sometimes', 'nullable', 'string', 'max:20'],
            'foto'     => ['sometimes', 'nullable', 'image', 'max:2048'], // max 2MB
            'password' => ['sometimes', 'confirmed', Password::min(8)],
        ]);

        // Update foto jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $path = $request->file('foto')->store('foto-profil', 'public');
            $user->foto = $path;
        }

        if ($request->filled('name'))     $user->name  = $request->name;
        if ($request->filled('no_hp'))    $user->no_hp = $request->no_hp;
        if ($request->filled('password')) $user->password = Hash::make($request->password);

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data'    => $this->formatUser($user),
        ]);
    }

    // -------------------------------------------------------
    // Helper: format data user untuk response JSON
    // -------------------------------------------------------
    private function formatUser(User $user): array
    {
        return [
            'id'        => $user->id,
            'name'      => $user->name,
            'email'     => $user->email,
            'role'      => $user->role,
            'no_hp'     => $user->no_hp,
            'foto'      => $user->foto
                            ? asset('storage/' . $user->foto)
                            : null,
            'created_at' => $user->created_at,
        ];
    }
}