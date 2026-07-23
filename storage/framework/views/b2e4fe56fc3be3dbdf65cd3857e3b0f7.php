<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expense Report - ReturnPro</title>
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
        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Expense Report</h5>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Print</button>
            <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3 no-print">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category_id') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->category_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3"><input type="date" name="from_date" class="form-control" value="<?php echo e(request('from_date')); ?>"></div>
                <div class="col-md-3"><input type="date" name="to_date" class="form-control" value="<?php echo e(request('to_date')); ?>"></div>
                <div class="col-md-3"><button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Filter</button></div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 text-center">
                <div class="text-muted">Total Expenses</div>
                <h4 class="text-danger">Rs. <?php echo e(number_format($totalAmount, 0)); ?></h4>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-3">
                <strong class="d-block mb-2">Category Wise Breakdown</strong>
                <?php $__currentLoopData = $categoryWise; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex justify-content-between border-bottom py-1">
                    <span><?php echo e($category ?? 'Uncategorized'); ?></span>
                    <strong>Rs. <?php echo e(number_format($amount, 0)); ?></strong>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr><th>Date</th><th>Category</th><th>Description</th><th class="text-end">Amount</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($expense->expense_date); ?></td>
                        <td><span class="badge bg-info"><?php echo e($expense->category->category_name ?? '-'); ?></span></td>
                        <td><?php echo e($expense->description ?? '-'); ?></td>
                        <td class="text-end"><strong>Rs. <?php echo e(number_format($expense->amount, 0)); ?></strong></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">No expenses found.</td></tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-dark">
                    <tr><td colspan="3"><strong>Total</strong></td><td class="text-end"><strong>Rs. <?php echo e(number_format($totalAmount, 0)); ?></strong></td></tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH C:\xampp\htdocs\returnpro\resources\views/reports/expenses.blade.php ENDPATH**/ ?>