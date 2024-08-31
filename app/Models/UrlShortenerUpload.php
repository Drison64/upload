<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlShortenerUpload extends Model
{
    use HasFactory;

    protected $table = 'urlshorteneruploads';

    protected $fillable = [
        'targetUrl',
    ];
}
