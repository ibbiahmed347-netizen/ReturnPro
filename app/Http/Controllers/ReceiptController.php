<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Voucher;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function store(Request $request, Voucher $voucher)
    {
        $request->validate([
            'receipt_date'   => 'required|date',
            'amount'         => 'required|numeric|min:1',
            'payment_method' => 'required|string',
        ]);

        $totalPaid = $voucher->receipts->sum('amount') + $request->amount;

        $receipt = Receipt::create([
            'receipt_no'     => 'RCP-' . date('Y') . '-' . str_pad(Receipt::count() + 1, 4, '0', STR_PAD_LEFT),
            'voucher_id'     => $voucher->id,
            'client_id'      => $voucher->client_id,
            'receipt_date'   => $request->receipt_date,
            'amount'         => $request->amount,
            'payment_method' => $request->payment_method,
            'bank_name'      => $request->bank_name,
            'cheque_no'      => $request->cheque_no,
            'notes'          => $request->notes,
            'received_by'    => auth()->id(),
        ]);

        // Update voucher status
        if ($totalPaid >= $voucher->net_amount) {
            $voucher->update(['status' => 'Paid']);
        } else {
            $voucher->update(['status' => 'Partial']);
        }

        UserActivityLog::log('Added receipt', 'receipts', $receipt->id);

        return back()->with('success', 'Payment recorded successfully!');
    }
}