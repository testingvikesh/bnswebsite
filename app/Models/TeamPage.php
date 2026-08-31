<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class TeamPage extends Model
{
    protected $fillable = [
        'page_title',
        'page_subtitle',
        'page_intro',
        'leadership_title',
        'academic_title',
        'advisory_title',
        'collab_badge',
        'collab_title',
        'collab_description',
        'operations_title',
        'operations_teams',
        'values_title',
        'values_items',
        'join_title',
        'join_intro',
        'join_looking_label',
        'join_roles',
        'join_cta_title',
        'join_cta_text',
        'join_contact_email',
        'hero_image',
        'is_active',
    ];

    protected $casts = [
        'operations_teams' => 'array',
        'values_items' => 'array',
        'join_roles' => 'array',
        'is_active' => 'boolean',
    ];

    public function heroImageUrl(?callable $fallback = null): string
    {
        if ($this->hero_image && File::exists(public_path($this->hero_image))) {
            return bns_vasset($this->hero_image);
        }

        return $fallback ? $fallback() : bns_vasset('assets/images/backgrounds/page-header-bg.jpg');
    }

    public function deleteHeroImage(): void
    {
        if ($this->hero_image && str_starts_with($this->hero_image, 'uploads/team/')
            && File::exists(public_path($this->hero_image))) {
            File::delete(public_path($this->hero_image));
        }
    }
}
