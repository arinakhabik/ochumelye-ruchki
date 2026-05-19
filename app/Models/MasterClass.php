<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'leader_id',
        'title',
        'description',
        'class_date',
        'time_slot',
        'capacity',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'class_date' => 'date',
            'price' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function freePlaces(): int
    {
        return $this->capacity - $this->registrations()->count();
    }

    public function hasFreePlaces(): bool
    {
        return $this->freePlaces() > 0;
    }
}
