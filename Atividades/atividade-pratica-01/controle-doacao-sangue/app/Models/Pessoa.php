<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'rua',
        'numero',
        'complemento',
        'documento',
        'cidade_id',
        'tipo_id'
    ];

    public function tipoSanguineo()
    {
        return $this->belongsTo(TiposSanguineos::class, 'tipo_id');
    }

    public function cidade()
    {
        return $this->belongsTo(Cidades::class, 'cidade_id');
    }
}
