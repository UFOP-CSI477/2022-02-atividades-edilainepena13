<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cidades extends Model
{
    use HasFactory;
    
    protected $table = 'cidades';
    protected $fillable = [
        'nome',
        'estado_id'
    ];

    public function estado()
    {
        return $this->belongsTo(Estados::class, 'estado_id');
    }

    public function locaisColeta()
    {
        return $this->hasMany(LocaisColeta::class, 'cidade_id');
    }

    public function pessoas()
    {
        return $this->hasMany(Pessoa::class, 'cidade_id');
    }
}
