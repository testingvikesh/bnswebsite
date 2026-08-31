<?php

namespace App\Services;

use App\Models\ContactPage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ContactPageService
{
    private static ?ContactPage $cache = null;

    public function get(): ContactPage
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        if (! Schema::hasTable('contact_pages')) {
            return self::$cache = $this->defaultPage();
        }

        $page = ContactPage::query()->first();

        return self::$cache = $page ?? $this->seedPage();
    }

    public function clearCache(): void
    {
        self::$cache = null;
    }

    public function seedPage(): ContactPage
    {
        $c = config('contact');

        return ContactPage::query()->create([
            'page_title' => $c['page']['title'],
            'page_subtitle' => $c['page']['subtitle'],
            'page_intro' => $c['page']['intro'],
            'page_intro_2' => $c['page']['intro_2'],
            'office_title' => $c['office']['title'],
            'office_brand' => $c['office']['brand'],
            'office_tagline' => $c['office']['tagline'],
            'office_head_label' => $c['office']['head_label'],
            'address_line' => $c['office']['address_line'],
            'city' => $c['office']['city'],
            'state' => $c['office']['state'],
            'pin_code' => $c['office']['pin_code'],
            'phone_helpline' => $c['phones']['helpline'],
            'phone_whatsapp' => $c['phones']['whatsapp'],
            'phone_office' => $c['phones']['office'],
            'email_admissions' => $c['emails']['admissions'],
            'email_general' => $c['emails']['general'],
            'email_media' => $c['emails']['media'],
            'website' => $c['website'],
            'office_hours' => $c['office_hours'],
            'admission_support_title' => $c['admission_support']['title'],
            'admission_support_intro' => $c['admission_support']['intro'],
            'admission_support_items' => $c['admission_support']['items'],
            'partnership_title' => $c['partnership']['title'],
            'partnership_intro' => $c['partnership']['intro'],
            'partnership_items' => $c['partnership']['items'],
            'faculty_cta_title' => $c['faculty_cta']['title'],
            'faculty_cta_text' => $c['faculty_cta']['text'],
            'faculty_cta_url' => $c['faculty_cta']['url'],
            'media_title' => $c['media']['title'],
            'media_text' => $c['media']['text'],
            'social_links' => $c['social'],
            'maps_embed_url' => $c['maps_embed_url'],
            'form_categories' => $c['form_categories'],
            'immediate_title' => $c['immediate']['title'],
            'immediate_call' => $c['immediate']['call_phone'],
            'immediate_whatsapp' => $c['immediate']['whatsapp'],
            'immediate_intro_url' => $c['immediate']['intro_url'],
            'immediate_apply_url' => $c['immediate']['apply_url'],
            'tagline_brand' => $c['footer_tagline']['brand'],
            'tagline_text' => $c['footer_tagline']['text'],
            'tagline_subtext' => $c['footer_tagline']['subtext'],
            'tagline_hindi' => $c['footer_tagline']['hindi'],
            'is_active' => true,
        ]);
    }

    public function storeHeroImage(ContactPage $page, $file): string
    {
        $directory = public_path('uploads/contact');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $page->deleteHeroImage();

        $extension = $file->getClientOriginalExtension() ?: $file->extension();
        $filename = 'hero-'.time().'.'.strtolower($extension);
        $file->move($directory, $filename);

        $path = 'uploads/contact/'.$filename;
        $page->update(['hero_image' => $path]);
        $this->clearCache();

        return $path;
    }

    private function defaultPage(): ContactPage
    {
        $c = config('contact');

        return new ContactPage([
            'page_title' => $c['page']['title'],
            'page_subtitle' => $c['page']['subtitle'],
            'page_intro' => $c['page']['intro'],
            'page_intro_2' => $c['page']['intro_2'],
            'office_title' => $c['office']['title'],
            'office_brand' => $c['office']['brand'],
            'office_tagline' => $c['office']['tagline'],
            'office_head_label' => $c['office']['head_label'],
            'address_line' => $c['office']['address_line'],
            'city' => $c['office']['city'],
            'state' => $c['office']['state'],
            'pin_code' => $c['office']['pin_code'],
            'phone_helpline' => $c['phones']['helpline'],
            'phone_whatsapp' => $c['phones']['whatsapp'],
            'phone_office' => $c['phones']['office'],
            'email_admissions' => $c['emails']['admissions'],
            'email_general' => $c['emails']['general'],
            'email_media' => $c['emails']['media'],
            'website' => $c['website'],
            'office_hours' => $c['office_hours'],
            'admission_support_title' => $c['admission_support']['title'],
            'admission_support_intro' => $c['admission_support']['intro'],
            'admission_support_items' => $c['admission_support']['items'],
            'partnership_title' => $c['partnership']['title'],
            'partnership_intro' => $c['partnership']['intro'],
            'partnership_items' => $c['partnership']['items'],
            'faculty_cta_title' => $c['faculty_cta']['title'],
            'faculty_cta_text' => $c['faculty_cta']['text'],
            'faculty_cta_url' => $c['faculty_cta']['url'],
            'media_title' => $c['media']['title'],
            'media_text' => $c['media']['text'],
            'social_links' => $c['social'],
            'maps_embed_url' => $c['maps_embed_url'],
            'form_categories' => $c['form_categories'],
            'immediate_title' => $c['immediate']['title'],
            'immediate_call' => $c['immediate']['call_phone'],
            'immediate_whatsapp' => $c['immediate']['whatsapp'],
            'immediate_intro_url' => $c['immediate']['intro_url'],
            'immediate_apply_url' => $c['immediate']['apply_url'],
            'tagline_brand' => $c['footer_tagline']['brand'],
            'tagline_text' => $c['footer_tagline']['text'],
            'tagline_subtext' => $c['footer_tagline']['subtext'],
            'tagline_hindi' => $c['footer_tagline']['hindi'],
            'is_active' => true,
        ]);
    }
}
