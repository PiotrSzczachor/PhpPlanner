<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;

class TimelineController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('timeline', compact('categories'));
    }

    public function getEvents()
    {
        $events = Event::with('category')->get();
        return response()->json($events);
    }

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
        $validated = $request->validate([
            'name' => 'required|string',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', 
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $event = Event::create([
            'name' => $validated['name'],
            'startDate' => $validated['startDate'],
            'endDate' => $validated['endDate'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'category_id' => $validated['category_id'],
        ]);

        return response()->json($event);
    }

}
