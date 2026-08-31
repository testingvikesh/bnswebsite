<?php

namespace App\Services;

use App\Models\TeamMember;
use App\Models\TeamPage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class TeamPageService
{
    private static ?TeamPage $pageCache = null;

    public function get(): TeamPage
    {
        if (self::$pageCache !== null) {
            return self::$pageCache;
        }

        if (! Schema::hasTable('team_pages')) {
            return self::$pageCache = $this->defaultPage();
        }

        $page = TeamPage::query()->first();

        if ($page === null) {
            $page = $this->seedPage();
        }

        return self::$pageCache = $page;
    }

    /** @return Collection<int, TeamMember> */
    public function members(string $category): Collection
    {
        if (! Schema::hasTable('team_members')) {
            return $this->defaultMembers($category);
        }

        $members = TeamMember::query()
            ->where('category', $category)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('full_name')
            ->get();

        if ($members->isEmpty()) {
            return $this->seedMembersIfEmpty($category);
        }

        return $members;
    }

    public function clearCache(): void
    {
        self::$pageCache = null;
    }

    public function seedPage(): TeamPage
    {
        $config = config('team');

        return TeamPage::query()->create([
            'page_title' => $config['page']['title'],
            'page_subtitle' => $config['page']['subtitle'],
            'page_intro' => $config['page']['intro'],
            'leadership_title' => $config['leadership']['title'],
            'academic_title' => $config['academic']['title'],
            'advisory_title' => $config['advisory']['title'],
            'collab_badge' => $config['collaboration']['badge'],
            'collab_title' => $config['collaboration']['title'],
            'collab_description' => $config['collaboration']['description'],
            'operations_title' => $config['operations']['title'],
            'operations_teams' => $config['operations']['teams'],
            'values_title' => $config['values']['title'],
            'values_items' => $config['values']['items'],
            'join_title' => $config['join']['title'],
            'join_intro' => $config['join']['intro'],
            'join_looking_label' => $config['join']['looking_for_label'],
            'join_roles' => $config['join']['roles'],
            'join_cta_title' => $config['join']['cta_title'],
            'join_cta_text' => $config['join']['cta_text'],
            'join_contact_email' => $config['join']['contact_email'],
            'is_active' => true,
        ]);
    }

    public function storeHeroImage(TeamPage $page, $file): string
    {
        $directory = public_path('uploads/team');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $page->deleteHeroImage();

        $extension = $file->getClientOriginalExtension() ?: $file->extension();
        $filename = 'hero-'.time().'.'.strtolower($extension);
        $file->move($directory, $filename);

        $path = 'uploads/team/'.$filename;
        $page->update(['hero_image' => $path]);
        $this->clearCache();

        return $path;
    }

    /** @return Collection<int, TeamMember> */
    private function seedMembersIfEmpty(string $category): Collection
    {
        $config = config('team');
        $key = $category === TeamMember::CATEGORY_LEADERSHIP ? 'leadership' : 'academic';
        $items = $config[$key]['members'] ?? [];
        $sort = 1;

        foreach ($items as $item) {
            TeamMember::query()->create([
                'category' => $category,
                'full_name' => $item['name'],
                'designation' => $item['designation'],
                'role' => $item['role'] ?? null,
                'expertise' => $item['expertise'] ?? [],
                'linkedin_url' => $item['linkedin'] ?? null,
                'email' => $item['email'] ?? null,
                'is_featured' => (bool) ($item['featured'] ?? false),
                'sort_order' => $sort++,
                'is_active' => true,
            ]);
        }

        return TeamMember::query()
            ->where('category', $category)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    private function defaultPage(): TeamPage
    {
        $config = config('team');

        return new TeamPage([
            'page_title' => $config['page']['title'],
            'page_subtitle' => $config['page']['subtitle'],
            'page_intro' => $config['page']['intro'],
            'leadership_title' => $config['leadership']['title'],
            'academic_title' => $config['academic']['title'],
            'advisory_title' => $config['advisory']['title'],
            'collab_badge' => $config['collaboration']['badge'],
            'collab_title' => $config['collaboration']['title'],
            'collab_description' => $config['collaboration']['description'],
            'operations_title' => $config['operations']['title'],
            'operations_teams' => $config['operations']['teams'],
            'values_title' => $config['values']['title'],
            'values_items' => $config['values']['items'],
            'join_title' => $config['join']['title'],
            'join_intro' => $config['join']['intro'],
            'join_looking_label' => $config['join']['looking_for_label'],
            'join_roles' => $config['join']['roles'],
            'join_cta_title' => $config['join']['cta_title'],
            'join_cta_text' => $config['join']['cta_text'],
            'join_contact_email' => $config['join']['contact_email'],
            'is_active' => true,
        ]);
    }

    /** @return Collection<int, TeamMember> */
    private function defaultMembers(string $category): Collection
    {
        $config = config('team');
        $key = $category === TeamMember::CATEGORY_LEADERSHIP ? 'leadership' : 'academic';

        return collect($config[$key]['members'] ?? [])->map(function (array $item) use ($category) {
            return new TeamMember([
                'category' => $category,
                'full_name' => $item['name'],
                'designation' => $item['designation'],
                'role' => $item['role'] ?? null,
                'expertise' => $item['expertise'] ?? [],
                'linkedin_url' => $item['linkedin'] ?? null,
                'email' => $item['email'] ?? null,
                'is_featured' => (bool) ($item['featured'] ?? false),
                'is_active' => true,
            ]);
        });
    }
}
