<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ApiSetting extends Model
{
    use HasApiTokens;

    protected $table = 'api_settings';
    protected $fillable = [
        'url',
        'key'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
