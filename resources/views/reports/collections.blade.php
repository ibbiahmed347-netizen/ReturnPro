<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Collections Report - ReturnPro</title>
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
        @media print { .sidebar, .topbar, .no-print { display: none !important; } .main-content { margin-left: 0 !important; } body { background: white; } }
    </style>
</head>
<body>
<div class="sidebar no-print">
    <div class="brand"><i class="fas fa-file-invoice me-2"></i>ReturnPro</div>
    <nav class="mt-2">
        <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('clients.index') }}" class="nav-link"><i class="fas fa-users"></i> Clients</a>
        <a href="{{ route('income-tax.index') }}" class="nav-link"><i class="fas fa-file-alt"></i> Income Tax</a>
        <a href="{{ route('sales-tax.index') }}" class="nav-link"><i class="fas fa-receipt"></i> Sales Tax</a>
        <a href="{{ route('vouchers.index') }}" class="nav-link"><i class="fas fa-file-invoice-dollar"></i> Vouchers</a>
        <a href="#" class="nav-link"><i class="fas fa-money-bill-wave"></i> Collections</a>
        <a href="{{ route('expenses.index') }}" class="nav-link"><i class="fas fa-chart-bar"></i> Expenses</a>
        <a href="#" class="nav-link"><i class="fas fa-folder-open"></i> Documents</a>
        <a href="{{ route('notices.index') }}" class="nav-link"><i class="fas fa-bell"></i> Notices</a>
        <a href="{{ route('tasks.index') }}" class="nav-link"><i class="fas fa-tasks"></i> Tasks</a>
        <a href="{{ route('reports.index') }}" class="nav-link active"><i class="fas fa-chart-pie"></i> Reports</a>
        <a href="{{ route('users.index') }}" class="nav-link"><i class="fas fa-user-cog"></i> Users</a>
        <a href="{{ route('settings.index') }}" class="nav-link"><i class="fas fa-cog"></i> Settings</a>
    </nav>
</div>
<div class="main-content">
    <div class="topbar no-print">
        <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Collections Report</h5>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Print</button>
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3 no-print">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <select name="client_id" class="form-select">
                        <option value="">All Clients</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3"><input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="From Date"></div>
                <div class="col-md-3"><input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="To Date"></div>
                <div class="col-md-3"><button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Filter</button></div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3" style="border-left: 4px solid #28a745 !important;">
        <div class="card-body py-2 d-flex justify-content-between align-items-center">
            <span class="text-muted">Total Collections</span>
            <h4 class="mb-0 text-success">Rs. {{ number_format($totalAmount, 0) }}</h4>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr><th>Receipt No</th><th>Date</th><th>Client</th><th>Voucher No</th><th>Payment Method</th><th class="text-end">Amount</th></tr>
                </thead>
                <tbody>
                    @forelse($receipts as $receipt)
                    <tr>
                        <td>{{ $receipt->receipt_no }}</td>
                        <td>{{ $receipt->receipt_date }}</td>
                        <td>{{ $receipt->client->name ?? '-' }}</td>
                        <td>{{ $receipt->voucher->voucher_no ?? '-' }}</td>
                        <td><span class="badge bg-secondary">{{ $receipt->payment_method }}</span></td>
                        <td class="text-end"><strong>Rs. {{ number_format($receipt->amount, 0) }}</strong></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No collections found.</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="table-dark">
                    <tr><td colspan="5"><strong>Total</strong></td><td class="text-end"><strong>Rs. {{ number_format($totalAmount, 0) }}</strong></td></tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>