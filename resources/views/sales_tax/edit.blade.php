<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sales Tax Return - ReturnPro</title>
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
        <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit — {{ $salesTax->client->name }} ({{ $salesTax->month_name }} {{ $salesTax->year }})</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('sales-tax.show', $salesTax) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>

    <form method="POST" action="{{ route('sales-tax.update', $salesTax) }}">
        @csrf @method('PUT')

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="section-title">Return Info</div>
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Client</label><input type="text" class="form-control" value="{{ $salesTax->client->name }}" readonly></div>
                    <div class="col-md-3"><label class="form-label">Period</label><input type="text" class="form-control" value="{{ $salesTax->month_name }} {{ $salesTax->year }}" readonly></div>
                    <div class="col-md-5"><label class="form-label">Notes</label><input type="text" name="notes" class="form-control" value="{{ old('notes', $salesTax->notes) }}"></div>
                </div>
            </div>
        </div>

        {{-- Sales --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="section-title">Sales (Output Tax)</div>
                <div id="sales-container">
                    @forelse($salesTax->sales as $i => $sale)
                    <div class="row g-2 mb-2 sale-row">
                        <div class="col-md-3"><input type="text" name="sales[{{ $i }}][invoice_no]" class="form-control form-control-sm" placeholder="Invoice No" value="{{ $sale->invoice_no }}"></div>
                        <div class="col-md-2"><input type="date" name="sales[{{ $i }}][invoice_date]" class="form-control form-control-sm" value="{{ $sale->invoice_date }}"></div>
                        <div class="col-md-3"><input type="number" name="sales[{{ $i }}][amount]" class="form-control form-control-sm sale-amount" placeholder="Amount" value="{{ $sale->amount }}" oninput="calcSummary()"></div>
                        <div class="col-md-3"><input type="number" name="sales[{{ $i }}][sales_tax]" class="form-control form-control-sm sale-tax" placeholder="Sales Tax" value="{{ $sale->sales_tax }}" oninput="calcSummary()"></div>
                        <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeRow(this)"><i class="fas fa-times"></i></button></div>
                    </div>
                    @empty
                    <div class="row g-2 mb-2 sale-row">
                        <div class="col-md-3"><input type="text" name="sales[0][invoice_no]" class="form-control form-control-sm" placeholder="Invoice No"></div>
                        <div class="col-md-2"><input type="date" name="sales[0][invoice_date]" class="form-control form-control-sm"></div>
                        <div class="col-md-3"><input type="number" name="sales[0][amount]" class="form-control form-control-sm sale-amount" placeholder="Amount" oninput="calcSummary()"></div>
                        <div class="col-md-3"><input type="number" name="sales[0][sales_tax]" class="form-control form-control-sm sale-tax" placeholder="Sales Tax" oninput="calcSummary()"></div>
                        <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeRow(this)"><i class="fas fa-times"></i></button></div>
                    </div>
                    @endforelse
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm mt-1" onclick="addSale()"><i class="fas fa-plus"></i> Add Row</button>
            </div>
        </div>

        {{-- Purchases --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="section-title">Purchases (Input Tax)</div>
                <div id="purchases-container">
                    @forelse($salesTax->purchases as $i => $purchase)
                    <div class="row g-2 mb-2 purchase-row">
                        <div class="col-md-3"><input type="text" name="purchases[{{ $i }}][invoice_no]" class="form-control form-control-sm" placeholder="Invoice No" value="{{ $purchase->invoice_no }}"></div>
                        <div class="col-md-2"><input type="date" name="purchases[{{ $i }}][invoice_date]" class="form-control form-control-sm" value="{{ $purchase->invoice_date }}"></div>
                        <div class="col-md-3"><input type="number" name="purchases[{{ $i }}][amount]" class="form-control form-control-sm purchase-amount" placeholder="Amount" value="{{ $purchase->amount }}" oninput="calcSummary()"></div>
                        <div class="col-md-3"><input type="number" name="purchases[{{ $i }}][input_tax]" class="form-control form-control-sm input-tax" placeholder="Input Tax" value="{{ $purchase->input_tax }}" oninput="calcSummary()"></div>
                        <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeRow(this)"><i class="fas fa-times"></i></button></div>
                    </div>
                    @empty
                    <div class="row g-2 mb-2 purchase-row">
                        <div class="col-md-3"><input type="text" name="purchases[0][invoice_no]" class="form-control form-control-sm" placeholder="Invoice No"></div>
                        <div class="col-md-2"><input type="date" name="purchases[0][invoice_date]" class="form-control form-control-sm"></div>
                        <div class="col-md-3"><input type="number" name="purchases[0][amount]" class="form-control form-control-sm purchase-amount" placeholder="Amount" oninput="calcSummary()"></div>
                        <div class="col-md-3"><input type="number" name="purchases[0][input_tax]" class="form-control form-control-sm input-tax" placeholder="Input Tax" oninput="calcSummary()"></div>
                        <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeRow(this)"><i class="fas fa-times"></i></button></div>
                    </div>
                    @endforelse
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm mt-1" onclick="addPurchase()"><i class="fas fa-plus"></i> Add Row</button>
            </div>
        </div>

        {{-- Summary --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    <strong>Tax Payable: Rs. <span id="tax-payable">{{ number_format($salesTax->tax_payable, 2) }}</span></strong>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Update Return</button>
            <a href="{{ route('sales-tax.show', $salesTax) }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let saleIdx = {{ $salesTax->sales->count() }}, purchaseIdx = {{ $salesTax->purchases->count() }};

function addSale() {
    const c = document.getElementById('sales-container');
    const d = document.createElement('div');
    d.className = 'row g-2 mb-2 sale-row';
    d.innerHTML = `<div class="col-md-3"><input type="text" name="sales[${saleIdx}][invoice_no]" class="form-control form-control-sm" placeholder="Invoice No"></div><div class="col-md-2"><input type="date" name="sales[${saleIdx}][invoice_date]" class="form-control form-control-sm"></div><div class="col-md-3"><input type="number" name="sales[${saleIdx}][amount]" class="form-control form-control-sm sale-amount" placeholder="Amount" oninput="calcSummary()"></div><div class="col-md-3"><input type="number" name="sales[${saleIdx}][sales_tax]" class="form-control form-control-sm sale-tax" placeholder="Sales Tax" oninput="calcSummary()"></div><div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeRow(this)"><i class="fas fa-times"></i></button></div>`;
    c.appendChild(d); saleIdx++;
}

function addPurchase() {
    const c = document.getElementById('purchases-container');
    const d = document.createElement('div');
    d.className = 'row g-2 mb-2 purchase-row';
    d.innerHTML = `<div class="col-md-3"><input type="text" name="purchases[${purchaseIdx}][invoice_no]" class="form-control form-control-sm" placeholder="Invoice No"></div><div class="col-md-2"><input type="date" name="purchases[${purchaseIdx}][invoice_date]" class="form-control form-control-sm"></div><div class="col-md-3"><input type="number" name="purchases[${purchaseIdx}][amount]" class="form-control form-control-sm purchase-amount" placeholder="Amount" oninput="calcSummary()"></div><div class="col-md-3"><input type="number" name="purchases[${purchaseIdx}][input_tax]" class="form-control form-control-sm input-tax" placeholder="Input Tax" oninput="calcSummary()"></div><div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeRow(this)"><i class="fas fa-times"></i></button></div>`;
    c.appendChild(d); purchaseIdx++;
}

function removeRow(btn) { btn.closest('.row').remove(); calcSummary(); }

function calcSummary() {
    let to=0, ti=0;
    document.querySelectorAll('.sale-tax').forEach(i => to += parseFloat(i.value)||0);
    document.querySelectorAll('.input-tax').forEach(i => ti += parseFloat(i.value)||0);
    document.getElementById('tax-payable').textContent = Math.max(0, to-ti).toFixed(2);
}

calcSummary();
</script>
</body>
</html>