<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

/**
 * ItemController
 * 
 * This controller handles all operations related to lost and found items.
 * It demonstrates REUSABILITY through separate methods for different actions.
 */
class ItemController extends Controller
{
    /**
     * Display a listing of all items with search and filter.
     * 
     * This method retrieves items from the database with optional
     * search and status filtering.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Start with base query
        $query = Item::query();
        
        // Apply search filter if provided
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('item_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('location', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        // Apply status filter if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Get filtered items, ordered by most recent first
        $items = $query->orderBy('created_at', 'desc')->get();
        
        // Get all items for statistics (unfiltered)
        $allItems = Item::all();
        
        // Return the view with items data
        return view('items.index', compact('items', 'allItems'));
    }

    /**
     * Show the form for creating a new item.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created item in the database.
     * 
     * This method demonstrates INPUT VALIDATION to ensure
     * data integrity before saving to database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'status' => 'required|in:Lost,Found',
            'contact' => 'required|string|max:255'
        ]);

        // Create new item using mass assignment (protected by $fillable in Model)
        Item::create($request->all());

        // Redirect back to the items list with success message
        return redirect()->route('items.index')
            ->with('success', 'Item reported successfully!');
    }

    /**
     * Show the form for editing the specified item.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\View\View
     */
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'location' => 'required|string|max:255',
            'status' => 'required|in:Lost,Found,Claimed',
            'contact' => 'required|string|max:255'
        ]);

        $item->update($request->all());

        return redirect()->route('items.index')
            ->with('success', 'Item updated successfully!');
    }

    /**
     * Mark an item as claimed.
     * 
     * This updates the item status to 'Claimed' indicating
     * the owner has recovered their item.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function claim(Item $item)
    {
        $item->status = 'Claimed';
        $item->save();

        return redirect()->route('items.index')
            ->with('success', 'Item has been marked as claimed!');
    }

    /**
     * Remove the specified item from database.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Item has been deleted successfully!');
    }

    /**
     * Display the specified item.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\View\View
     */
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }
}
