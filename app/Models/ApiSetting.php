<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * App\Models\ApiSetting
 *
 * @property int $id
 * @property string $url
 * @property string $key
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read User|null $user
 * @method static Builder|ApiSetting newModelQuery()
 * @method static Builder|ApiSetting newQuery()
 * @method static Builder|ApiSetting query()
 * @method static Builder|ApiSetting whereCreatedAt($value)
 * @method static Builder|ApiSetting whereId($value)
 * @method static Builder|ApiSetting whereKey($value)
 * @method static Builder|ApiSetting whereUpdatedAt($value)
 * @method static Builder|ApiSetting whereUrl($value)
 * @method static Builder|ApiSetting whereUserId($value)
 * @property-read Collection<int, PersonalAccessToken> $tokens
 * @mixin Eloquent
 */
class ApiSetting extends Model
{
    use HasApiTokens;

    protected $table = 'api_settings';
    protected $fillable = [
        'url',
        'key'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
