<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Voucher;
use App\Models\Receipt;
use App\Models\Expense;
use App\Models\IncomeTaxReturn;
use App\Models\SalesTaxReturn;
use App\Models\TaxYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    // Collections Report
    public function collections(Request $request)
    {
        $query = Receipt::with(['client', 'voucher']);

        if ($request->filled('from_date')) $query->where('receipt_date', '>=', $request->from_date);
        if ($request->filled('to_date'))   $query->where('receipt_date', '<=', $request->to_date);
        if ($request->filled('client_id')) $query->where('client_id', $request->client_id);

        $receipts    = $query->orderBy('receipt_date', 'desc')->get();
        $totalAmount = $receipts->sum('amount');
        $clients     = Client::where('status', 'Active')->orderBy('name')->get();

        return view('reports.collections', compact('receipts', 'totalAmount', 'clients'));
    }

    // Outstanding Report
    public function outstanding(Request $request)
    {
        $query = Voucher::with(['client', 'taxYear'])
            ->whereIn('status', ['Unpaid', 'Partial']);

        if ($request->filled('client_id')) $query->where('client_id', $request->client_id);

        $vouchers    = $query->orderBy('voucher_date')->get();
        $totalAmount = $vouchers->sum('net_amount');
        $totalPaid   = $vouchers->sum(fn($v) => $v->receipts->sum('amount'));
        $totalDue    = $totalAmount - $totalPaid;
        $clients     = Client::where('status', 'Active')->orderBy('name')->get();

        return view('reports.outstanding', compact('vouchers', 'totalAmount', 'totalPaid', 'totalDue', 'clients'));
    }

    // Expense Report
    public function expenses(Request $request)
    {
        $query = \App\Models\Expense::with(['category']);

        if ($request->filled('from_date'))   $query->where('expense_date', '>=', $request->from_date);
        if ($request->filled('to_date'))     $query->where('expense_date', '<=', $request->to_date);
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);

        $expenses    = $query->orderBy('expense_date', 'desc')->get();
        $totalAmount = $expenses->sum('amount');

        $categoryWise = $expenses->groupBy('category.category_name')
            ->map(fn($group) => $group->sum('amount'))
            ->sortDesc();

        $categories = \App\Models\ExpenseCategory::orderBy('category_name')->get();

        return view('reports.expenses', compact('expenses', 'totalAmount', 'categoryWise', 'categories'));
    }

    // Client Wise Report
    public function clientWise(Request $request)
    {
        $query = Client::with(['vouchers.receipts', 'incomeTaxReturns', 'salesTaxReturns']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                  ->orWhere('case_number', 'like', "%$search%");
        }

        if ($request->filled('status')) $query->where('status', $request->status);

        $clients = $query->orderBy('name')->get()->map(function($client) {
            $client->total_billed    = $client->vouchers->sum('net_amount');
            $client->total_collected = $client->vouchers->sum(fn($v) => $v->receipts->sum('amount'));
            $client->total_due       = $client->total_billed - $client->total_collected;
            $client->income_tax_count = $client->incomeTaxReturns->count();
            $client->sales_tax_count  = $client->salesTaxReturns->count();
            return $client;
        });

        return view('reports.client_wise', compact('clients'));
    }

    // Income Tax Report
    public function incomeTax(Request $request)
    {
        $query = IncomeTaxReturn::with(['client', 'taxYear', 'incomeDetails']);

        if ($request->filled('tax_year_id')) $query->where('tax_year_id', $request->tax_year_id);
        if ($request->filled('status'))      $query->where('return_status', $request->status);

        $returns  = $query->orderBy('created_at', 'desc')->get();
        $taxYears = TaxYear::orderBy('tax_year', 'desc')->get();

        return view('reports.income_tax', compact('returns', 'taxYears'));
    }

    // Sales Tax Report
    public function salesTax(Request $request)
    {
        $query = SalesTaxReturn::with(['client', 'sales', 'purchases']);

        if ($request->filled('month')) $query->where('month', $request->month);
        if ($request->filled('year'))  $query->where('year', $request->year);
        if ($request->filled('status')) $query->where('status', $request->status);

        $returns = $query->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

        return view('reports.sales_tax', compact('returns'));
    }
}