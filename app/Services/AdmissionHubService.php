<?php

namespace App\Services;

use App\Models\AdmissionHub;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class AdmissionHubService
{
    private static ?AdmissionHub $cache = null;

    public function get(): AdmissionHub
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        if (! Schema::hasTable('admission_hub')) {
            return self::$cache = $this->defaultHub();
        }

        $hub = AdmissionHub::query()->first();

        return self::$cache = $hub ?? $this->seedHub();
    }

    public function clearCache(): void
    {
        self::$cache = null;
    }

    public function seedHub(): AdmissionHub
    {
        $c = config('admission');

        return AdmissionHub::query()->create([
            'page_title' => $c['hub']['title'],
            'page_subtitle' => $c['hub']['subtitle'],
            'page_intro' => $c['hub']['intro'],
            'page_intro_2' => $c['hub']['intro_2'],
            'menu_items' => $c['menu'],
            'trust_title' => $c['trust']['title'],
            'trust_items' => $c['trust']['items'],
            'after_admission_title' => $c['after_admission']['title'],
            'after_admission_items' => $c['after_admission']['items'],
            'dashboard_title' => $c['student_dashboard']['title'],
            'dashboard_items' => $c['student_dashboard']['items'],
            'office_counselor' => $c['office']['counselor'],
            'office_phone' => $c['office']['phone'],
            'office_whatsapp' => $c['office']['whatsapp'],
            'office_email' => $c['office']['email'],
            'office_address' => $c['office']['address'],
            'maps_embed_url' => $c['office']['maps_embed_url'],
            'tagline_brand' => 'BUSINESS NAVACHAR SCHOOL',
            'tagline_text' => "India's Mission for Prosperity Through Business Education",
            'tagline_subtext' => 'Learn Prosperity • Create Prosperity • Build a Developed India',
            'tagline_hindi' => '( ज्ञान | नवाचार | समृद्धि )',
            'is_active' => true,
        ]);
    }

    public function storeHeroImage(AdmissionHub $hub, $file): string
    {
        $directory = public_path('uploads/admissions');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $hub->deleteHeroImage();

        $extension = $file->getClientOriginalExtension() ?: $file->extension();
        $filename = 'hub-'.time().'.'.strtolower($extension);
        $file->move($directory, $filename);

        $path = 'uploads/admissions/'.$filename;
        $hub->update(['hero_image' => $path]);
        $this->clearCache();

        return $path;
    }

    private function defaultHub(): AdmissionHub
    {
        $c = config('admission');

        return new AdmissionHub([
            'page_title' => $c['hub']['title'],
            'page_subtitle' => $c['hub']['subtitle'],
            'page_intro' => $c['hub']['intro'],
            'page_intro_2' => $c['hub']['intro_2'],
            'menu_items' => $c['menu'],
            'trust_title' => $c['trust']['title'],
            'trust_items' => $c['trust']['items'],
            'after_admission_title' => $c['after_admission']['title'],
            'after_admission_items' => $c['after_admission']['items'],
            'dashboard_title' => $c['student_dashboard']['title'],
            'dashboard_items' => $c['student_dashboard']['items'],
            'office_counselor' => $c['office']['counselor'],
            'office_phone' => $c['office']['phone'],
            'office_whatsapp' => $c['office']['whatsapp'],
            'office_email' => $c['office']['email'],
            'office_address' => $c['office']['address'],
            'maps_embed_url' => $c['office']['maps_embed_url'],
            'tagline_brand' => 'BUSINESS NAVACHAR SCHOOL',
            'tagline_text' => "India's Mission for Prosperity Through Business Education",
            'tagline_subtext' => 'Learn Prosperity • Create Prosperity • Build a Developed India',
            'tagline_hindi' => '( ज्ञान | नवाचार | समृद्धि )',
            'is_active' => true,
        ]);
    }
}
