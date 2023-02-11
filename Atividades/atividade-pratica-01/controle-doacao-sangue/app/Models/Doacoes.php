<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doacoes extends Model
{
    use HasFactory;
    
    protected $table = 'doacoes';
    protected $fillable = [
        'pessoa_id',
        'local_id',
        'data'
    ];

    public function local()
    {
        return $this->belongsTo(LocaisColeta::class, 'local_id');
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }
}
