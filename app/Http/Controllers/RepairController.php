<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RepairRequest;
class RepairController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'model' => 'required|string|max:255',
        'phone' => 'required|regex:/^\+7\d{10}$/',
        'description' => 'nullable|string'
    ]);
    
    RepairRequest::create([
        'user_id' => auth()->id(),
        'model' => $request->model,
        'phone' => $request->phone,
        'description' => $request->description
    ]);

    return back()->with('success', 'Заявка успешно отправлена!');
}
public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:'.implode(',', RepairRequest::STATUSES)
    ]);

    $repairRequest = RepairRequest::findOrFail($id);
    $repairRequest->update(['status' => $request->status]);

    return back()->with('success', 'Статус обновлен');
}
}
