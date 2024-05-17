<?php

namespace Rdmarwein\FormApi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreadCrumbDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function BreadCrumb()
    {
        return $this->belongsTo(BreadCrumb::class);
    }

    public function FormMaster()
    {
        return $this->belongsTo(FormMaster::class);
    }
}
