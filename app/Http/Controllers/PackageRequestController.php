<?php

namespace App\Http\Controllers;

use App\Models\PackageRequest;
use Illuminate\Http\Request;

class PackageRequestController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:64'],
            'message' => ['required', 'string', 'max:2000'],
            'package' => ['required', 'string', 'max:255'],
        ]);

        PackageRequest::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'company' => $data['company'] ?? null,
            'phone' => $data['phone'] ?? null,
            'message' => $data['message'],
            'package' => $data['package'],
            'status' => 'pending',
            'seen' => false,
        ]);

        return back()->withInput($request->only(['package']))->with('pricing_status', 'Your package request has been submitted successfully.');
    }
}
