<?php

namespace App\Models;

use App\Models\User;
use App\Models\Module;
use Illuminate\Database\Eloquent\Model;

class ModuleDownload extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'module_id',
        'downloaded_at',
    ];

    protected $dates = ['downloaded_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}