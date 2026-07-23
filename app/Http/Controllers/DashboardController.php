<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_clients'   => DB::table('clients')->where('status', 'Active')->count(),
            'total_returns'   => DB::table('income_tax_returns')->count(),
            'pending_tasks'   => DB::table('tasks')->where('status', 'Pending')->count(),
            'unpaid_vouchers' => DB::table('vouchers')->where('status', 'Unpaid')->count(),
        ];

        return view('dashboard', compact('stats'));
    }
}