<?php

namespace App\Models;

use App\Models\Scopes\BugUserTypeScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy(BugUserTypeScope::class)]
class Bug extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * The bug reporter.
     *
     * @return BelongsTo
     */
    public function creator():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
