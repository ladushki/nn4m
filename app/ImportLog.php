<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    public $fillable = [
        'filename',
        'inserted',
        'updated',
        'failed',
        'is_completed',
    ];

    public function errors(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ImportLogError::class, 'import_log_id', 'id');
    }


}
