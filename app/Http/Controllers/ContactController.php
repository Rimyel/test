<?php

namespace App\Http\Controllers;
use App\Models\Poster;
use App\Models\User;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'phone' => 'required|regex:/^\+7\d{10}$/',
        'poster_id' => 'required|exists:posters,id',
    ]);

    ContactRequest::create([
        'user_id' => auth()->id(),
        'poster_id' => $request->poster_id,
        'phone' => $request->phone
    ]);

    return response()->json(['success' => true]);
}
}
