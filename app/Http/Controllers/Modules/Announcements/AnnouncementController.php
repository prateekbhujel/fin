<?php

namespace App\Http\Controllers\Modules\Announcements;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modules\Announcements\StoreAnnouncementRequest;
use App\Http\Requests\Modules\Announcements\UpdateAnnouncementRequest;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $query = Announcement::query()->with('user')->latest('published_at');

        if (($published = request('published')) !== null && $published !== '') {
            $query->where('is_published', (bool) $published);
        }

        return view('modules.announcements.index', [
            'announcements' => $query->paginate(10)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('modules.announcements.create');
    }

    public function store(StoreAnnouncementRequest $request)
    {
        Announcement::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->filled('published_at')
                ? $request->date('published_at')
                : ($request->boolean('is_published') ? now() : null),
        ]);

        return redirect()->route('announcements.index')->with('status', 'Announcement created successfully.');
    }

    public function show(Announcement $announcement)
    {
        return redirect()->route('announcements.edit', $announcement);
    }

    public function edit(Announcement $announcement)
    {
        return view('modules.announcements.edit', [
            'announcement' => $announcement,
        ]);
    }

    public function update(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        $announcement->update([
            ...$request->validated(),
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->filled('published_at')
                ? $request->date('published_at')
                : ($request->boolean('is_published') ? ($announcement->published_at ?? now()) : null),
        ]);

        return redirect()->route('announcements.index')->with('status', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('announcements.index')->with('status', 'Announcement deleted successfully.');
    }
}
