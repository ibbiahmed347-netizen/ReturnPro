<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice - ReturnPro</title>
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
        .reply-card { background: #f8f9fa; border-radius: 8px; padding: 12px; margin-bottom: 10px; border-left: 3px solid #1e3c72; }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="brand"><i class="fas fa-file-invoice me-2"></i>ReturnPro</div>
    <nav class="mt-2">
        <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('clients.index') }}" class="nav-link"><i class="fas fa-users"></i> Clients</a>
        <a href="{{ route('income-tax.index') }}" class="nav-link"><i class="fas fa-file-alt"></i> Income Tax</a>
        <a href="#" class="nav-link"><i class="fas fa-receipt"></i> Sales Tax</a>
        <a href="{{ route('vouchers.index') }}" class="nav-link"><i class="fas fa-file-invoice-dollar"></i> Vouchers</a>
        <a href="#" class="nav-link"><i class="fas fa-money-bill-wave"></i> Collections</a>
        <a href="{{ route('expenses.index') }}" class="nav-link"><i class="fas fa-chart-bar"></i> Expenses</a>
        <a href="#" class="nav-link"><i class="fas fa-folder-open"></i> Documents</a>
        <a href="{{ route('notices.index') }}" class="nav-link active"><i class="fas fa-bell"></i> Notices</a>
        <a href="{{ route('tasks.index') }}" class="nav-link"><i class="fas fa-tasks"></i> Tasks</a>
        <a href="#" class="nav-link"><i class="fas fa-chart-pie"></i> Reports</a>
        <a href="{{ route('users.index') }}" class="nav-link"><i class="fas fa-user-cog"></i> Users</a>
        <a href="{{ route('settings.index') }}" class="nav-link"><i class="fas fa-cog"></i> Settings</a>
    </nav>
</div>
<div class="main-content">
    <div class="topbar">
        <h5 class="mb-0">
            <i class="fas fa-bell me-2"></i>{{ $notice->subject }}
            <span class="badge bg-{{ $notice->status_color }} ms-2">{{ $notice->status }}</span>
        </h5>
        <div class="d-flex gap-2">
            <a href="{{ route('notices.edit', $notice) }}" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i> Edit</a>
            <a href="{{ route('notices.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>

    @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="section-title">Notice Details</div>
                    <div class="row g-2">
                        <div class="col-6"><div class="info-label">Notice Number</div><div class="info-value">{{ $notice->notice_number ?? '-' }}</div></div>
                        <div class="col-6"><div class="info-label">Notice Date</div><div class="info-value">{{ $notice->notice_date }}</div></div>
                        <div class="col-12"><div class="info-label">Client</div><div class="info-value"><a href="{{ route('clients.show', $notice->client) }}">{{ $notice->client->name }}</a> {{ $notice->client->case_number ? '(Case: '.$notice->client->case_number.')' : '' }}</div></div>
                        <div class="col-12"><div class="info-label">Subject</div><div class="info-value">{{ $notice->subject }}</div></div>
                        @if($notice->description)
                        <div class="col-12"><div class="info-label">Description</div><div class="info-value">{{ $notice->description }}</div></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Add Reply --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="section-title">Add Reply</div>
                    <form method="POST" action="{{ route('notices.update', $notice) }}">
                        @csrf @method('PUT')
                        <input type="hidden" name="add_reply" value="1">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label">Reply Date</label>
                                <input type="date" name="reply_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Remarks</label>
                                <textarea name="remarks" class="form-control" rows="3" placeholder="Reply details..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-reply me-1"></i> Add Reply</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Replies --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="section-title">Reply History ({{ $notice->replies->count() }})</div>
                    @forelse($notice->replies as $reply)
                    <div class="reply-card">
                        <div class="d-flex justify-content-between mb-1">
                            <strong>{{ $reply->repliedBy->name ?? 'Unknown' }}</strong>
                            <span class="text-muted small">{{ $reply->reply_date }}</span>
                        </div>
                        <p class="mb-0">{{ $reply->remarks }}</p>
                    </div>
                    @empty
                    <p class="text-muted mb-0">No replies yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>