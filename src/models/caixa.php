<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caixa extends Model
{
    protected $table = 'caixas';
    
    protected $fillable = [
        'tipo',
        'valor',
        'status'
    ];
}
