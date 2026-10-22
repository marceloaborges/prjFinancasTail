<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public $table = 'logs';
    protected $fillable = [
        'user_id',
        'log_tipo_id',
        'acao',
        'metodo',
        'descricao',
        'tabela',
        'registro_id',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    /************************ Relationships ************************/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tipo()
    {
        return $this->belongsTo(LogTipo::class, 'log_tipo_id');
    }

    /************************ Methods ************************/

    public function scopeSearch($query, $filter = NULL)
    {
        $data = self::query()->with(['user','tipo'])
        ->when(isset($filter['user_ids']), function($query) use ($filter){
            $query->whereIn('user_id', $filter['user_ids']);
        })
        ->when(isset($filter['log_tipo_ids']), function($query) use ($filter){
            $query->whereIn('log_tipo_id', $filter['log_tipo_ids']);
        })
        ->when(!empty($filter['tabela']), function($q) use ($filter) {
            $q->where('tabela', 'LIKE', '%' . trim($filter['tabela']) . '%');
        })
        ->when(!empty($filter['descricao']), function($q) use ($filter) {
            $q->where('descricao', 'LIKE', '%' . trim($filter['descricao']) . '%');
        })
        ->latest();

        return $data;
    }
}
