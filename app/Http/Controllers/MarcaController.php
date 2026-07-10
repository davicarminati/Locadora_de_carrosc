<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function __construct(Marca $marca){
        $this->marca = $marca;
    }    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$marcas = Marca::all();
        $marca = $this->marca->with('modelos')->get();
        
        return response()->json($marca, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return 'create';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {  

        $request->validate($this->marca->rules(), $this->marca->feedback());


        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);
        
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);
        if($marca === null) {
            return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404) ;        
        }
        return response()->json($marca, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marca $marca)
    {
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

       $marca = $this->marca->find($id);
        if($marca === null) {
            return response()->json(['erro' => 'Impossivel relaziar a atualizaçao. Recurso nao encrotado'],404);
        }

        if($request->method() === 'PATCH'){

            $regrasDinamicas = array();
        
            foreach($marca->rules() as $input => $regra){

                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
            dd($regrasDinamicas);

            $request->validate($regrasDinamicas->rules(), $marca->feedback());
        }
        else{

            $request->validate($marca->rules(), $marca->feedback());
        }

        if($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
        }

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');
        
        $marca->fill($request->all());
        $marca->imagem = $imagem_urn;
        $marca->save();

        /*
        $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);*/

       return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if($marca === null){
            return response()->json(['erro' => 'Voce nao pode apagar um registro q nao existe meu caro'],404);
        }

            Storage::disk('public')->delete($marca->imagem);

        
        $marca->delete();
        return response()->json(['msg' => 'A marca foi removida com sucesso'], 200);
    }
}
