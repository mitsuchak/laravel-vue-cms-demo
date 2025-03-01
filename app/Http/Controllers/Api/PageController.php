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

    public function show_slug($slug)
    {
        // Split the slug into segments
        $segments = $slug ? explode('/', $slug) : [];
        
        // Start from the root pages (pages with no parent)
        $pages = Page::with('children')->whereNull('parent_id')->get();

        // Traverse the tree to find the matching page
        $page = $this->resolvePageFromSegments($pages, $segments);
        if (!$page) {
            return response()->json(['error' => 'Page not found'], 404);
        }

        return response()->json($page);
    }

    /**
     * Recursively resolve the page from the slug segments.
     */
    private function resolvePageFromSegments($pages, $segments, $index = 0)
    {

        // Get the current segment
        $currentSlug = $segments[$index] ?? null;
        
        // Find the page with the matching slug
        $page = Page::where('slug', $currentSlug);
        if (!$page) {
            return null; // No matching page found
        }

        // If this is the last segment, return the page
        if ($index === count($segments) - 1) {
            return $page;
        }

        // Otherwise, continue traversing the tree
        return $this->resolvePageFromSegments($page->children, $segments, $index + 1);
    }

}