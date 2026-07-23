<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Tax Returns - ReturnPro</title>
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
    </style>
</head>
<body>
<div class="sidebar">
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
    <div class="topbar">
        <h5 class="mb-0">Sales Tax Returns</h5>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>

    @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
    @if(session('error'))<div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search client, NTN, case no..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
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
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i></button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('sales-tax.create') }}" class="btn btn-success w-100"><i class="fas fa-plus"></i> Add Return</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Client</th>
                        <th>Period</th>
                        <th>Total Sales</th>
                        <th>Output Tax</th>
                        <th>Input Tax</th>
                        <th>Tax Payable</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $return)
                    <tr>
                        <td>
                            <a href="{{ route('clients.show', $return->client) }}" class="text-decoration-none fw-semibold">{{ $return->client->name }}</a>
                            <br><small class="text-muted">{{ $return->client->case_number ?? '' }}</small>
                        </td>
                        <td><span class="badge bg-primary">{{ $return->month_name }} {{ $return->year }}</span></td>
                        <td>Rs. {{ number_format($return->total_sales, 0) }}</td>
                        <td>Rs. {{ number_format($return->total_sales_tax, 0) }}</td>
                        <td>Rs. {{ number_format($return->total_input_tax, 0) }}</td>
                        <td><strong class="text-danger">Rs. {{ number_format($return->tax_payable, 0) }}</strong></td>
                        <td><span class="badge bg-{{ $return->status == 'Published' ? 'success' : 'warning' }}">{{ $return->status }}</span></td>
                        <td>
                            <a href="{{ route('sales-tax.show', $return) }}" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i></a>
                            @if($return->status == 'Draft')
                            <a href="{{ route('sales-tax.edit', $return) }}" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('sales-tax.destroy', $return) }}" class="d-inline" onsubmit="return confirm('Delete this return?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4"><i class="fas fa-receipt fa-2x mb-2 d-block"></i>No returns found. <a href="{{ route('sales-tax.create') }}">Add first return</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($returns->hasPages())<div class="card-footer">{{ $returns->appends(request()->query())->links() }}</div>@endif
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>