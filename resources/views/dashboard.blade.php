<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ReturnPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #1e3c72;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
        }
        .sidebar .brand {
            padding: 20px;
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 10px 20px;
            display: block;
            text-decoration: none;
            font-size: 14px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        .sidebar .nav-link i { width: 20px; margin-right: 8px; }
        .main-content { margin-left: 250px; padding: 20px; }
        .topbar {
            background: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }
        .stat-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        }
        .stat-card .icon {
            width: 55px; height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #fff;
        }
    </style>
</head>
<body>

{{-- Sidebar --}}
<div class="sidebar">
    <div class="brand"><i class="fas fa-file-invoice me-2"></i>ReturnPro</div>
    <nav class="mt-2">
        <a href="{{ route('dashboard') }}" class="nav-link active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="#" class="nav-link"><i class="fas fa-users"></i> Clients</a>
        <a href="#" class="nav-link"><i class="fas fa-file-alt"></i> Income Tax</a>
        <a href="#" class="nav-link"><i class="fas fa-receipt"></i> Sales Tax</a>
        <a href="#" class="nav-link"><i class="fas fa-file-invoice-dollar"></i> Vouchers</a>
        <a href="#" class="nav-link"><i class="fas fa-money-bill-wave"></i> Collections</a>
        <a href="#" class="nav-link"><i class="fas fa-chart-bar"></i> Expenses</a>
        <a href="#" class="nav-link"><i class="fas fa-folder-open"></i> Documents</a>
        <a href="#" class="nav-link"><i class="fas fa-bell"></i> Notices</a>
        <a href="#" class="nav-link"><i class="fas fa-tasks"></i> Tasks</a>
        <a href="#" class="nav-link"><i class="fas fa-chart-pie"></i> Reports</a>
        <a href="#" class="nav-link"><i class="fas fa-cog"></i> Settings</a>
    </nav>
</div>

{{-- Main Content --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <h5 class="mb-0">Dashboard</h5>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted">Welcome, <strong>{{ auth()->user()->name }}</strong></span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon" style="background:#1e3c72">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Active Clients</div>
                        <div class="fs-4 fw-bold">{{ $stats['total_clients'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon" style="background:#28a745">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Tax Returns</div>
                        <div class="fs-4 fw-bold">{{ $stats['total_returns'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon" style="background:#ffc107">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Pending Tasks</div>
                        <div class="fs-4 fw-bold">{{ $stats['pending_tasks'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon" style="background:#dc3545">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Unpaid Vouchers</div>
                        <div class="fs-4 fw-bold">{{ $stats['unpaid_vouchers'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Welcome Message --}}
    <div class="card border-0 shadow-sm p-4">
        <h5 class="text-muted">
            <i class="fas fa-check-circle text-success me-2"></i>
            ReturnPro system is ready! Start by adding your first client.
        </h5>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>