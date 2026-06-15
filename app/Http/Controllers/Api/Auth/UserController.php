<?php

namespace App\Http\Controllers\Api\Auth; // Make sure this namespace matches your folder

use App\Http\Controllers\Controller; // 👈 ADD THIS LINE TO FIX THE ERROR
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
   public function index()
    {
        // Using response() lowercase consistently
        return response()->json(User::all(), 200);
    }

    /**
     * Display the specified user.
     */
    public function show(int $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404); // Changed from 400 to 404
        }

        return response()->json($user, 200);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|string'
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role']
        ]);

        return response()->json($user, 201);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, int $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404); // Changed from 400 to 404
        }

        // Validate the incoming data, ignoring the current user's email for unique check
        $validated = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'email'    => ['sometimes', 'required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|required|string|min:6',
            'role'     => 'sometimes|required|string'
        ]);

        // If a password is being updated, hash it before saving
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Fill and save only the validated data passed in the request
        $user->update($validated);

        return response()->json($user, 200);
    }
    public function destroy(int $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }
}
