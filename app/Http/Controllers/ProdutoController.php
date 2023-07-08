<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function __construct(Produto $produto){
        $this->produto = $produto;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->search == NULL){
        $produtos = $this->produto->paginate(10);
        return response()->json($produtos,200);
        }else{
        $busca = $this->produto->where('name','like','%'.$request->search.'%')->paginate(10);
        return response()->json($busca,200);
        }
     
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
  
        $request->validate($this->produto->rules(),$this->produto->feedback());

        $produto = $this->produto->create($request->all());

        return response()->json($produto,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produto = $this->produto->find($id);
        if($produto===null){
            return response()->json(['erro'=>'Registro não encontrado'],404);
        }
        return response()->json($produto,200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $produto = $this->produto->find($id);
   
        if($produto===null){
            return response()->json(['erro'=>'Impossivel atualizar. Registro não encontrado'],404);
        }
        if($request->method()==="PATCH"){
         $regrasDinamicas = array();
         foreach($produto->rules() as $input => $regra){
           if(array_key_exists($input,$request->all())){
            $regrasDinamicas[$input] = $regra;
           }
         }
         $request->validate($regrasDinamicas,$produto->feedback());
        }else{
        $request->validate($produto->rules(),$produto->feedback());
        }
        $produto->update($request->all());
        return response()->json($produto,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     $produto = $this->produto->find($id);
     if($produto===null){
        return response()->json(['erro'=>'Impossivel excluir. Registro não encontrado'],404);
    }
     $produto->delete();
     return response()->json(['msg'=>'Produto excluido'],200);
    }
    public function destroyRecords($ids){
        $success = [];
        $erros = [];
        $ids = explode(";",$ids);
        $tamanho = count($ids);
        for($i = 0; $i < $tamanho; $i++) {
          $produto = $this->produto->find($ids[$i]);
          if($produto===null){
            array_push($erros,$ids[$i]);  
          }else{
           $produto->delete(); 
           array_push($success,$ids[$i]);
          }
        }
        return [
                'excluidos'=>$success, 
                'nao encontrados'=>$erros
               ];
    }


}
