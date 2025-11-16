<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ministere extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'libelle',
        'description'
    ];

    public function agents()
    {
        return $this->hasMany(Agent::class, 'ministere_rattachement', 'libelle');
    }
}