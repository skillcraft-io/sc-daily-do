<?php

namespace Skillcraft\DailyDo\Models;

use Botble\Base\Casts\SafeContent;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Skillcraft\Core\Models\CoreModel;

/**
 * @method static \Botble\Base\Models\BaseQueryBuilder<static> query()
 */
class DailyDo extends CoreModel
{
    use SoftDeletes;

    protected $table = 'daily_dos';

    protected $fillable = [
        'module_type',
        'module_id',
        'title',
        'description',
        'due_date',
        'is_completed',
    ];

    protected $casts = [
        'title' => SafeContent::class,
        'is_completed' => 'boolean',
        'description' => SafeContent::class,
        'due_date' => 'date',
    ];

    public function module(): ?MorphTo
    {
        return $this->morphTo();
    }
}
