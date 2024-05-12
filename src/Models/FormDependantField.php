<?php

namespace Rdmarwein\FormApi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormDependantField extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function masterKey()
    {
       return $this->belongsTo(FormForeignKey::class,'master_key');
    }

    public function childKey()
    {
       return $this->belongsTo(FormForeignKey::class,'foreign_key');
    }

    public function formMaster()
    {
        return $this->belongsTo(FormMaster::class);
    }

    public function ForeignKey()
    {
         return $this->belongsTo(FormForeignKey::class,'foreign_key');
    }
}
