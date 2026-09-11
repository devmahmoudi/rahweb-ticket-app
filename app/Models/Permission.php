<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    /**
     * Roles that have this permission
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    #[Scope]
    protected function whereModel(Builder $query, string $model): void
    {
        $model = str_replace('App\\Models\\', '', $model);

        $query->where('model', "App\\Models\\{$model}");
    }
}
