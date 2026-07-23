<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $client->name }} - ReturnPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .sidebar {
            width: 250px; min-height: 100vh;
            background: #1e3c72; position: fixed;
            top: 0; left: 0; z-index: 100;
        }
        .sidebar .brand {
            padding: 20px; color: #fff;
            font-size: 20px; font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 10px 20px; display: block;
            text-decoration: none; font-size: 14px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1); color: #fff;
        }
        .sidebar .nav-link i { width: 20px; margin-right: 8px; }
        .main-content { margin-left: 250px; padding: 20px; }
        .topbar {
            background: #fff; padding: 12px 20px;
            border-radius: 8px; margin-bottom: 20px;
            display: flex; justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }
        .section-title {
            font-size: 13px; font-weight: 700;
            text-transform: uppercase; color: #1e3c72;
            border-bottom: 2px solid #1e3c72;
            padding-bottom: 5px; margin-bottom: 15px;
        }
        .info-label { font-size: 12px; color: #888; margin-bottom: 2px; }
        .info-value { font-weight: 500; }
    </style>
</head>
<body>

{{-- Sidebar --}}
<div class="sidebar">
    <div class="brand"><i class="fas fa-file-invoice me-2"></i>ReturnPro</div>
    <nav class="mt-2">
        <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('clients.index') }}" class="nav-link active"><i class="fas fa-users"></i> Clients</a>
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
        <h5 class="mb-0">
            <i class="fas fa-user me-2"></i>{{ $client->name }}
            <span class="badge bg-{{ $client->status == 'Active' ? 'success' : 'danger' }} ms-2">
                {{ $client->status }}
            </span>
        </h5>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-warning text-white">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('clients.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">

        {{-- Basic Info --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="section-title">Basic Information</div>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="info-label">Client Code</div>
                            <div class="info-value">{{ $client->client_code }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">Case Number</div>
                            <div class="info-value">{{ $client->case_number ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">Full Name</div>
                            <div class="info-value">{{ $client->name }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">Father Name</div>
                            <div class="info-value">{{ $client->father_name ?? '-' }}</div>
                        </div>
                        <div class="col-12">
                            <div class="info-label">Business Name</div>
                            <div class="info-value">{{ $client->business_name ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">Registration Date</div>
                            <div class="info-value">{{ $client->registration_date ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">Annual Fee</div>
                            <div class="info-value">Rs. {{ number_format($client->annual_fee, 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tax & Contact Info --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="section-title">Tax & Contact Information</div>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="info-label">CNIC</div>
                            <div class="info-value">{{ $client->cnic ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">NTN</div>
                            <div class="info-value">{{ $client->ntn ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">FBR Username</div>
                            <div class="info-value">{{ $client->fbr_username ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">Mobile</div>
                            <div class="info-value">{{ $client->mobile ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">WhatsApp</div>
                            <div class="info-value">{{ $client->whatsapp ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $client->email ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">City</div>
                            <div class="info-value">{{ $client->city ?? '-' }}</div>
                        </div>
                        <div class="col-12">
                            <div class="info-label">Address</div>
                            <div class="info-value">{{ $client->address ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tax Returns Summary --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="section-title">Income Tax Returns</div>
                    @forelse($client->incomeTaxReturns as $return)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span>Tax Year: <strong>{{ $return->tax_year_id }}</strong></span>
                            <span class="badge bg-{{ $return->return_status == 'Published' ? 'success' : 'warning' }}">
                                {{ $return->return_status }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No tax returns found.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Tasks Summary --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="section-title">Tasks</div>
                    @forelse($client->tasks as $task)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span>{{ $task->title }}</span>
                            <span class="badge bg-{{ $task->status == 'Completed' ? 'success' : ($task->status == 'In Progress' ? 'warning' : 'secondary') }}">
                                {{ $task->status }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No tasks found.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Notices Summary --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="section-title">Notices</div>
                    @forelse($client->notices as $notice)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span>{{ $notice->subject ?? 'No Subject' }}</span>
                            <span class="badge bg-{{ $notice->status == 'Closed' ? 'success' : ($notice->status == 'In Progress' ? 'warning' : 'danger') }}">
                                {{ $notice->status }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No notices found.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>