<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;

class ClienteController extends Controller
{

        public function __construct(Cliente $cliente){
        $this->cliente = $cliente;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clienteRepository = new MarcaRepository ($this->cliente);


        if($request->has('atributos_modelos')) {
            $atributos_modelos = 'modelos:id,' .$request->atributos_modelos;
            $clienteRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        } else {
            $clienteRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        if($request->has('filtro')){
            $clienteRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $clienteRepository->selectAtributos($request->atributos);
        }

        return response()->json($clienteRepository->getResultado(), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClienteRequest $request)
    {
        $request->validate($this->cliente->rules(), $this->cliente->feedback());


        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');

        $cliente = $this->cliente->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);
        
        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente, $id)
    {
        $cliente = $this->cliente->with('modelos')->find($id);
        if($cliente === null) {
            return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404) ;        
        }
        return response()->json($cliente, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente, $id)
    {
        $cliente = $this->cliente->find($id);
        if($cliente === null) {
            return response()->json(['erro' => 'Impossivel relaziar a atualizaçao. Recurso nao encrotado'],404);
        }

        if($request->method() === 'PATCH'){

            $regrasDinamicas = array();
        
            foreach($cliente->rules() as $input => $regra){

                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas->rules(), $cliente->feedback());
        }
        else{

            $request->validate($cliente->rules(), $cliente->feedback());
        }

        if($request->file('imagem')) {
            Storage::disk('public')->delete($cliente->imagem);
        }
        
        $cliente->fill($request->all());
        $cliente->save();

       return response()->json($cliente, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente, $id)
    {
         $cliente = $this->cliente->find($id);

        if($cliente === null){
            return response()->json(['erro' => 'Voce nao pode apagar um registro q nao existe meu caro'],404);
        }

            Storage::disk('public')->delete($cliente->imagem);

        
        $cliente->delete();
        return response()->json(['msg' => 'A marca foi removida com sucesso'], 200);
    }
}
