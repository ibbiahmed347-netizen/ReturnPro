<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Tax Return - ReturnPro</title>
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
        @media print { .sidebar, .topbar, .no-print { display: none !important; } .main-content { margin-left: 0 !important; padding: 0 !important; } body { background: white; } }
    </style>
</head>
<body>
<div class="sidebar no-print">
    <div class="brand"><i class="fas fa-file-invoice me-2"></i>ReturnPro</div>
    <nav class="mt-2">
        <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('clients.index') }}" class="nav-link"><i class="fas fa-users"></i> Clients</a>
        <a href="{{ route('income-tax.index') }}" class="nav-link"><i class="fas fa-file-alt"></i> Income Tax</a>
        <a href="{{ route('sales-tax.index') }}" class="nav-link active"><i class="fas fa-receipt"></i> Sales Tax</a>
        <a href="{{ route('vouchers.index') }}" class="nav-link"><i class="fas fa-file-invoice-dollar"></i> Vouchers</a>
        <a href="#" class="nav-link"><i class="fas fa-money-bill-wave"></i> Collections</a>
        <a href="{{ route('expenses.index') }}" class="nav-link"><i class="fas fa-chart-bar"></i> Expenses</a>
        <a href="#" class="nav-link"><i class="fas fa-folder-open"></i> Documents</a>
        <a href="{{ route('notices.index') }}" class="nav-link"><i class="fas fa-bell"></i> Notices</a>
        <a href="{{ route('tasks.index') }}" class="nav-link"><i class="fas fa-tasks"></i> Tasks</a>
        <a href="#" class="nav-link"><i class="fas fa-chart-pie"></i> Reports</a>
        <a href="{{ route('users.index') }}" class="nav-link"><i class="fas fa-user-cog"></i> Users</a>
        <a href="{{ route('settings.index') }}" class="nav-link"><i class="fas fa-cog"></i> Settings</a>
    </nav>
</div>
<div class="main-content">
    <div class="topbar no-print">
        <h5 class="mb-0">
            {{ $salesTax->client->name }} — {{ $salesTax->month_name }} {{ $salesTax->year }}
            <span class="badge bg-{{ $salesTax->status == 'Published' ? 'success' : 'warning' }} ms-2">{{ $salesTax->status }}</span>
        </h5>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Print</button>
            @if($salesTax->status == 'Draft')
            <a href="{{ route('sales-tax.edit', $salesTax) }}" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i> Edit</a>
            <form method="POST" action="{{ route('sales-tax.publish', $salesTax) }}" class="d-inline" onsubmit="return confirm('Publish this return?')">
                @csrf <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Publish</button>
            </form>
            @endif
            <a href="{{ route('sales-tax.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>

    @if(session('success'))<div class="alert alert-success alert-dismissible fade show no-print">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif

    <div class="row g-3">
        {{-- Client Info --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="section-title">Client</div>
                    <p class="mb-1"><strong>{{ $salesTax->client->name }}</strong></p>
                    <p class="mb-1 text-muted">Case: {{ $salesTax->client->case_number ?? '-' }}</p>
                    <p class="mb-1 text-muted">NTN: {{ $salesTax->client->ntn ?? '-' }}</p>
                    <p class="mb-0 text-muted">Period: {{ $salesTax->month_name }} {{ $salesTax->year }}</p>
                </div>
            </div>
        </div>

        {{-- Tax Summary --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="section-title">Tax Summary</div>
                    <table class="table table-sm">
                        <tr><td>Total Sales</td><td class="text-end">Rs. {{ number_format($salesTax->total_sales, 0) }}</td></tr>
                        <tr><td>Output Tax (Sales Tax)</td><td class="text-end">Rs. {{ number_format($salesTax->total_sales_tax, 0) }}</td></tr>
                        <tr><td>Total Purchases</td><td class="text-end">Rs. {{ number_format($salesTax->total_purchases, 0) }}</td></tr>
                        <tr><td>Input Tax</td><td class="text-end">Rs. {{ number_format($salesTax->total_input_tax, 0) }}</td></tr>
                        <tr class="table-danger"><td><strong>Tax Payable</strong></td><td class="text-end"><strong>Rs. {{ number_format($salesTax->tax_payable, 0) }}</strong></td></tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sales --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="section-title">Sales Details</div>
                    <table class="table table-sm">
                        <thead><tr><th>Invoice No</th><th>Date</th><th class="text-end">Amount</th><th class="text-end">Tax</th></tr></thead>
                        <tbody>
                            @forelse($salesTax->sales as $sale)
                            <tr>
                                <td>{{ $sale->invoice_no ?? '-' }}</td>
                                <td>{{ $sale->invoice_date ?? '-' }}</td>
                                <td class="text-end">{{ number_format($sale->amount, 0) }}</td>
                                <td class="text-end">{{ number_format($sale->sales_tax, 0) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-muted text-center">No sales entries</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-dark">
                            <tr><td colspan="2"><strong>Total</strong></td><td class="text-end"><strong>{{ number_format($salesTax->total_sales, 0) }}</strong></td><td class="text-end"><strong>{{ number_format($salesTax->total_sales_tax, 0) }}</strong></td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Purchases --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="section-title">Purchase Details</div>
                    <table class="table table-sm">
                        <thead><tr><th>Invoice No</th><th>Date</th><th class="text-end">Amount</th><th class="text-end">Input Tax</th></tr></thead>
                        <tbody>
                            @forelse($salesTax->purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->invoice_no ?? '-' }}</td>
                                <td>{{ $purchase->invoice_date ?? '-' }}</td>
                                <td class="text-end">{{ number_format($purchase->amount, 0) }}</td>
                                <td class="text-end">{{ number_format($purchase->input_tax, 0) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-muted text-center">No purchase entries</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-dark">
                            <tr><td colspan="2"><strong>Total</strong></td><td class="text-end"><strong>{{ number_format($salesTax->total_purchases, 0) }}</strong></td><td class="text-end"><strong>{{ number_format($salesTax->total_input_tax, 0) }}</strong></td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>