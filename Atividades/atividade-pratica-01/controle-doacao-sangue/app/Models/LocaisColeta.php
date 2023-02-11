<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocaisColeta extends Model
{
    use HasFactory;
    
    protected $table = 'locais_coleta';
    protected $fillable = [
        'nome',
        'rua',
        'numero',
        'complemento',
        'cidade_id'
    ];

    public function cidade()
    {
        return $this->belongsTo(Cidades::class, 'cidade_id');
    }

    public function doacoes()
    {
        return $this->hasMany(Doacoes::class, 'local_id');
    }
}
