<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportLogError extends Model
{
    public $fillable = [
        'store_number',
        'import_log_id',
        'column_name',
        'description',
    ];

    public function log(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ImportLog::class, 'import_log_id', 'id');
    }

    public function scopeLast($query)
    {
        return $query->whereRaw('import_log_id = (SELECT id FROM import_logs ORDER BY id DESC LIMIT 1)');
    }
}
