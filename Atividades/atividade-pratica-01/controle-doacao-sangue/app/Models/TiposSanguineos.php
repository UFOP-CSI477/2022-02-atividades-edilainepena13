<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposSanguineos extends Model
{
    use HasFactory;
    
    protected $table = 'tipos_sanguineos';
    protected $fillable = [
        'tipo',
        'fator'
    ];

    public function pessoas()
    {
        return $this->hasMany(Pessoa::class, 'tipo_id');
    }
}
