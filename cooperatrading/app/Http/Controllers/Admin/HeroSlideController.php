<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHeroSlideRequest;
use App\Http\Requests\Admin\UpdateHeroSlideRequest;
use App\Models\HeroSlide;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    public function index(Request $request): View
    {
        $query = HeroSlide::query();

        if ($request->filled('status')) {
            if ($request->input('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->input('status') === 'hidden') {
                $query->where('is_active', false);
            }
        }

        $sort = $request->input('sort', 'sort_order');
        if ($sort === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
        }

        $slides = $query->paginate(50)->withQueryString();
        return view('admin.hero-slides.index', compact('slides'));
    }

    public function create(): View
    {
        return view('admin.hero-slides.create');
    }

    public function store(StoreHeroSlideRequest $request): RedirectResponse
    {
        $data = $this->extractSlideData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hero-slides', 'public');
        }

        if (!array_key_exists('sort_order', $data) || $data['sort_order'] === null) {
            $data['sort_order'] = (int) HeroSlide::max('sort_order') + 1;
        }

        HeroSlide::create($data);

        return redirect()->route('admin.hero-slides.index')
            ->with('status', 'Hero slide added successfully.');
    }

    public function edit(HeroSlide $heroSlide): View
    {
        return view('admin.hero-slides.edit', ['slide' => $heroSlide]);
    }

    public function update(UpdateHeroSlideRequest $request, HeroSlide $heroSlide): RedirectResponse
    {
        $data = $this->extractSlideData($request);

        if ($request->hasFile('image')) {
            if ($heroSlide->image && Storage::disk('public')->exists($heroSlide->image)) {
                Storage::disk('public')->delete($heroSlide->image);
            }
            $data['image'] = $request->file('image')->store('hero-slides', 'public');
        }

        $heroSlide->update($data);

        return redirect()->route('admin.hero-slides.index')
            ->with('status', 'Hero slide updated successfully.');
    }

    public function toggleActive(HeroSlide $heroSlide): RedirectResponse
    {
        $heroSlide->update([
            'is_active' => !$heroSlide->is_active,
        ]);

        $state = $heroSlide->is_active ? 'visible on the home page' : 'hidden from the home page';
        return back()->with('status', "Slide \"{$heroSlide->title}\" is now {$state}.");
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'order' => ['required', 'array', 'min:1'],
            'order.*' => ['integer', 'exists:hero_slides,id'],
        ]);

        $ids = $request->input('order');
        foreach ($ids as $position => $id) {
            HeroSlide::whereKey((int) $id)->update(['sort_order' => $position]);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Slide order saved.',
        ]);
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        if ($heroSlide->image && Storage::disk('public')->exists($heroSlide->image)) {
            Storage::disk('public')->delete($heroSlide->image);
        }

        $heroSlide->delete();

        return redirect()->route('admin.hero-slides.index')
            ->with('status', 'Hero slide deleted successfully.');
    }

    private function extractSlideData(Request $request): array
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        if (isset($data['tags']) && is_string($data['tags'])) {
            $tags = array_values(array_filter(array_map(
                fn ($t) => trim($t),
                explode(',', $data['tags'])
            )));
            $data['tags'] = $tags;
        }

        return $data;
    }
}
