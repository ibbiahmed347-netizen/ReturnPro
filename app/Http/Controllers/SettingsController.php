<?php

namespace App\Http\Controllers;

use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = DB::table('settings')->first();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email'        => 'nullable|email',
            'phone'        => 'nullable|string|max:50',
        ]);

        DB::table('settings')->updateOrInsert(
            ['id' => 1],
            [
                'company_name' => $request->company_name,
                'address'      => $request->address,
                'phone'        => $request->phone,
                'email'        => $request->email,
                'ntn'          => $request->ntn,
                'city'         => $request->city,
                'updated_at'   => now(),
            ]
        );

        UserActivityLog::log('Updated settings');

        return back()->with('success', 'Settings updated successfully!');
    }
}