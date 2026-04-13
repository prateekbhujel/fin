<?php

namespace App\Modules\Announcements\Services;

use App\Models\Announcement;
use App\Modules\Announcements\DTOs\AnnouncementData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AnnouncementService
{
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Announcement::query()->with('user')->latest('published_at');

        if (($published = $filters['published'] ?? null) !== null && $published !== '') {
            $query->where('is_published', (bool) $published);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function publishedForDashboard(int $limit = 5): Collection
    {
        return Announcement::query()
            ->published()
            ->latest('published_at')
            ->take($limit)
            ->get();
    }

    public function store(AnnouncementData $data, int $userId): Announcement
    {
        return Announcement::create($data->toArray(
            userId: $userId,
            fallbackPublishedAt: now(),
        ));
    }

    public function update(Announcement $announcement, AnnouncementData $data): Announcement
    {
        $announcement->update($data->toArray(
            userId: (int) $announcement->user_id,
            fallbackPublishedAt: $announcement->published_at ?? now(),
        ));

        return $announcement->refresh();
    }

    public function delete(Announcement $announcement): void
    {
        $announcement->delete();
    }
}
