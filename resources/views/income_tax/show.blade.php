<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Tax Return - ReturnPro</title>
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
        @media print {
            .sidebar, .topbar, .no-print { display: none !important; }
            .main-content { margin-left: 0 !important; padding: 0 !important; }
            body { background: white; }
        }
    </style>
</head>
<body>
<div class="sidebar no-print">
    <div class="brand"><i class="fas fa-file-invoice me-2"></i>ReturnPro</div>
    <nav class="mt-2">
        <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('clients.index') }}" class="nav-link"><i class="fas fa-users"></i> Clients</a>
        <a href="{{ route('income-tax.index') }}" class="nav-link active"><i class="fas fa-file-alt"></i> Income Tax</a>
        <a href="#" class="nav-link"><i class="fas fa-receipt"></i> Sales Tax</a>
        <a href="{{ route('vouchers.index') }}" class="nav-link"><i class="fas fa-file-invoice-dollar"></i> Vouchers</a>
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
            <i class="fas fa-file-alt me-2"></i>
            {{ $incomeTax->client->name }} — {{ $incomeTax->taxYear->tax_year }}
            <span class="badge bg-{{ $incomeTax->return_status == 'Published' ? 'success' : 'warning' }} ms-2">{{ $incomeTax->return_status }}</span>
            <span class="badge bg-secondary ms-1">{{ $incomeTax->return_type }}</span>
        </h5>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Print</button>
            @if($incomeTax->return_status == 'Draft')
            <a href="{{ route('income-tax.edit', $incomeTax) }}" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i> Edit</a>
            <form method="POST" action="{{ route('income-tax.publish', $incomeTax) }}" class="d-inline" onsubmit="return confirm('Publish this return? It cannot be edited after publishing.')">
                @csrf
                <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Publish</button>
            </form>
            @endif
            <a href="{{ route('income-tax.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>

    @if(session('success'))<div class="alert alert-success alert-dismissible fade show no-print">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
    @if(session('error'))<div class="alert alert-danger alert-dismissible fade show no-print">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif

    <div class="row g-3">

        {{-- Client Info --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="section-title">Client Information</div>
                    <div class="row g-2">
                        <div class="col-6"><div class="info-label">Name</div><div class="info-value">{{ $incomeTax->client->name }}</div></div>
                        <div class="col-6"><div class="info-label">Case No</div><div class="info-value">{{ $incomeTax->client->case_number ?? '-' }}</div></div>
                        <div class="col-6"><div class="info-label">CNIC</div><div class="info-value">{{ $incomeTax->client->cnic ?? '-' }}</div></div>
                        <div class="col-6"><div class="info-label">NTN</div><div class="info-value">{{ $incomeTax->client->ntn ?? '-' }}</div></div>
                        <div class="col-6"><div class="info-label">Mobile</div><div class="info-value">{{ $incomeTax->client->mobile ?? '-' }}</div></div>
                        <div class="col-6"><div class="info-label">Tax Year</div><div class="info-value">{{ $incomeTax->taxYear->tax_year }}</div></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Income Details --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="section-title">Income Details</div>
                    @if($incomeTax->incomeDetails)
                    <table class="table table-sm">
                        <tr><td>Salary Income</td><td class="text-end">Rs. {{ number_format($incomeTax->incomeDetails->salary_income, 0) }}</td></tr>
                        <tr><td>Business Income</td><td class="text-end">Rs. {{ number_format($incomeTax->incomeDetails->business_income, 0) }}</td></tr>
                        <tr><td>Property Income</td><td class="text-end">Rs. {{ number_format($incomeTax->incomeDetails->property_income, 0) }}</td></tr>
                        <tr><td>Capital Gain</td><td class="text-end">Rs. {{ number_format($incomeTax->incomeDetails->capital_gain, 0) }}</td></tr>
                        <tr><td>Other Income</td><td class="text-end">Rs. {{ number_format($incomeTax->incomeDetails->other_income, 0) }}</td></tr>
                        <tr class="table-dark"><td><strong>Total Income</strong></td><td class="text-end"><strong>Rs. {{ number_format($incomeTax->total_income, 0) }}</strong></td></tr>
                    </table>
                    @else
                    <p class="text-muted">No income details found.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tax Credits --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="section-title mb-0">Tax Credits</div>
                        @if($incomeTax->return_status == 'Draft')
                        <button class="btn btn-sm btn-outline-primary no-print" onclick="$('#creditForm').toggle()"><i class="fas fa-plus"></i> Add</button>
                        @endif
                    </div>
                    <div id="creditForm" style="display:none" class="mb-3 no-print">
                        <form method="POST" action="{{ route('income-tax.update', $incomeTax) }}" id="creditFormEl">
                            @csrf @method('PUT')
                            <input type="hidden" name="add_credit" value="1">
                            <div class="row g-2">
                                <div class="col-7"><input type="text" name="credit_description" class="form-control form-control-sm" placeholder="Description" required></div>
                                <div class="col-3"><input type="number" name="credit_amount" class="form-control form-control-sm" placeholder="Amount" min="0" required></div>
                                <div class="col-2"><button type="submit" class="btn btn-success btn-sm w-100">Add</button></div>
                            </div>
                        </form>
                    </div>
                    @forelse($incomeTax->taxCredits as $credit)
                    <div class="d-flex justify-content-between border-bottom py-1">
                        <span>{{ $credit->description }}</span>
                        <strong>Rs. {{ number_format($credit->amount, 0) }}</strong>
                    </div>
                    @empty
                    <p class="text-muted mb-0">No tax credits added.</p>
                    @endforelse
                    @if($incomeTax->taxCredits->count() > 0)
                    <div class="d-flex justify-content-between mt-2">
                        <strong>Total</strong>
                        <strong>Rs. {{ number_format($incomeTax->taxCredits->sum('amount'), 0) }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tax Deducted --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="section-title mb-0">Tax Deducted at Source</div>
                        @if($incomeTax->return_status == 'Draft')
                        <button class="btn btn-sm btn-outline-primary no-print" onclick="$('#deductForm').toggle()"><i class="fas fa-plus"></i> Add</button>
                        @endif
                    </div>
                    <div id="deductForm" style="display:none" class="mb-3 no-print">
                        <form method="POST" action="{{ route('income-tax.update', $incomeTax) }}">
                            @csrf @method('PUT')
                            <input type="hidden" name="add_deducted" value="1">
                            <div class="row g-2">
                                <div class="col-7"><input type="text" name="deducted_source" class="form-control form-control-sm" placeholder="Source name" required></div>
                                <div class="col-3"><input type="number" name="deducted_amount" class="form-control form-control-sm" placeholder="Amount" min="0" required></div>
                                <div class="col-2"><button type="submit" class="btn btn-success btn-sm w-100">Add</button></div>
                            </div>
                        </form>
                    </div>
                    @forelse($incomeTax->taxDeducted as $deducted)
                    <div class="d-flex justify-content-between border-bottom py-1">
                        <span>{{ $deducted->source_name }}</span>
                        <strong>Rs. {{ number_format($deducted->amount, 0) }}</strong>
                    </div>
                    @empty
                    <p class="text-muted mb-0">No tax deducted entries.</p>
                    @endforelse
                    @if($incomeTax->taxDeducted->count() > 0)
                    <div class="d-flex justify-content-between mt-2">
                        <strong>Total</strong>
                        <strong>Rs. {{ number_format($incomeTax->taxDeducted->sum('amount'), 0) }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Notes --}}
        @if($incomeTax->notes)
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="section-title">Notes</div>
                    <p class="mb-0">{{ $incomeTax->notes }}</p>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</body>
</html>