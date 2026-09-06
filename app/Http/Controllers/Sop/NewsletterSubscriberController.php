<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $subscribers = collect();
        $total = 0;

        if (Schema::hasTable('newsletter_subscribers')) {
            $query = NewsletterSubscriber::query()->latest();

            if ($search !== '') {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('email', 'like', "%{$search}%")
                        ->orWhere('source', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%");
                });
            }

            $subscribers = $query->paginate(20)->withQueryString();
            $total = NewsletterSubscriber::query()->count();
        }

        return view('sop.newsletter-subscribers.index', [
            'subscribers' => $subscribers,
            'search' => $search,
            'total' => $total,
        ]);
    }

    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return back()->with('status', 'Subscriber deleted.');
    }
}
