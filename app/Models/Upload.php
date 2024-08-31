<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Upload extends Model
{
    use HasFactory;

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function uploadInfo() : MorphTo {
        return $this->morphTo();
    }

    protected $fillable = [
        'user_id',
        'upload_info_type',
        'upload_info_id',
        'uploadName',
    ];

}
