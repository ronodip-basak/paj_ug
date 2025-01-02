<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Device extends Model
{
    protected $fillable = ["name", "model", "device_unique_id"];


    public function getRouteKeyName()
    {
        return "device_unique_id";
    }

    public function users(): BelongsToMany {
        return $this->belongsToMany("App\Models\User", "device_user_pivot");
    }
}
