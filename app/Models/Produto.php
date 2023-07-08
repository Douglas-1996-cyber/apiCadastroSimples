<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;
    protected $fillable=['name','price','amount'];

    public function rules(){
        $regras = [
            'name'=>'required|unique:produtos,name,'.$this->id,
            'price'=>'required',
            'amount'=>'required'
        ];
        return $regras;
    }
    
    public function feedback(){
        $feedback=[
            'required'=>'O campo :attribute requerida',
            'name.unique'=>'Produto já existe'
        ];
        return $feedback; 
    }
}
