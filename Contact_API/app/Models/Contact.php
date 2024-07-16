<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use HasFactory;
    public $fillable = ['title'];
    public function emails():hasMany
    {
        return $this->hasMany(Email::class,'contact_id','id');
    }
    public function phones():hasMany
    {
        return $this->hasMany(Phone::class,'contact_id','id');
    }
}
