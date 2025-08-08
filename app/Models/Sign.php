<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Sign extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'address',
        'lat',
        'lng',
        'notes',
        'image',
        'placed_at',
        'placed_by_user_id',
        'recovered_at',
        'recovered_by_user_id'
    ];

    protected $casts = [
        'placed_at' => 'datetime',
        'recovered_at' => 'datetime',
    ];

    protected static function booted()
    {
        /**
         * Delete file if record is deleted
         */
        static::deleting(function (Sign $sign) {
            // Check if the 'image' field has a value
            if (!is_null($sign->image)) {
                // Delete the image from S3
                Storage::disk('r2')->delete($sign->image);
            }
        });

        /**
         * Delete file is `image` field is changed to NULL
         */
        static::updating(function (Sign $sign) {
            // Check if the 'image' field is being updated and its new value is null
            if ($sign->isDirty('image') && is_null($sign->image)) {
                // Get the original (old) image path before it was set to null
                $oldImagePath = $sign->getOriginal('image');

                // If an old image path exists, delete the file from S3
                if ($oldImagePath) {
                    Storage::disk('r2')->delete($oldImagePath);
                }
            }
        });
    }

    // Tenant
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    // Relationship to the user who placed the sign
    public function placedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'placed_by_user_id');
    }

    // Relationship to the user who recovered the sign
    public function recoveredByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recovered_by_user_id');
    }

    // What campaign?
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
