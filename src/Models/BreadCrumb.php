<?php

namespace Rdmarwein\FormApi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreadCrumb extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function BreadCrumbDetail()
    {
       return $this->hasMany(BreadCrumDetail::class);
    }

    public function FormMaster()
    {
        return $this->belongsTo(FormMaster::class);
    }
}
