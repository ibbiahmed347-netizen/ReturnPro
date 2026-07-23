<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. ROLES ─────────────────────────────────────────────────────
        $roles = [
            ['role_name' => 'Super Admin',         'description' => 'Complete system access'],
            ['role_name' => 'Tax Manager',          'description' => 'Client & return management'],
            ['role_name' => 'Data Entry Operator',  'description' => 'Client and bills entry'],
            ['role_name' => 'Accountant',           'description' => 'Vouchers, receipts & expenses'],
            ['role_name' => 'Viewer',               'description' => 'View-only access'],
        ];
        DB::table('roles')->insert(array_map(fn($r) => array_merge($r, [
            'created_at' => now(), 'updated_at' => now(),
        ]), $roles));

        // ─── 2. PERMISSIONS ───────────────────────────────────────────────
        $permissions = [
            ['permission_name' => 'dashboard.view',            'module' => 'dashboard'],
            ['permission_name' => 'clients.view',              'module' => 'clients'],
            ['permission_name' => 'clients.create',            'module' => 'clients'],
            ['permission_name' => 'clients.edit',              'module' => 'clients'],
            ['permission_name' => 'clients.delete',            'module' => 'clients'],
            ['permission_name' => 'clients.archive',           'module' => 'clients'],
            ['permission_name' => 'income_tax.view',           'module' => 'income_tax'],
            ['permission_name' => 'income_tax.create',         'module' => 'income_tax'],
            ['permission_name' => 'income_tax.edit',           'module' => 'income_tax'],
            ['permission_name' => 'income_tax.publish',        'module' => 'income_tax'],
            ['permission_name' => 'income_tax.delete',         'module' => 'income_tax'],
            ['permission_name' => 'sales_tax.view',            'module' => 'sales_tax'],
            ['permission_name' => 'sales_tax.create',          'module' => 'sales_tax'],
            ['permission_name' => 'sales_tax.edit',            'module' => 'sales_tax'],
            ['permission_name' => 'sales_tax.publish',         'module' => 'sales_tax'],
            ['permission_name' => 'vouchers.view',             'module' => 'billing'],
            ['permission_name' => 'vouchers.create',           'module' => 'billing'],
            ['permission_name' => 'vouchers.edit',             'module' => 'billing'],
            ['permission_name' => 'vouchers.delete',           'module' => 'billing'],
            ['permission_name' => 'receipts.view',             'module' => 'billing'],
            ['permission_name' => 'receipts.create',           'module' => 'billing'],
            ['permission_name' => 'expenses.view',             'module' => 'expenses'],
            ['permission_name' => 'expenses.create',           'module' => 'expenses'],
            ['permission_name' => 'expenses.edit',             'module' => 'expenses'],
            ['permission_name' => 'expenses.delete',           'module' => 'expenses'],
            ['permission_name' => 'notices.view',              'module' => 'notices'],
            ['permission_name' => 'notices.create',            'module' => 'notices'],
            ['permission_name' => 'notices.edit',              'module' => 'notices'],
            ['permission_name' => 'tasks.view',                'module' => 'tasks'],
            ['permission_name' => 'tasks.create',              'module' => 'tasks'],
            ['permission_name' => 'tasks.edit',                'module' => 'tasks'],
            ['permission_name' => 'documents.view',            'module' => 'documents'],
            ['permission_name' => 'documents.upload',          'module' => 'documents'],
            ['permission_name' => 'documents.delete',          'module' => 'documents'],
            ['permission_name' => 'reports.view',              'module' => 'reports'],
            ['permission_name' => 'users.view',                'module' => 'settings'],
            ['permission_name' => 'users.create',              'module' => 'settings'],
            ['permission_name' => 'users.edit',                'module' => 'settings'],
            ['permission_name' => 'users.delete',              'module' => 'settings'],
            ['permission_name' => 'settings.manage',           'module' => 'settings'],
        ];
        DB::table('permissions')->insert(array_map(fn($p) => array_merge($p, [
            'created_at' => now(), 'updated_at' => now(),
        ]), $permissions));

        // ─── 3. ROLE PERMISSIONS ──────────────────────────────────────────
        $allPermIds = DB::table('permissions')->pluck('id');
        $superAdminRole = DB::table('roles')->where('role_name', 'Super Admin')->value('id');
        foreach ($allPermIds as $pid) {
            DB::table('role_permissions')->insert(['role_id' => $superAdminRole, 'permission_id' => $pid]);
        }

        $taxManagerRole = DB::table('roles')->where('role_name', 'Tax Manager')->value('id');
        $taxManagerPerms = ['dashboard.view','clients.view','clients.create','clients.edit','clients.archive',
            'income_tax.view','income_tax.create','income_tax.edit','income_tax.publish',
            'sales_tax.view','sales_tax.create','sales_tax.edit','sales_tax.publish',
            'vouchers.view','vouchers.create','vouchers.edit',
            'receipts.view','receipts.create',
            'notices.view','notices.create','notices.edit',
            'tasks.view','tasks.create','tasks.edit',
            'documents.view','documents.upload',
            'reports.view'];
        $this->assignPermissions($taxManagerRole, $taxManagerPerms);

        $deoRole = DB::table('roles')->where('role_name', 'Data Entry Operator')->value('id');
        $deoPerms = ['dashboard.view','clients.view','clients.create','clients.edit',
            'income_tax.view','income_tax.create','income_tax.edit',
            'sales_tax.view','sales_tax.create','sales_tax.edit',
            'vouchers.view','vouchers.create',
            'notices.view','tasks.view','tasks.create',
            'documents.view','documents.upload'];
        $this->assignPermissions($deoRole, $deoPerms);

        $accountantRole = DB::table('roles')->where('role_name', 'Accountant')->value('id');
        $accountantPerms = ['dashboard.view','clients.view',
            'vouchers.view','vouchers.create','vouchers.edit',
            'receipts.view','receipts.create',
            'expenses.view','expenses.create','expenses.edit',
            'reports.view'];
        $this->assignPermissions($accountantRole, $accountantPerms);

        $viewerRole = DB::table('roles')->where('role_name', 'Viewer')->value('id');
        $viewerPerms = ['dashboard.view','clients.view','income_tax.view','sales_tax.view',
            'vouchers.view','receipts.view','expenses.view','notices.view','tasks.view',
            'documents.view','reports.view'];
        $this->assignPermissions($viewerRole, $viewerPerms);

        // ─── 4. DEFAULT ADMIN USER ────────────────────────────────────────
        DB::table('users')->insert([
            'role_id'    => $superAdminRole,
            'name'       => 'Super Admin',
            'email'      => 'admin@returnpro.pk',
            'phone'      => '03000000000',
            'password'   => Hash::make('Admin@123'),
            'status'     => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ─── 5. TAX YEARS ─────────────────────────────────────────────────
        $taxYears = [
            ['tax_year' => '2022', 'start_date' => '2021-07-01', 'end_date' => '2022-06-30', 'status' => 'Closed'],
            ['tax_year' => '2023', 'start_date' => '2022-07-01', 'end_date' => '2023-06-30', 'status' => 'Closed'],
            ['tax_year' => '2024', 'start_date' => '2023-07-01', 'end_date' => '2024-06-30', 'status' => 'Closed'],
            ['tax_year' => '2025', 'start_date' => '2024-07-01', 'end_date' => '2025-06-30', 'status' => 'Open'],
            ['tax_year' => '2026', 'start_date' => '2025-07-01', 'end_date' => '2026-06-30', 'status' => 'Open'],
        ];
        DB::table('tax_years')->insert(array_map(fn($y) => array_merge($y, [
            'created_at' => now(), 'updated_at' => now(),
        ]), $taxYears));

        // ─── 6. MASTER DATA ───────────────────────────────────────────────
        $billTypes = ['Electricity', 'Gas', 'Water', 'Internet', 'Telephone', 'PTCL'];
        DB::table('utility_bill_types')->insert(array_map(fn($b) => [
            'bill_type' => $b, 'created_at' => now(), 'updated_at' => now(),
        ], $billTypes));

        $expenseCategories = ['Rent', 'Salaries', 'Utilities', 'Office Supplies', 'Internet', 'Marketing', 'Transport', 'Miscellaneous'];
        DB::table('expense_categories')->insert(array_map(fn($c) => [
            'category_name' => $c, 'created_at' => now(), 'updated_at' => now(),
        ], $expenseCategories));

        $docCategories = ['CNIC', 'NTN Certificate', 'STRN Certificate', 'Bank Statement', 'Salary Slip', 'Property Documents', 'Vehicle Documents', 'Tax Returns', 'Other'];
        DB::table('document_categories')->insert(array_map(fn($c) => [
            'category_name' => $c, 'created_at' => now(), 'updated_at' => now(),
        ], $docCategories));

        // ─── 7. DEFAULT SETTINGS ──────────────────────────────────────────
        DB::table('settings')->insert([
            'company_name' => 'ReturnPro Tax Consultants',
            'address'      => 'Karachi, Pakistan',
            'phone'        => '021-00000000',
            'email'        => 'info@returnpro.pk',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }

    private function assignPermissions(int $roleId, array $permNames): void
    {
        $permIds = DB::table('permissions')->whereIn('permission_name', $permNames)->pluck('id');
        foreach ($permIds as $pid) {
            DB::table('role_permissions')->insert(['role_id' => $roleId, 'permission_id' => $pid]);
        }
    }
}