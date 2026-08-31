<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'email',
        'agreed_terms',
        'source',
        'ip_address',
    ];

    protected $casts = [
        'agreed_terms' => 'boolean',
    ];
}
