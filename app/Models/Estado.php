<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estados';
    protected $fillable = [
      'nome',
      'sigla',
    ];

    /************************ Relationships ************************/

    public function cidades()
    {
        return $this->hasMany(Cidade::class)->orderBy('nome');
    }
}
