<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['category', 'createdBy']);

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('from_date')) {
            $query->where('expense_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('expense_date', '<=', $request->to_date);
        }

        $expenses   = $query->orderBy('expense_date', 'desc')->paginate(20);
        $categories = ExpenseCategory::orderBy('category_name')->get();
        $totalAmount = $query->sum('amount');

        return view('expenses.index', compact('expenses', 'categories', 'totalAmount'));
    }

    public function create()
    {
        $categories = ExpenseCategory::orderBy('category_name')->get();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'amount'       => 'required|numeric|min:1',
            'category_id'  => 'required|exists:expense_categories,id',
        ]);

        $expense = Expense::create([
            'category_id'  => $request->category_id,
            'expense_date' => $request->expense_date,
            'amount'       => $request->amount,
            'description'  => $request->description,
            'created_by'   => auth()->id(),
        ]);

        UserActivityLog::log('Created expense', 'expenses', $expense->id);

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully!');
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::orderBy('category_name')->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'amount'       => 'required|numeric|min:1',
            'category_id'  => 'required|exists:expense_categories,id',
        ]);

        $expense->update([
            'category_id'  => $request->category_id,
            'expense_date' => $request->expense_date,
            'amount'       => $request->amount,
            'description'  => $request->description,
        ]);

        UserActivityLog::log('Updated expense', 'expenses', $expense->id);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        UserActivityLog::log('Deleted expense', 'expenses', $expense->id);
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully!');
    }
}