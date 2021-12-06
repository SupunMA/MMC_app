<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    use HasFactory;

    protected $primaryKey = 'landID';
    protected $table = 'lands';

    protected $fillable = [
        'landAddress',
        'landMap',
        'landDetails',
        'landValue',
        'ownerID'
    ];
}