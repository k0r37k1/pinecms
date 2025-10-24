<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoController extends Controller
{
    // Missing return type hint (bad!)
    public function test(Request $request)
    {
        // Direct env() usage (bad!)
        $apiKey = env('API_KEY');

        // No validation (bad!)
        $data = $request->all();

        // Missing error handling
        return response()->json($data);
    }

    // Another bad example
    public function store(Request $request)
    {
        // No FormRequest validation
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Not hashed!
        ]);

        return $user;
    }
}
