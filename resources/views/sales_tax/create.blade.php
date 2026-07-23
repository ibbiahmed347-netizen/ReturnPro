<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Sales Tax Return - ReturnPro</title>
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
        <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add Sales Tax Return</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('sales-tax.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>

    @if($errors->any())<div class="alert alert-danger">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif

    <form method="POST" action="{{ route('sales-tax.store') }}">
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
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }} {{ $client->case_number ? '(Case: '.$client->case_number.')' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Month <span class="text-danger">*</span></label>
                        <select name="month" class="form-select" required>
                            <option value="">-- Select Month --</option>
                            @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ old('month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Year <span class="text-danger">*</span></label>
                        <select name="year" class="form-select" required>
                            @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ old('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Notes</label>
                        <input type="text" name="notes" class="form-control" value="{{ old('notes') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Sales --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="section-title">Sales (Output Tax)</div>
                <div id="sales-container">
                    <div class="row g-2 mb-2 sale-row">
                        <div class="col-md-3"><input type="text" name="sales[0][invoice_no]" class="form-control form-control-sm" placeholder="Invoice No"></div>
                        <div class="col-md-2"><input type="date" name="sales[0][invoice_date]" class="form-control form-control-sm"></div>
                        <div class="col-md-3"><input type="number" name="sales[0][amount]" class="form-control form-control-sm sale-amount" placeholder="Sale Amount" min="0" step="0.01" oninput="calcSummary()"></div>
                        <div class="col-md-3"><input type="number" name="sales[0][sales_tax]" class="form-control form-control-sm sale-tax" placeholder="Sales Tax" min="0" step="0.01" oninput="calcSummary()"></div>
                        <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeRow(this)"><i class="fas fa-times"></i></button></div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm mt-1" onclick="addSale()"><i class="fas fa-plus"></i> Add Row</button>
            </div>
        </div>

        {{-- Purchases --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="section-title">Purchases (Input Tax)</div>
                <div id="purchases-container">
                    <div class="row g-2 mb-2 purchase-row">
                        <div class="col-md-3"><input type="text" name="purchases[0][invoice_no]" class="form-control form-control-sm" placeholder="Invoice No"></div>
                        <div class="col-md-2"><input type="date" name="purchases[0][invoice_date]" class="form-control form-control-sm"></div>
                        <div class="col-md-3"><input type="number" name="purchases[0][amount]" class="form-control form-control-sm purchase-amount" placeholder="Purchase Amount" min="0" step="0.01" oninput="calcSummary()"></div>
                        <div class="col-md-3"><input type="number" name="purchases[0][input_tax]" class="form-control form-control-sm input-tax" placeholder="Input Tax" min="0" step="0.01" oninput="calcSummary()"></div>
                        <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeRow(this)"><i class="fas fa-times"></i></button></div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm mt-1" onclick="addPurchase()"><i class="fas fa-plus"></i> Add Row</button>
            </div>
        </div>

        {{-- Summary --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="section-title">Tax Summary</div>
                <div class="row g-3">
                    <div class="col-md-3"><label class="form-label">Total Sales</label><input type="text" id="total-sales" class="form-control bg-light" readonly value="0"></div>
                    <div class="col-md-3"><label class="form-label">Output Tax</label><input type="text" id="total-output" class="form-control bg-light" readonly value="0"></div>
                    <div class="col-md-3"><label class="form-label">Total Purchases</label><input type="text" id="total-purchases" class="form-control bg-light" readonly value="0"></div>
                    <div class="col-md-3"><label class="form-label">Input Tax</label><input type="text" id="total-input" class="form-control bg-light" readonly value="0"></div>
                    <div class="col-md-12">
                        <div class="alert alert-warning mb-0">
                            <strong>Tax Payable: Rs. <span id="tax-payable">0</span></strong>
                            (Output Tax - Input Tax)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Save Return</button>
            <a href="{{ route('sales-tax.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let saleIdx = 1, purchaseIdx = 1;

function addSale() {
    const c = document.getElementById('sales-container');
    const d = document.createElement('div');
    d.className = 'row g-2 mb-2 sale-row';
    d.innerHTML = `
        <div class="col-md-3"><input type="text" name="sales[${saleIdx}][invoice_no]" class="form-control form-control-sm" placeholder="Invoice No"></div>
        <div class="col-md-2"><input type="date" name="sales[${saleIdx}][invoice_date]" class="form-control form-control-sm"></div>
        <div class="col-md-3"><input type="number" name="sales[${saleIdx}][amount]" class="form-control form-control-sm sale-amount" placeholder="Sale Amount" min="0" step="0.01" oninput="calcSummary()"></div>
        <div class="col-md-3"><input type="number" name="sales[${saleIdx}][sales_tax]" class="form-control form-control-sm sale-tax" placeholder="Sales Tax" min="0" step="0.01" oninput="calcSummary()"></div>
        <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeRow(this)"><i class="fas fa-times"></i></button></div>`;
    c.appendChild(d); saleIdx++;
}

function addPurchase() {
    const c = document.getElementById('purchases-container');
    const d = document.createElement('div');
    d.className = 'row g-2 mb-2 purchase-row';
    d.innerHTML = `
        <div class="col-md-3"><input type="text" name="purchases[${purchaseIdx}][invoice_no]" class="form-control form-control-sm" placeholder="Invoice No"></div>
        <div class="col-md-2"><input type="date" name="purchases[${purchaseIdx}][invoice_date]" class="form-control form-control-sm"></div>
        <div class="col-md-3"><input type="number" name="purchases[${purchaseIdx}][amount]" class="form-control form-control-sm purchase-amount" placeholder="Purchase Amount" min="0" step="0.01" oninput="calcSummary()"></div>
        <div class="col-md-3"><input type="number" name="purchases[${purchaseIdx}][input_tax]" class="form-control form-control-sm input-tax" placeholder="Input Tax" min="0" step="0.01" oninput="calcSummary()"></div>
        <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeRow(this)"><i class="fas fa-times"></i></button></div>`;
    c.appendChild(d); purchaseIdx++;
}

function removeRow(btn) { btn.closest('.row').remove(); calcSummary(); }

function calcSummary() {
    let ts=0, to=0, tp=0, ti=0;
    document.querySelectorAll('.sale-amount').forEach(i => ts += parseFloat(i.value)||0);
    document.querySelectorAll('.sale-tax').forEach(i => to += parseFloat(i.value)||0);
    document.querySelectorAll('.purchase-amount').forEach(i => tp += parseFloat(i.value)||0);
    document.querySelectorAll('.input-tax').forEach(i => ti += parseFloat(i.value)||0);
    document.getElementById('total-sales').value = ts.toFixed(2);
    document.getElementById('total-output').value = to.toFixed(2);
    document.getElementById('total-purchases').value = tp.toFixed(2);
    document.getElementById('total-input').value = ti.toFixed(2);
    document.getElementById('tax-payable').textContent = Math.max(0, to-ti).toFixed(2);
}
</script>
</body>
</html>