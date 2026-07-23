<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\Client;
use App\Models\TaxYear;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::with(['client', 'taxYear']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('voucher_no', 'like', "%$search%")
                  ->orWhereHas('client', fn($q) => $q->where('name', 'like', "%$search%")
                  ->orWhere('case_number', 'like', "%$search%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $vouchers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        $clients  = Client::where('status', 'Active')->orderBy('name')->get();
        $taxYears = TaxYear::orderBy('tax_year', 'desc')->get();
        $voucherNo = 'VCH-' . date('Y') . '-' . str_pad(Voucher::count() + 1, 4, '0', STR_PAD_LEFT);

        return view('vouchers.create', compact('clients', 'taxYears', 'voucherNo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_no'   => 'required|unique:vouchers,voucher_no',
            'client_id'    => 'required|exists:clients,id',
            'voucher_date' => 'required|date',
            'amount'       => 'required|numeric|min:0',
            'items'        => 'required|array|min:1',
            'items.*.service_name' => 'required|string',
            'items.*.amount'       => 'required|numeric|min:0',
        ]);

        $discount   = $request->discount ?? 0;
        $netAmount  = $request->amount - $discount;

        $voucher = Voucher::create([
            'voucher_no'   => $request->voucher_no,
            'client_id'    => $request->client_id,
            'tax_year_id'  => $request->tax_year_id,
            'voucher_date' => $request->voucher_date,
            'due_date'     => $request->due_date,
            'amount'       => $request->amount,
            'discount'     => $discount,
            'net_amount'   => $netAmount,
            'status'       => 'Unpaid',
            'notes'        => $request->notes,
            'created_by'   => auth()->id(),
        ]);

        foreach ($request->items as $item) {
            $voucher->items()->create([
                'service_name' => $item['service_name'],
                'amount'       => $item['amount'],
            ]);
        }

        UserActivityLog::log('Created voucher', 'vouchers', $voucher->id);

        return redirect()->route('vouchers.show', $voucher)->with('success', 'Voucher created successfully!');
    }

    public function show(Voucher $voucher)
    {
        $voucher->load(['client', 'taxYear', 'items', 'receipts']);
        return view('vouchers.show', compact('voucher'));
    }

    public function destroy(Voucher $voucher)
    {
        if ($voucher->status !== 'Unpaid') {
            return back()->with('error', 'Only unpaid vouchers can be deleted.');
        }
        UserActivityLog::log('Deleted voucher', 'vouchers', $voucher->id);
        $voucher->delete();

        return redirect()->route('vouchers.index')->with('success', 'Voucher deleted successfully!');
    }
}