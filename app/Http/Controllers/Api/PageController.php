<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    // Get all pages
    public function index()
    {
        $pages = Page::whereNull('parent_id')->with('children')->get();
        return response()->json($pages);
    }

    // Create a new page

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages',
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:pages,id',
        ]);

        // Create the page
        $page = Page::create($validated);

        // Return the created page as JSON with a 'data' key
        return response()->json([
            'data' => $page,
        ], 201);
    }

    // Get a single page
    public function show(Page $page)
    {
        return response()->json($page);
    }

    // Update a page
    public function update(Request $request, Page $page)
{
    // Validate the request
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
        'content' => 'required|string',
        'parent_id' => 'nullable|exists:pages,id',
    ]);

    // Update the page
    $page->update($validated);

    // Return the updated page as JSON with a 'data' key
    return response()->json([
        'data' => $page,
    ]);
}

    // Delete a page
    public function destroy(Page $page)
    {
        $page->delete();
        return response()->json(null, 204);
    }
}