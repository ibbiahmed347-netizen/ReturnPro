<?php

namespace App\Http\Controllers;

use App\Models\SalesTaxReturn;
use App\Models\Client;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class SalesTaxReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesTaxReturn::with(['client', 'sales', 'purchases']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('client', fn($q) => $q->where('name', 'like', "%$search%")
                ->orWhere('case_number', 'like', "%$search%")
                ->orWhere('ntn', 'like', "%$search%"));
        }

        if ($request->filled('month')) $query->where('month', $request->month);
        if ($request->filled('year'))  $query->where('year', $request->year);
        if ($request->filled('status')) $query->where('status', $request->status);

        $returns = $query->orderBy('year', 'desc')->orderBy('month', 'desc')->paginate(20);

        return view('sales_tax.index', compact('returns'));
    }

    public function create()
    {
        $clients = Client::where('status', 'Active')->orderBy('name')->get();
        return view('sales_tax.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'month'     => 'required|integer|min:1|max:12',
            'year'      => 'required|integer|min:2000|max:2100',
        ]);

        $exists = SalesTaxReturn::where('client_id', $request->client_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->exists();

        if ($exists) {
            return back()->withErrors(['month' => 'Return already exists for this client, month and year.'])->withInput();
        }

        $return = SalesTaxReturn::create([
            'client_id' => $request->client_id,
            'month'     => $request->month,
            'year'      => $request->year,
            'status'    => 'Draft',
            'notes'     => $request->notes,
        ]);

        // Save Sales
        if ($request->sales) {
            foreach ($request->sales as $sale) {
                if (!empty($sale['amount'])) {
                    $return->sales()->create([
                        'invoice_no'   => $sale['invoice_no'] ?? null,
                        'invoice_date' => $sale['invoice_date'] ?? null,
                        'amount'       => $sale['amount'] ?? 0,
                        'sales_tax'    => $sale['sales_tax'] ?? 0,
                    ]);
                }
            }
        }

        // Save Purchases
        if ($request->purchases) {
            foreach ($request->purchases as $purchase) {
                if (!empty($purchase['amount'])) {
                    $return->purchases()->create([
                        'invoice_no'   => $purchase['invoice_no'] ?? null,
                        'invoice_date' => $purchase['invoice_date'] ?? null,
                        'amount'       => $purchase['amount'] ?? 0,
                        'input_tax'    => $purchase['input_tax'] ?? 0,
                    ]);
                }
            }
        }

        UserActivityLog::log('Created sales tax return', 'sales_tax_returns', $return->id);

        return redirect()->route('sales-tax.show', $return)->with('success', 'Sales Tax Return created successfully!');
    }

    public function show(SalesTaxReturn $salesTax)
    {
        $salesTax->load(['client', 'sales', 'purchases']);
        return view('sales_tax.show', compact('salesTax'));
    }

    public function edit(SalesTaxReturn $salesTax)
    {
        if ($salesTax->status === 'Published') {
            return back()->with('error', 'Published returns cannot be edited.');
        }
        $salesTax->load(['sales', 'purchases']);
        $clients = Client::where('status', 'Active')->orderBy('name')->get();
        return view('sales_tax.edit', compact('salesTax', 'clients'));
    }

    public function update(Request $request, SalesTaxReturn $salesTax)
    {
        if ($salesTax->status === 'Published') {
            return back()->with('error', 'Published returns cannot be edited.');
        }

        $salesTax->update(['notes' => $request->notes]);

        // Delete old & re-insert
        $salesTax->sales()->delete();
        $salesTax->purchases()->delete();

        if ($request->sales) {
            foreach ($request->sales as $sale) {
                if (!empty($sale['amount'])) {
                    $salesTax->sales()->create([
                        'invoice_no'   => $sale['invoice_no'] ?? null,
                        'invoice_date' => $sale['invoice_date'] ?? null,
                        'amount'       => $sale['amount'] ?? 0,
                        'sales_tax'    => $sale['sales_tax'] ?? 0,
                    ]);
                }
            }
        }

        if ($request->purchases) {
            foreach ($request->purchases as $purchase) {
                if (!empty($purchase['amount'])) {
                    $salesTax->purchases()->create([
                        'invoice_no'   => $purchase['invoice_no'] ?? null,
                        'invoice_date' => $purchase['invoice_date'] ?? null,
                        'amount'       => $purchase['amount'] ?? 0,
                        'input_tax'    => $purchase['input_tax'] ?? 0,
                    ]);
                }
            }
        }

        UserActivityLog::log('Updated sales tax return', 'sales_tax_returns', $salesTax->id);

        return redirect()->route('sales-tax.show', $salesTax)->with('success', 'Return updated successfully!');
    }

    public function publish(SalesTaxReturn $salesTax)
    {
        $salesTax->update(['status' => 'Published']);
        UserActivityLog::log('Published sales tax return', 'sales_tax_returns', $salesTax->id);
        return back()->with('success', 'Return published successfully!');
    }

    public function destroy(SalesTaxReturn $salesTax)
    {
        if ($salesTax->status === 'Published') {
            return back()->with('error', 'Published returns cannot be deleted.');
        }
        UserActivityLog::log('Deleted sales tax return', 'sales_tax_returns', $salesTax->id);
        $salesTax->delete();
        return redirect()->route('sales-tax.index')->with('success', 'Return deleted successfully!');
    }
}