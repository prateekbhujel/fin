<?php

namespace App\Modules\Announcements\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Modules\Announcements\DTOs\AnnouncementData;
use App\Modules\Announcements\Http\Requests\StoreAnnouncementRequest;
use App\Modules\Announcements\Http\Requests\UpdateAnnouncementRequest;
use App\Modules\Announcements\Services\AnnouncementService;

class AnnouncementController extends Controller
{
    public function __construct(
        protected AnnouncementService $announcements,
    ) {
    }

    public function index()
    {
        return view('modules.announcements.index', [
            'announcements' => $this->announcements->paginate(request()->only('published')),
        ]);
    }

    public function create()
    {
        return view('modules.announcements.create');
    }

    public function store(StoreAnnouncementRequest $request)
    {
        $this->announcements->store(
            data: AnnouncementData::fromArray(
                data: $request->validated(),
                isPublished: $request->boolean('is_published'),
                publishedAt: $request->filled('published_at') ? $request->date('published_at') : null,
                expiresAt: $request->filled('expires_at') ? $request->date('expires_at') : null,
            ),
            userId: (int) auth()->id(),
        );

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
        $this->announcements->update($announcement, AnnouncementData::fromArray(
            data: $request->validated(),
            isPublished: $request->boolean('is_published'),
            publishedAt: $request->filled('published_at') ? $request->date('published_at') : null,
            expiresAt: $request->filled('expires_at') ? $request->date('expires_at') : null,
        ));

        return redirect()->route('announcements.index')->with('status', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $this->announcements->delete($announcement);

        return redirect()->route('announcements.index')->with('status', 'Announcement deleted successfully.');
    }
}
