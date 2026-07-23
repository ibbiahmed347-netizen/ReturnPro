<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Outstanding Report - ReturnPro</title>
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
        @media print { .sidebar, .topbar, .no-print { display: none !important; } .main-content { margin-left: 0 !important; } body { background: white; } }
    </style>
</head>
<body>
<div class="sidebar no-print">
    <div class="brand"><i class="fas fa-file-invoice me-2"></i>ReturnPro</div>
    <nav class="mt-2">
        <a href="<?php echo e(route('dashboard')); ?>" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="<?php echo e(route('clients.index')); ?>" class="nav-link"><i class="fas fa-users"></i> Clients</a>
        <a href="<?php echo e(route('income-tax.index')); ?>" class="nav-link"><i class="fas fa-file-alt"></i> Income Tax</a>
        <a href="<?php echo e(route('sales-tax.index')); ?>" class="nav-link"><i class="fas fa-receipt"></i> Sales Tax</a>
        <a href="<?php echo e(route('vouchers.index')); ?>" class="nav-link"><i class="fas fa-file-invoice-dollar"></i> Vouchers</a>
        <a href="#" class="nav-link"><i class="fas fa-money-bill-wave"></i> Collections</a>
        <a href="<?php echo e(route('expenses.index')); ?>" class="nav-link"><i class="fas fa-chart-bar"></i> Expenses</a>
        <a href="#" class="nav-link"><i class="fas fa-folder-open"></i> Documents</a>
        <a href="<?php echo e(route('notices.index')); ?>" class="nav-link"><i class="fas fa-bell"></i> Notices</a>
        <a href="<?php echo e(route('tasks.index')); ?>" class="nav-link"><i class="fas fa-tasks"></i> Tasks</a>
        <a href="<?php echo e(route('reports.index')); ?>" class="nav-link active"><i class="fas fa-chart-pie"></i> Reports</a>
        <a href="<?php echo e(route('users.index')); ?>" class="nav-link"><i class="fas fa-user-cog"></i> Users</a>
        <a href="<?php echo e(route('settings.index')); ?>" class="nav-link"><i class="fas fa-cog"></i> Settings</a>
    </nav>
</div>
<div class="main-content">
    <div class="topbar no-print">
        <h5 class="mb-0"><i class="fas fa-exclamation-circle me-2"></i>Outstanding Report</h5>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Print</button>
            <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3 no-print">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-4">
                    <select name="client_id" class="form-select">
                        <option value="">All Clients</option>
                        <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($client->id); ?>" <?php echo e(request('client_id') == $client->id ? 'selected' : ''); ?>><?php echo e($client->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Filter</button></div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4"><div class="card border-0 shadow-sm p-3 text-center"><div class="text-muted">Total Billed</div><h4 class="text-primary">Rs. <?php echo e(number_format($totalAmount, 0)); ?></h4></div></div>
        <div class="col-md-4"><div class="card border-0 shadow-sm p-3 text-center"><div class="text-muted">Total Paid</div><h4 class="text-success">Rs. <?php echo e(number_format($totalPaid, 0)); ?></h4></div></div>
        <div class="col-md-4"><div class="card border-0 shadow-sm p-3 text-center"><div class="text-muted">Total Outstanding</div><h4 class="text-danger">Rs. <?php echo e(number_format($totalDue, 0)); ?></h4></div></div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr><th>Voucher No</th><th>Client</th><th>Date</th><th>Tax Year</th><th class="text-end">Net Amount</th><th class="text-end">Paid</th><th class="text-end">Balance</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $vouchers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voucher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $paid = $voucher->receipts->sum('amount'); $balance = $voucher->net_amount - $paid; ?>
                    <tr>
                        <td><?php echo e($voucher->voucher_no); ?></td>
                        <td><?php echo e($voucher->client->name); ?><br><small class="text-muted"><?php echo e($voucher->client->case_number ?? ''); ?></small></td>
                        <td><?php echo e($voucher->voucher_date); ?></td>
                        <td><?php echo e($voucher->taxYear->tax_year ?? '-'); ?></td>
                        <td class="text-end">Rs. <?php echo e(number_format($voucher->net_amount, 0)); ?></td>
                        <td class="text-end text-success">Rs. <?php echo e(number_format($paid, 0)); ?></td>
                        <td class="text-end text-danger"><strong>Rs. <?php echo e(number_format($balance, 0)); ?></strong></td>
                        <td><span class="badge bg-<?php echo e($voucher->status == 'Partial' ? 'warning' : 'danger'); ?>"><?php echo e($voucher->status); ?></span></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">No outstanding vouchers!</td></tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-dark">
                    <tr><td colspan="4"><strong>Total</strong></td><td class="text-end"><strong>Rs. <?php echo e(number_format($totalAmount, 0)); ?></strong></td><td class="text-end"><strong>Rs. <?php echo e(number_format($totalPaid, 0)); ?></strong></td><td class="text-end"><strong>Rs. <?php echo e(number_format($totalDue, 0)); ?></strong></td><td></td></tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH C:\xampp\htdocs\returnpro\resources\views/reports/outstanding.blade.php ENDPATH**/ ?>