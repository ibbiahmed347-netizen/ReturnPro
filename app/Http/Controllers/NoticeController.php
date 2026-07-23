<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\NoticeReply;
use App\Models\Client;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $query = Notice::with(['client', 'replies']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%$search%")
                  ->orWhere('notice_number', 'like', "%$search%")
                  ->orWhereHas('client', fn($q) => $q->where('name', 'like', "%$search%")
                  ->orWhere('case_number', 'like', "%$search%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $notices = $query->orderBy('notice_date', 'desc')->paginate(20);
        return view('notices.index', compact('notices'));
    }

    public function create()
    {
        $clients = Client::where('status', 'Active')->orderBy('name')->get();
        return view('notices.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id'   => 'required|exists:clients,id',
            'notice_date' => 'required|date',
            'subject'     => 'required|string|max:255',
        ]);

        $notice = Notice::create([
            'client_id'     => $request->client_id,
            'notice_number' => $request->notice_number,
            'notice_date'   => $request->notice_date,
            'subject'       => $request->subject,
            'description'   => $request->description,
            'status'        => 'Open',
        ]);

        UserActivityLog::log('Created notice', 'notices', $notice->id);

        return redirect()->route('notices.show', $notice)->with('success', 'Notice added successfully!');
    }

    public function show(Notice $notice)
    {
        $notice->load(['client', 'replies.repliedBy']);
        return view('notices.show', compact('notice'));
    }

    public function edit(Notice $notice)
    {
        $clients = Client::where('status', 'Active')->orderBy('name')->get();
        return view('notices.edit', compact('notice', 'clients'));
    }

    public function update(Request $request, Notice $notice)
    {
        // Add Reply
        if ($request->add_reply) {
            $request->validate(['reply_date' => 'required|date', 'remarks' => 'required|string']);
            $notice->replies()->create([
                'reply_date' => $request->reply_date,
                'remarks'    => $request->remarks,
                'replied_by' => auth()->id(),
            ]);
            return back()->with('success', 'Reply added successfully!');
        }

        $request->validate([
            'client_id'   => 'required|exists:clients,id',
            'notice_date' => 'required|date',
            'subject'     => 'required|string|max:255',
            'status'      => 'required|in:Open,In Progress,Closed',
        ]);

        $notice->update([
            'client_id'     => $request->client_id,
            'notice_number' => $request->notice_number,
            'notice_date'   => $request->notice_date,
            'subject'       => $request->subject,
            'description'   => $request->description,
            'status'        => $request->status,
        ]);

        UserActivityLog::log('Updated notice', 'notices', $notice->id);
        return redirect()->route('notices.show', $notice)->with('success', 'Notice updated successfully!');
    }

    public function destroy(Notice $notice)
    {
        UserActivityLog::log('Deleted notice', 'notices', $notice->id);
        $notice->delete();
        return redirect()->route('notices.index')->with('success', 'Notice deleted successfully!');
    }
}