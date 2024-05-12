<?php

namespace Rdmarwein\FormApi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormMaster extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    public function excludedColumn()
    {
       return $this->hasMany(FormExcludeColumns::class);
    }

    public function foreignKey()
    {
       return $this->hasMany(FormForeignKey::class);
    }

    public function formDependent()
    {
       return $this->hasMany(FormDependantField::class)->select('id','master_key','foreign_key');
    }

    public function Role()
    {
        return $this->belongsTo(FormRole::class,'role','role');
    }
}
