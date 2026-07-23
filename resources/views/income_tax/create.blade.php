<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Return - ReturnPro</title>
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
    </style>
</head>
<body>
<div class="sidebar">
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
    <div class="topbar">
        <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add Income Tax Return</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('income-tax.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>

    @if($errors->any())<div class="alert alert-danger">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif

    <form method="POST" action="{{ route('income-tax.store') }}">
        @csrf

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="section-title">Return Information</div>
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Client <span class="text-danger">*</span></label>
                        <select name="client_id" class="form-select" required>
                            <option value="">-- Select Client --</option>
                            @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $selectedClient?->id) == $client->id ? 'selected' : '' }}>
                                {{ $client->name }} {{ $client->case_number ? '(Case: '.$client->case_number.')' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tax Year <span class="text-danger">*</span></label>
                        <select name="tax_year_id" class="form-select" required>
                            <option value="">-- Select Year --</option>
                            @foreach($taxYears as $year)
                            <option value="{{ $year->id }}" {{ old('tax_year_id') == $year->id ? 'selected' : '' }}>{{ $year->tax_year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Return Type</label>
                        <select name="return_type" class="form-select">
                            <option value="Original">Original</option>
                            <option value="Revised">Revised</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="section-title">Income Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Salary Income (Rs.)</label>
                        <input type="number" name="salary_income" class="form-control income-field" value="{{ old('salary_income', 0) }}" min="0" step="0.01" oninput="calcTotal()">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Business Income (Rs.)</label>
                        <input type="number" name="business_income" class="form-control income-field" value="{{ old('business_income', 0) }}" min="0" step="0.01" oninput="calcTotal()">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Property Income (Rs.)</label>
                        <input type="number" name="property_income" class="form-control income-field" value="{{ old('property_income', 0) }}" min="0" step="0.01" oninput="calcTotal()">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Capital Gain (Rs.)</label>
                        <input type="number" name="capital_gain" class="form-control income-field" value="{{ old('capital_gain', 0) }}" min="0" step="0.01" oninput="calcTotal()">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Other Income (Rs.)</label>
                        <input type="number" name="other_income" class="form-control income-field" value="{{ old('other_income', 0) }}" min="0" step="0.01" oninput="calcTotal()">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total Income (Rs.)</label>
                        <input type="number" id="total_income" class="form-control bg-light fw-bold" readonly value="0">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Save Return</button>
            <a href="{{ route('income-tax.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function calcTotal() {
        let total = 0;
        document.querySelectorAll('.income-field').forEach(i => total += parseFloat(i.value) || 0);
        document.getElementById('total_income').value = total.toFixed(2);
    }
    calcTotal();
</script>
</body>
</html>