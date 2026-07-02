<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\UrlGeneration\PublicUrlGenerator;

class Modelo extends Model
{

    use HasFactory;

    protected $fillable = ['marca_id','nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules() {
        return [
            'marca_id' => 'exists::marcas,id',
            'nome' => 'required|unique:modelos,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|interger|digits_betweeb:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',

        ];
    }    

   public function feedback() {
        return [
            'required' => 'O campo :attribute e obrigatorio',
            'imagem.mimes' => 'O arquivo deve ser uma imaegm do tipo PNG',
            'nome.unique' => 'O nome da marca nao pode ser igual',
            'nome.min' => 'O nome deve ter no minimo 3 caracteres'
        ];


    }
}
