<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('cnic', 'like', "%$search%")
                  ->orWhere('ntn', 'like', "%$search%")
                  ->orWhere('mobile', 'like', "%$search%")
                  ->orWhere('case_number', 'like', "%$search%")
                  ->orWhere('client_code', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $clients = $query->orderBy('name')->paginate(20);

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $clientCode = 'CL-' . str_pad(Client::count() + 1, 4, '0', STR_PAD_LEFT);
        return view('clients.create', compact('clientCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:150',
            'client_code' => 'required|unique:clients,client_code',
            'cnic'        => 'nullable|unique:clients,cnic',
            'ntn'         => 'nullable|unique:clients,ntn',
            'mobile'      => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:150',
            'annual_fee'  => 'nullable|numeric|min:0',
        ]);

        $client = Client::create($request->all());

        UserActivityLog::log('Created client', 'clients', $client->id, null, $client->toArray());

        return redirect()->route('clients.index')->with('success', 'Client added successfully!');
    }

    public function show(Client $client)
    {
        $client->load(['businesses', 'contacts', 'notes.user', 'incomeTaxReturns', 'vouchers', 'notices', 'tasks']);
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name'       => 'required|string|max:150',
            'cnic'       => 'nullable|unique:clients,cnic,' . $client->id,
            'ntn'        => 'nullable|unique:clients,ntn,' . $client->id,
            'mobile'     => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:150',
            'annual_fee' => 'nullable|numeric|min:0',
        ]);

        $old = $client->toArray();
        $client->update($request->all());

        UserActivityLog::log('Updated client', 'clients', $client->id, $old, $client->toArray());

        return redirect()->route('clients.show', $client)->with('success', 'Client updated successfully!');
    }

    public function destroy(Client $client)
    {
        UserActivityLog::log('Deleted client', 'clients', $client->id, $client->toArray(), null);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully!');
    }
}