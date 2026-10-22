<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogTipo extends Model
{
    public $table = 'log_tipos';
    protected $fillable = [
        'descricao'
    ];

    public static function boot()
    {        
        parent::boot();

        self::creating(function($model){
            $model->user_add = Auth()->User()->id;
        });

        self::updating(function($model){
            $model->user_upd = Auth()->User()->id;
        });
    }

    /************************ Relationships ************************/

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    

    /************************ Methods ************************/
}
