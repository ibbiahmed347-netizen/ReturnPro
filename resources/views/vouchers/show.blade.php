<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher {{ $voucher->voucher_no }} - ReturnPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .sidebar { width: 250px; min-height: 100vh; background: #1e3c72; position: fixed; top: 0; left: 0; z-index: 100; }
        .sidebar .brand { padding: 20px; color: #fff; font-size: 20px; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 10px 20px; display: block; text-decoration: none; font-size: 14px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,0.1); color: #fff; }
        .sidebar .nav-link i { width: 20px; margin-right: 8px; }
        .main-content { margin-left: 250px; padding: 20px; }
        .topbar { background: #fff; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
        .section-title { font-size: 13px; font-weight: 700; text-transform: uppercase; color: #1e3c72; border-bottom: 2px solid #1e3c72; padding-bottom: 5px; margin-bottom: 15px; }
        .info-label { font-size: 12px; color: #888; margin-bottom: 2px; }
        .info-value { font-weight: 500; }

        /* Print Styles */
        @media print {
            .sidebar, .topbar, .no-print { display: none !important; }
            .main-content { margin-left: 0 !important; padding: 0 !important; }
            .print-area { display: block !important; }
            body { background: white; }
            .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        }

        .print-voucher {
            display: none;
            padding: 20px;
        }
        .print-header { text-align: center; border-bottom: 2px solid #1e3c72; padding-bottom: 15px; margin-bottom: 20px; }
        .print-header h2 { color: #1e3c72; font-weight: 800; margin: 0; }
        .print-header p { margin: 0; color: #666; }
        .print-footer { text-align: center; border-top: 1px solid #ddd; padding-top: 10px; margin-top: 20px; font-size: 12px; color: #888; }
    </style>
</head>
<body>

<div class="sidebar no-print">
    <div class="brand"><i class="fas fa-file-invoice me-2"></i>ReturnPro</div>
    <nav class="mt-2">
        <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('clients.index') }}" class="nav-link"><i class="fas fa-users"></i> Clients</a>
        <a href="#" class="nav-link"><i class="fas fa-file-alt"></i> Income Tax</a>
        <a href="#" class="nav-link"><i class="fas fa-receipt"></i> Sales Tax</a>
        <a href="{{ route('vouchers.index') }}" class="nav-link active"><i class="fas fa-file-invoice-dollar"></i> Vouchers</a>
        <a href="#" class="nav-link"><i class="fas fa-money-bill-wave"></i> Collections</a>
        <a href="#" class="nav-link"><i class="fas fa-chart-bar"></i> Expenses</a>
        <a href="#" class="nav-link"><i class="fas fa-folder-open"></i> Documents</a>
        <a href="#" class="nav-link"><i class="fas fa-bell"></i> Notices</a>
        <a href="#" class="nav-link"><i class="fas fa-tasks"></i> Tasks</a>
        <a href="#" class="nav-link"><i class="fas fa-chart-pie"></i> Reports</a>
        <a href="#" class="nav-link"><i class="fas fa-cog"></i> Settings</a>
    </nav>
</div>

<div class="main-content">
    <div class="topbar no-print">
        <h5 class="mb-0">
            <i class="fas fa-file-invoice-dollar me-2"></i>{{ $voucher->voucher_no }}
            <span class="badge bg-{{ $voucher->status == 'Paid' ? 'success' : ($voucher->status == 'Partial' ? 'warning' : 'danger') }} ms-2">
                {{ $voucher->status }}
            </span>
        </h5>
        <div class="d-flex gap-2">
            <button onclick="printVoucher()" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Print Voucher</button>
            @if($voucher->receipts->count() > 0)
            <button onclick="printReceipt()" class="btn btn-sm btn-success"><i class="fas fa-print"></i> Print Receipt</button>
            @endif
            <a href="{{ route('vouchers.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show no-print">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    {{-- PRINT AREA: VOUCHER --}}
    <div id="print-voucher" class="print-voucher">
        <div class="print-header">
            <h2>ReturnPro Tax Consultants</h2>
            <p>Tax Practice Management System</p>
        </div>
        <div class="text-center mb-3">
            <h4 style="color:#1e3c72; font-weight:800;">FEE VOUCHER</h4>
        </div>
        <div class="row mb-3">
            <div class="col-6">
                <table style="font-size:14px;">
                    <tr><td style="width:120px;"><strong>Voucher No:</strong></td><td>{{ $voucher->voucher_no }}</td></tr>
                    <tr><td><strong>Date:</strong></td><td>{{ $voucher->voucher_date }}</td></tr>
                    <tr><td><strong>Due Date:</strong></td><td>{{ $voucher->due_date ?? '-' }}</td></tr>
                    <tr><td><strong>Tax Year:</strong></td><td>{{ $voucher->taxYear->tax_year ?? '-' }}</td></tr>
                </table>
            </div>
            <div class="col-6">
                <table style="font-size:14px;">
                    <tr><td style="width:120px;"><strong>Client:</strong></td><td>{{ $voucher->client->name }}</td></tr>
                    <tr><td><strong>Case No:</strong></td><td>{{ $voucher->client->case_number ?? '-' }}</td></tr>
                    <tr><td><strong>CNIC:</strong></td><td>{{ $voucher->client->cnic ?? '-' }}</td></tr>
                    <tr><td><strong>NTN:</strong></td><td>{{ $voucher->client->ntn ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
        <table class="table table-bordered" style="font-size:14px;">
            <thead style="background:#1e3c72; color:white;">
                <tr><th>#</th><th>Service Description</th><th class="text-end">Amount (Rs.)</th></tr>
            </thead>
            <tbody>
                @foreach($voucher->items as $i => $item)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $item->service_name }}</td>
                    <td class="text-end">{{ number_format($item->amount, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr><td colspan="2" class="text-end"><strong>Total Amount:</strong></td><td class="text-end"><strong>{{ number_format($voucher->amount, 0) }}</strong></td></tr>
                @if($voucher->discount > 0)
                <tr><td colspan="2" class="text-end">Discount:</td><td class="text-end">- {{ number_format($voucher->discount, 0) }}</td></tr>
                @endif
                <tr style="background:#f8f9fa;"><td colspan="2" class="text-end"><strong>Net Payable:</strong></td><td class="text-end"><strong>Rs. {{ number_format($voucher->net_amount, 0) }}</strong></td></tr>
                <tr><td colspan="2" class="text-end">Amount Paid:</td><td class="text-end">Rs. {{ number_format($voucher->receipts->sum('amount'), 0) }}</td></tr>
                <tr style="background:#fff3cd;"><td colspan="2" class="text-end"><strong>Balance:</strong></td><td class="text-end"><strong>Rs. {{ number_format($voucher->net_amount - $voucher->receipts->sum('amount'), 0) }}</strong></td></tr>
            </tfoot>
        </table>
        @if($voucher->notes)
        <p style="font-size:13px;"><strong>Notes:</strong> {{ $voucher->notes }}</p>
        @endif
        <div class="row mt-4">
            <div class="col-6 text-center">
                <div style="border-top:1px solid #333; padding-top:5px; margin-top:40px;">Client Signature</div>
            </div>
            <div class="col-6 text-center">
                <div style="border-top:1px solid #333; padding-top:5px; margin-top:40px;">Authorized Signature</div>
            </div>
        </div>
        <div class="print-footer">
            <p>Thank you for your business | ReturnPro Tax Consultants</p>
        </div>
    </div>

    {{-- PRINT AREA: RECEIPT --}}
    <div id="print-receipt" class="print-voucher">
        <div class="print-header">
            <h2>ReturnPro Tax Consultants</h2>
            <p>Tax Practice Management System</p>
        </div>
        <div class="text-center mb-3">
            <h4 style="color:#28a745; font-weight:800;">PAYMENT RECEIPT</h4>
        </div>
        @foreach($voucher->receipts as $receipt)
        <div class="mb-4 p-3" style="border:1px solid #ddd; border-radius:8px;">
            <div class="row">
                <div class="col-6">
                    <table style="font-size:14px;">
                        <tr><td style="width:130px;"><strong>Receipt No:</strong></td><td>{{ $receipt->receipt_no }}</td></tr>
                        <tr><td><strong>Date:</strong></td><td>{{ $receipt->receipt_date }}</td></tr>
                        <tr><td><strong>Payment Method:</strong></td><td>{{ $receipt->payment_method }}</td></tr>
                        @if($receipt->bank_name)
                        <tr><td><strong>Bank:</strong></td><td>{{ $receipt->bank_name }}</td></tr>
                        @endif
                    </table>
                </div>
                <div class="col-6">
                    <table style="font-size:14px;">
                        <tr><td style="width:130px;"><strong>Client:</strong></td><td>{{ $voucher->client->name }}</td></tr>
                        <tr><td><strong>Voucher No:</strong></td><td>{{ $voucher->voucher_no }}</td></tr>
                        <tr><td><strong>Amount Paid:</strong></td><td><strong style="font-size:18px; color:#28a745;">Rs. {{ number_format($receipt->amount, 0) }}</strong></td></tr>
                    </table>
                </div>
            </div>
            @if($receipt->notes)
            <p style="font-size:13px;" class="mt-2"><strong>Notes:</strong> {{ $receipt->notes }}</p>
            @endif
        </div>
        @endforeach
        <div class="row mt-4">
            <div class="col-6 text-center">
                <div style="border-top:1px solid #333; padding-top:5px; margin-top:40px;">Client Signature</div>
            </div>
            <div class="col-6 text-center">
                <div style="border-top:1px solid #333; padding-top:5px; margin-top:40px;">Authorized Signature</div>
            </div>
        </div>
        <div class="print-footer">
            <p>Thank you for your payment | ReturnPro Tax Consultants</p>
        </div>
    </div>

    {{-- Normal View --}}
    <div id="normal-view">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="section-title">Voucher Details</div>
                        <div class="row g-3">
                            <div class="col-6"><div class="info-label">Voucher No</div><div class="info-value">{{ $voucher->voucher_no }}</div></div>
                            <div class="col-6"><div class="info-label">Voucher Date</div><div class="info-value">{{ $voucher->voucher_date }}</div></div>
                            <div class="col-6"><div class="info-label">Client</div><div class="info-value"><a href="{{ route('clients.show', $voucher->client) }}">{{ $voucher->client->name }}</a></div></div>
                            <div class="col-6"><div class="info-label">Tax Year</div><div class="info-value">{{ $voucher->taxYear->tax_year ?? '-' }}</div></div>
                            <div class="col-6"><div class="info-label">Due Date</div><div class="info-value">{{ $voucher->due_date ?? '-' }}</div></div>
                            <div class="col-6"><div class="info-label">Notes</div><div class="info-value">{{ $voucher->notes ?? '-' }}</div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="section-title">Amount Summary</div>
                        <table class="table table-sm">
                            <tr><td>Total Amount</td><td class="text-end">Rs. {{ number_format($voucher->amount, 0) }}</td></tr>
                            <tr><td>Discount</td><td class="text-end text-danger">- Rs. {{ number_format($voucher->discount, 0) }}</td></tr>
                            <tr class="table-dark"><td><strong>Net Amount</strong></td><td class="text-end"><strong>Rs. {{ number_format($voucher->net_amount, 0) }}</strong></td></tr>
                            <tr><td>Total Paid</td><td class="text-end text-success">Rs. {{ number_format($voucher->receipts->sum('amount'), 0) }}</td></tr>
                            <tr class="table-warning"><td><strong>Balance</strong></td><td class="text-end"><strong>Rs. {{ number_format($voucher->net_amount - $voucher->receipts->sum('amount'), 0) }}</strong></td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="section-title">Services</div>
                        <table class="table table-sm">
                            <thead><tr><th>Service</th><th class="text-end">Amount</th></tr></thead>
                            <tbody>
                                @foreach($voucher->items as $item)
                                <tr><td>{{ $item->service_name }}</td><td class="text-end">Rs. {{ number_format($item->amount, 0) }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if($voucher->status !== 'Paid')
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="section-title">Receive Payment</div>
                        <form method="POST" action="{{ route('receipts.store', $voucher) }}">
                            @csrf
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="receipt_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Amount</label>
                                    <input type="number" name="amount" class="form-control" min="1" step="0.01" value="{{ $voucher->net_amount - $voucher->receipts->sum('amount') }}" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Payment Method</label>
                                    <select name="payment_method" class="form-select" onchange="toggleBank(this.value)">
                                        <option value="Cash">Cash</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Cheque">Cheque</option>
                                    </select>
                                </div>
                                <div class="col-6" id="bank-field" style="display:none">
                                    <label class="form-label">Bank / Cheque No</label>
                                    <input type="text" name="bank_name" class="form-control" placeholder="Bank name">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Notes</label>
                                    <input type="text" name="notes" class="form-control" placeholder="Optional">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success w-100"><i class="fas fa-check me-1"></i> Record Payment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="section-title">Payment History</div>
                        @forelse($voucher->receipts as $receipt)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <strong>{{ $receipt->receipt_no }}</strong>
                                <span class="text-muted ms-2">{{ $receipt->receipt_date }}</span>
                                <span class="badge bg-secondary ms-2">{{ $receipt->payment_method }}</span>
                            </div>
                            <strong class="text-success">Rs. {{ number_format($receipt->amount, 0) }}</strong>
                        </div>
                        @empty
                        <p class="text-muted mb-0">No payments received yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleBank(val) {
        document.getElementById('bank-field').style.display = (val !== 'Cash') ? 'block' : 'none';
    }

    function printVoucher() {
        document.getElementById('normal-view').style.display = 'none';
        document.getElementById('print-voucher').style.display = 'block';
        document.getElementById('print-receipt').style.display = 'none';
        window.print();
        document.getElementById('normal-view').style.display = 'block';
        document.getElementById('print-voucher').style.display = 'none';
    }

    function printReceipt() {
        document.getElementById('normal-view').style.display = 'none';
        document.getElementById('print-receipt').style.display = 'block';
        document.getElementById('print-voucher').style.display = 'none';
        window.print();
        document.getElementById('normal-view').style.display = 'block';
        document.getElementById('print-receipt').style.display = 'none';
    }
</script>
</body>
</html>