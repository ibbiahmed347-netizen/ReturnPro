<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Voucher - ReturnPro</title>
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
        <a href="#" class="nav-link"><i class="fas fa-file-alt"></i> Income Tax</a>
        <a href="#" class="nav-link"><i class="fas fa-receipt"></i> Sales Tax</a>
        <a href="{{ route('vouchers.index') }}" class="nav-link active"><i class="fas fa-file-invoice-dollar"></i> Vouchers</a>
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
        <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Create Voucher</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('vouchers.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('vouchers.store') }}">
        @csrf

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="section-title">Voucher Information</div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Voucher No <span class="text-danger">*</span></label>
                        <input type="text" name="voucher_no" class="form-control" value="{{ old('voucher_no', $voucherNo) }}" required>
                    </div>
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
                    <div class="col-md-4">
                        <label class="form-label">Tax Year</label>
                        <select name="tax_year_id" class="form-select">
                            <option value="">-- Select Tax Year --</option>
                            @foreach($taxYears as $year)
                                <option value="{{ $year->id }}" {{ old('tax_year_id') == $year->id ? 'selected' : '' }}>
                                    {{ $year->tax_year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Voucher Date <span class="text-danger">*</span></label>
                        <input type="date" name="voucher_date" class="form-control" value="{{ old('voucher_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Notes</label>
                        <input type="text" name="notes" class="form-control" value="{{ old('notes') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Services / Items --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="section-title">Services</div>
                <div id="items-container">
                    <div class="row g-2 mb-2 item-row">
                        <div class="col-md-8">
                            <input type="text" name="items[0][service_name]" class="form-control" placeholder="Service name (e.g. Income Tax Return 2025)" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="items[0][amount]" class="form-control item-amount" placeholder="Amount" min="0" step="0.01" required oninput="calculateTotal()">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-danger w-100" onclick="removeItem(this)"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addItem()">
                    <i class="fas fa-plus"></i> Add Service
                </button>
            </div>
        </div>

        {{-- Totals --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="section-title">Amount Summary</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Total Amount</label>
                        <input type="number" name="amount" id="total-amount" class="form-control" readonly value="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Discount (Rs.)</label>
                        <input type="number" name="discount" id="discount" class="form-control" value="0" min="0" step="0.01" oninput="calculateTotal()">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Net Amount</label>
                        <input type="number" id="net-amount" class="form-control bg-light fw-bold" readonly value="0">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Save Voucher</button>
            <a href="{{ route('vouchers.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let itemIndex = 1;

    function addItem() {
        const container = document.getElementById('items-container');
        const div = document.createElement('div');
        div.className = 'row g-2 mb-2 item-row';
        div.innerHTML = `
            <div class="col-md-8">
                <input type="text" name="items[${itemIndex}][service_name]" class="form-control" placeholder="Service name" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="items[${itemIndex}][amount]" class="form-control item-amount" placeholder="Amount" min="0" step="0.01" required oninput="calculateTotal()">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger w-100" onclick="removeItem(this)"><i class="fas fa-times"></i></button>
            </div>`;
        container.appendChild(div);
        itemIndex++;
    }

    function removeItem(btn) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) {
            btn.closest('.item-row').remove();
            calculateTotal();
        }
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.item-amount').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        document.getElementById('total-amount').value = total.toFixed(2);
        document.getElementById('net-amount').value = (total - discount).toFixed(2);
    }
</script>
</body>
</html>