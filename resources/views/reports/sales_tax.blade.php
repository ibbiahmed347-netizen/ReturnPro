<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Tax Report - ReturnPro</title>
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
        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Sales Tax Returns Report</h5>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Print</button>
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3 no-print">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <select name="month" class="form-select">
                        <option value="">All Months</option>
                        @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="year" class="form-select">
                        <option value="">All Years</option>
                        @for($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Published" {{ request('status') == 'Published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Filter</button></div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3"><div class="card border-0 shadow-sm p-3 text-center"><div class="text-muted">Total Returns</div><h4 class="text-primary">{{ $returns->count() }}</h4></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm p-3 text-center"><div class="text-muted">Total Sales</div><h4 class="text-info">Rs. {{ number_format($returns->sum('total_sales'), 0) }}</h4></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm p-3 text-center"><div class="text-muted">Output Tax</div><h4 class="text-warning">Rs. {{ number_format($returns->sum('total_sales_tax'), 0) }}</h4></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm p-3 text-center"><div class="text-muted">Tax Payable</div><h4 class="text-danger">Rs. {{ number_format($returns->sum('tax_payable'), 0) }}</h4></div></div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr><th>Client</th><th>Period</th><th class="text-end">Sales</th><th class="text-end">Output Tax</th><th class="text-end">Input Tax</th><th class="text-end">Tax Payable</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($returns as $return)
                    <tr>
                        <td>{{ $return->client->name }}<br><small class="text-muted">{{ $return->client->case_number ?? '' }}</small></td>
                        <td><span class="badge bg-primary">{{ $return->month_name }} {{ $return->year }}</span></td>
                        <td class="text-end">Rs. {{ number_format($return->total_sales, 0) }}</td>
                        <td class="text-end">Rs. {{ number_format($return->total_sales_tax, 0) }}</td>
                        <td class="text-end">Rs. {{ number_format($return->total_input_tax, 0) }}</td>
                        <td class="text-end"><strong class="text-danger">Rs. {{ number_format($return->tax_payable, 0) }}</strong></td>
                        <td><span class="badge bg-{{ $return->status == 'Published' ? 'success' : 'warning' }}">{{ $return->status }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No returns found.</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="table-dark">
                    <tr><td colspan="2"><strong>Total</strong></td><td class="text-end"><strong>Rs. {{ number_format($returns->sum('total_sales'), 0) }}</strong></td><td class="text-end"><strong>Rs. {{ number_format($returns->sum('total_sales_tax'), 0) }}</strong></td><td class="text-end"><strong>Rs. {{ number_format($returns->sum('total_input_tax'), 0) }}</strong></td><td class="text-end"><strong>Rs. {{ number_format($returns->sum('tax_payable'), 0) }}</strong></td><td></td></tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>