<?php

namespace App\Services;

use App\Models\SiteSetting;
use App\Models\SponsorMember;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class SponsorPageService
{
    public const SECTION_SETTING_KEY = 'sponsors_section';

    /** @return array<string, mixed> */
    public function buildSponsorsForFront(): array
    {
        $defaults = config('team.sponsors', []);
        $section = $this->sectionSettings();

        $members = $this->activeMembers();

        if ($members->isEmpty()) {
            $members = collect($defaults['members'] ?? []);
        } else {
            $members = $members->map(fn (SponsorMember $member) => $member->toFrontArray());
        }

        return [
            'title' => $section['title'] ?? $defaults['title'] ?? 'Meet Our Sponsors',
            'subtitle' => $section['subtitle'] ?? $defaults['subtitle'] ?? '',
            'section_label' => $section['section_label'] ?? $defaults['section_label'] ?? 'Partners',
            'members' => $members->values()->all(),
        ];
    }

    public function syncFromConfigIfEmpty(): void
    {
        if (! Schema::hasTable('sponsor_members') || SponsorMember::query()->exists()) {
            return;
        }

        foreach (config('team.sponsors.members', []) as $index => $member) {
            SponsorMember::query()->create([
                'name' => $member['name'] ?? 'Sponsor',
                'designation' => $member['designation'] ?? null,
                'profile' => $member['profile'] ?? null,
                'default_photo' => $member['photo'] ?? null,
                'sort_order' => $member['sort_order'] ?? ($index + 1),
                'is_active' => true,
            ]);
        }
    }

    /** @return array<string, mixed> */
    public function sectionSettings(): array
    {
        if (! Schema::hasTable('site_settings')) {
            return [];
        }

        $raw = SiteSetting::query()->where('key', self::SECTION_SETTING_KEY)->value('value');

        if (! is_string($raw) || trim($raw) === '') {
            return [];
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : [];
    }

    /** @param array<string, mixed> $settings */
    public function saveSectionSettings(array $settings): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        SiteSetting::query()->updateOrCreate(
            ['key' => self::SECTION_SETTING_KEY],
            ['value' => json_encode($settings, JSON_UNESCAPED_UNICODE)]
        );
    }

    /** @return \Illuminate\Support\Collection<int, SponsorMember> */
    public function allForAdmin()
    {
        if (! Schema::hasTable('sponsor_members')) {
            return collect();
        }

        return SponsorMember::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /** @return \Illuminate\Support\Collection<int, SponsorMember> */
    private function activeMembers()
    {
        if (! Schema::hasTable('sponsor_members')) {
            return collect();
        }

        return SponsorMember::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function storePhoto(SponsorMember $member, UploadedFile $file): string
    {
        $directory = public_path('uploads/sponsors');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $member->deleteUploadedPhoto();

        $filename = 'sponsor-'.$member->id.'-'.time().'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return 'uploads/sponsors/'.$filename;
    }
}
