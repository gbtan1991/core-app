<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $key
 * @property bool $is_active
 * @property Carbon|null $last_used_at
 * @property int|null $created_by
 */
class FunnelApiKey extends Model
{
    protected $fillable = ['name', 'key', 'is_active', 'last_used_at', 'created_by'];

    protected function casts(): array
    {
        return [
            'is_active'    => 'boolean',
            'last_used_at' => 'datetime',
        ];
    }

    /** The staff member who generated this key. */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
