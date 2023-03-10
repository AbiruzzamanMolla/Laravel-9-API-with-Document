<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'image',
        'user_id',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!is_null($this->image)) return asset(Storage::url($this->image));
        return asset('default/product-image.jpg');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;

        if (!$this->exists) {
            $slug = str()->slug($value);

            // check if slug already exists
            $originalSlug = $slug;
            $count = 2;

            while (static::whereSlug($slug)->exists()) {
                $slug = "{$originalSlug}-" . $count++;
            }

            $this->attributes['slug'] = $slug;
        }
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid()->toString();
        });
    }
}
