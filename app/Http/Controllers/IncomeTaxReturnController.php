<?php

namespace App\Http\Controllers;

use App\Models\IncomeTaxReturn;
use App\Models\Client;
use App\Models\TaxYear;
use App\Models\ReturnTaxCredit;
use App\Models\ReturnTaxDeducted;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class IncomeTaxReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = IncomeTaxReturn::with(['client', 'taxYear']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('client', fn($q) => $q->where('name', 'like', "%$search%")
                ->orWhere('case_number', 'like', "%$search%")
                ->orWhere('ntn', 'like', "%$search%"));
        }

        if ($request->filled('tax_year_id')) {
            $query->where('tax_year_id', $request->tax_year_id);
        }

        if ($request->filled('status')) {
            $query->where('return_status', $request->status);
        }

        $returns  = $query->orderBy('created_at', 'desc')->paginate(20);
        $taxYears = TaxYear::orderBy('tax_year', 'desc')->get();

        return view('income_tax.index', compact('returns', 'taxYears'));
    }

    public function create(Request $request)
    {
        $clients  = Client::where('status', 'Active')->orderBy('name')->get();
        $taxYears = TaxYear::orderBy('tax_year', 'desc')->get();
        $selectedClient = $request->client_id ? Client::find($request->client_id) : null;

        return view('income_tax.create', compact('clients', 'taxYears', 'selectedClient'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id'   => 'required|exists:clients,id',
            'tax_year_id' => 'required|exists:tax_years,id',
            'return_type' => 'required|in:Original,Revised',
        ]);

        $exists = IncomeTaxReturn::where('client_id', $request->client_id)
            ->where('tax_year_id', $request->tax_year_id)
            ->where('return_type', $request->return_type)
            ->exists();

        if ($exists) {
            return back()->withErrors(['tax_year_id' => 'Return already exists for this client, year and type.'])->withInput();
        }

        $return = IncomeTaxReturn::create([
            'client_id'     => $request->client_id,
            'tax_year_id'   => $request->tax_year_id,
            'return_type'   => $request->return_type,
            'return_status' => 'Draft',
            'notes'         => $request->notes,
        ]);

        $return->incomeDetails()->create([
            'salary_income'   => $request->salary_income ?? 0,
            'business_income' => $request->business_income ?? 0,
            'property_income' => $request->property_income ?? 0,
            'capital_gain'    => $request->capital_gain ?? 0,
            'other_income'    => $request->other_income ?? 0,
        ]);

        UserActivityLog::log('Created income tax return', 'income_tax_returns', $return->id);

        return redirect()->route('income-tax.show', $return)->with('success', 'Income Tax Return created successfully!');
    }

    public function show(IncomeTaxReturn $incomeTax)
    {
        $incomeTax->load(['client', 'taxYear', 'incomeDetails', 'taxCredits', 'taxDeducted']);
        return view('income_tax.show', compact('incomeTax'));
    }

    public function edit(IncomeTaxReturn $incomeTax)
    {
        if ($incomeTax->return_status === 'Published') {
            return back()->with('error', 'Published returns cannot be edited.');
        }
        $taxYears = TaxYear::orderBy('tax_year', 'desc')->get();
        $incomeTax->load(['incomeDetails', 'taxCredits', 'taxDeducted']);
        return view('income_tax.edit', compact('incomeTax', 'taxYears'));
    }

    public function update(Request $request, IncomeTaxReturn $incomeTax)
    {
        if ($incomeTax->return_status === 'Published') {
            return back()->with('error', 'Published returns cannot be edited.');
        }

        // Add Tax Credit
        if ($request->add_credit) {
            $request->validate([
                'credit_description' => 'required|string',
                'credit_amount'      => 'required|numeric|min:0',
            ]);
            $incomeTax->taxCredits()->create([
                'description' => $request->credit_description,
                'amount'      => $request->credit_amount,
            ]);
            return back()->with('success', 'Tax credit added!');
        }

        // Add Tax Deducted
        if ($request->add_deducted) {
            $request->validate([
                'deducted_source' => 'required|string',
                'deducted_amount' => 'required|numeric|min:0',
            ]);
            $incomeTax->taxDeducted()->create([
                'source_name' => $request->deducted_source,
                'amount'      => $request->deducted_amount,
            ]);
            return back()->with('success', 'Tax deducted entry added!');
        }

        // Normal Update
        $incomeTax->update([
            'return_type' => $request->return_type,
            'notes'       => $request->notes,
        ]);

        $incomeTax->incomeDetails()->updateOrCreate(
            ['return_id' => $incomeTax->id],
            [
                'salary_income'   => $request->salary_income ?? 0,
                'business_income' => $request->business_income ?? 0,
                'property_income' => $request->property_income ?? 0,
                'capital_gain'    => $request->capital_gain ?? 0,
                'other_income'    => $request->other_income ?? 0,
            ]
        );

        UserActivityLog::log('Updated income tax return', 'income_tax_returns', $incomeTax->id);

        return redirect()->route('income-tax.show', $incomeTax)->with('success', 'Return updated successfully!');
    }

    public function publish(IncomeTaxReturn $incomeTax)
    {
        $incomeTax->update([
            'return_status'  => 'Published',
            'published_by'   => auth()->id(),
            'published_date' => now(),
        ]);

        UserActivityLog::log('Published income tax return', 'income_tax_returns', $incomeTax->id);

        return back()->with('success', 'Return published successfully!');
    }

    public function destroy(IncomeTaxReturn $incomeTax)
    {
        if ($incomeTax->return_status === 'Published') {
            return back()->with('error', 'Published returns cannot be deleted.');
        }
        UserActivityLog::log('Deleted income tax return', 'income_tax_returns', $incomeTax->id);
        $incomeTax->delete();

        return redirect()->route('income-tax.index')->with('success', 'Return deleted successfully!');
    }
}