<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'description',
    'sessions_count',
    'price',
    'is_active',
])]
class PtPackage extends Model
{
    protected function casts(): array
    {
        return [
            'sessions_count' => 'integer',
            'price' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function memberPackages(): HasMany
    {
        return $this->hasMany(PtSessionPackage::class);
    }
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtPackage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function quotas()   { return $this->hasMany(MemberPtQuota::class); }
    public function payments() { return $this->hasMany(Payment::class); }
>>>>>>> origin/develop
}
