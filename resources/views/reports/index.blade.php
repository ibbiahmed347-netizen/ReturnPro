<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - ReturnPro</title>
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
        .report-card { border: none; border-radius: 12px; box-shadow: 0 3px 15px rgba(0,0,0,0.08); transition: transform 0.2s; cursor: pointer; text-decoration: none; color: inherit; }
        .report-card:hover { transform: translateY(-3px); color: inherit; }
        .report-icon { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #fff; margin-bottom: 15px; }
    </style>
</head>
<body>
<div class="sidebar">
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
    <div class="topbar">
        <h5 class="mb-0">Reports</h5>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="{{ route('reports.collections') }}" class="report-card card d-block p-4">
                <div class="report-icon" style="background:#28a745"><i class="fas fa-money-bill-wave"></i></div>
                <h5>Collections Report</h5>
                <p class="text-muted mb-0">View all payments received from clients with date filters.</p>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('reports.outstanding') }}" class="report-card card d-block p-4">
                <div class="report-icon" style="background:#dc3545"><i class="fas fa-exclamation-circle"></i></div>
                <h5>Outstanding Report</h5>
                <p class="text-muted mb-0">View all unpaid and partially paid vouchers.</p>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('reports.expenses') }}" class="report-card card d-block p-4">
                <div class="report-icon" style="background:#fd7e14"><i class="fas fa-chart-bar"></i></div>
                <h5>Expense Report</h5>
                <p class="text-muted mb-0">View all expenses with category wise breakdown.</p>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('reports.client-wise') }}" class="report-card card d-block p-4">
                <div class="report-icon" style="background:#1e3c72"><i class="fas fa-users"></i></div>
                <h5>Client Wise Report</h5>
                <p class="text-muted mb-0">View billing, collection and outstanding per client.</p>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('reports.income-tax') }}" class="report-card card d-block p-4">
                <div class="report-icon" style="background:#6f42c1"><i class="fas fa-file-alt"></i></div>
                <h5>Income Tax Report</h5>
                <p class="text-muted mb-0">View all income tax returns by year and status.</p>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('reports.sales-tax') }}" class="report-card card d-block p-4">
                <div class="report-icon" style="background:#17a2b8"><i class="fas fa-receipt"></i></div>
                <h5>Sales Tax Report</h5>
                <p class="text-muted mb-0">View all sales tax returns with tax payable summary.</p>
            </a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>