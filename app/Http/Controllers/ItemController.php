<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Item;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Services\ItemService;

/**
 * ItemController
 * 
 * This controller handles all operations related to lost and found items.
 * It demonstrates REUSABILITY through separate methods for different actions.
 */
class ItemController extends Controller
{
    public function __construct(private ItemService $itemService)
    {
    }

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
        [$items, $stats] = $this->itemService->getItemsForDashboard($request);
        
        // Return the view with items data
        return view('items.index', compact('items', 'stats'));
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
    public function store(StoreItemRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        // Create new item using mass assignment (protected by $fillable in Model)
        $this->itemService->createItem($validated);

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
        // Require authorization
        Gate::authorize('update', $item);

        $item->load('photos');

        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        // Require authorization
        Gate::authorize('update', $item);

        $this->itemService->updateItem($item, $request->validated());

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
    public function claim(Request $request, Item $item)
    {
        // Require authorization
        Gate::authorize('claim', $item);

        $claimantInfo = $request->input('claimant_info');
        $this->itemService->markItemClaimed($item, $claimantInfo);

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
        // Require authorization
        Gate::authorize('delete', $item);

        $this->itemService->deleteItem($item);

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
        $item->load('photos');

        return view('items.show', compact('item'));
    }
}
