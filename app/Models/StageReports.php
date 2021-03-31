<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StageReports extends Model
{
    use HasFactory;

    protected $table = 'stage_reports';

    protected $fillable = [
        'text',
        'status',
        'document_id',
        'stage_list_id',
        'user_id'
    ];

}
