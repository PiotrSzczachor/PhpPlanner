<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class TimelineController extends Controller
{
    // Display the timeline view
    public function index()
    {
        return view('timeline');
    }

    // Fetch events for the timeline
    public function getEvents()
    {
        $events = Event::all(); // Fetch all events
        return response()->json($events); // Return events as JSON
    }

    // Delete an event
    public function deleteEvent($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->delete();
    
            return response()->json(['message' => 'Event deleted successfully'])->setStatusCode(200);
    
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete event', 'error' => $e->getMessage()])->setStatusCode(500);
        }
    }

    public function create(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'name' => 'required|string',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validate the image
        ]);

        // Handle the image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public'); // Store the image in the 'public/images' directory
        }

        // Create the event
        $event = Event::create([
            'name' => $validated['name'],
            'startDate' => $validated['startDate'],
            'endDate' => $validated['endDate'],
            'description' => $validated['description'],
            'image' => $imagePath, // Save the image path in the database
            'category_id' => 1, // You can set this dynamically
        ]);

        return response()->json($event);
    }

}
