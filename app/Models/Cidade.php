<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    protected $table = 'cidades';
    public $timestamps = false;
    protected $fillable = [
        'nome',
        'estado_id',
    ];

    /************************ Relationships ************************/

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'cidade_id');
    }

    /************************ Methods ************************/
}
