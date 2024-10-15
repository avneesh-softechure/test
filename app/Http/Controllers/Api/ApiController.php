<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ApiController extends Controller
{
    public function index()
    {
        $users = User::all(); 

        return response()->json([
            'success' => true,
            'message' => 'Users data retrieved successfully!',
            'data' => $users,
        ], 200);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'desc' => 'required|string|max:1000',
            'role' => 'required|integer',
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->hasFile('profile_pic')) {
            $profilePic = $request->file('profile_pic');
            $fileName = time() . '_' . $profilePic->getClientOriginalName();
            $filePath = $profilePic->storeAs('uploads', $fileName, 'public');
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'desc' => $request->input('desc'),
            'role' => $request->input('role'),
            'profile_pic' => $filePath ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data saved successfully!',
            'data' => $user,
        ], 200);
    }
}
