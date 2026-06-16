<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class locacoes extends Model
{
    /** @use HasFactory<\Database\Factories\LocacaoFactory> */
    use HasFactory;
    protected $table = 'locacoes'; 
}
