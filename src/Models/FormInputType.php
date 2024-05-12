<?php

namespace Rdmarwein\FormApi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormInputType extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function InputType()
    {
       return $this->belongsTo(InputType::class,'input_type','input_type');
    }
}
