<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        // Get events for the current user
        $events = CalendarEvent::where('user_id', Auth::id())
            ->orderBy('start_time')
            ->get();

        return view('calendar.index', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
            'type' => 'required|in:Reminder,Appointment,Event',
        ]);

        CalendarEvent::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'type' => $request->type,
        ]);

        return back()->with('success', 'Event added successfully');
    }

    public function destroy(CalendarEvent $event)
    {
        // Ensure user owns the event
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $event->delete();
        return back()->with('success', 'Event deleted');
    }
}