<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'agree' => ['accepted'],
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'agree.accepted' => 'Please agree to the terms & conditions.',
        ]);

        $email = strtolower(trim($validated['email']));

        $alreadySubscribed = NewsletterSubscriber::query()
            ->where('email', $email)
            ->exists();

        if (! $alreadySubscribed) {
            NewsletterSubscriber::create([
                'email' => $email,
                'agreed_terms' => true,
                'source' => 'footer',
                'ip_address' => $request->ip(),
            ]);
        }

        return redirect()
            ->back()
            ->withFragment('newsletter-subscribe')
            ->with(
                'newsletter_success',
                $alreadySubscribed
                    ? 'Thank you! You are already subscribed to BNS updates and events.'
                    : 'Thank you for subscribing! You will receive BNS updates and events in your inbox.'
            );
    }
}
