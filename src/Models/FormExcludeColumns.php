<?php

namespace Rdmarwein\FormApi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormExcludeColumns extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function FormMaster()
    {
        return $this->belongsTo(FormMaster::class);
    }
}
