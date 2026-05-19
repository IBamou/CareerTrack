<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Http\Requests\Reminder\StoreReminderRequest;
use App\Http\Requests\Reminder\UpdateReminderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    public function index()
    {
        $reminders = Reminder::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->orderBy('remind_at')
            ->with('remindable')
            ->paginate(15);

        return view('reminder.index', compact('reminders'));
    }

    public function create(Request $request)
    {
        $remindableType = $request->remindable_type;
        $remindableId = $request->remindable_id;

        return view('reminder.create', compact('remindableType', 'remindableId'));
    }

    public function store(StoreReminderRequest $request)
    {
        $reminder = Reminder::create([
            ...$request->validated(),
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        return redirect()->back()->with('status', 'Reminder set.');
    }

    public function edit(Reminder $reminder)
    {
        $this->authorize('view', $reminder);
        return view('reminder.edit', compact('reminder'));
    }

    public function update(UpdateReminderRequest $request, Reminder $reminder)
    {
        $this->authorize('update', $reminder);
        $reminder->update($request->validated());

        return redirect()->back()->with('status', 'Reminder updated.');
    }

    public function destroy(Reminder $reminder)
    {
        $this->authorize('delete', $reminder);
        $reminder->delete();

        return redirect()->back()->with('status', 'Reminder cancelled.');
    }

    public function complete(Reminder $reminder)
    {
        $this->authorize('update', $reminder);
        $reminder->update(['status' => 'sent', 'reminded_at' => now()]);

        return redirect()->back()->with('status', 'Reminder marked as done.');
    }
}
