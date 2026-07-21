<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocacaoRequest;
use App\Http\Requests\UpdateLocacaoRequest;
use App\Models\Locacao;

class LocacaoController extends Controller
{

        public function __construct(Locacao $locacao){
        $this->locacao = $locacao;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository ($this->locacao);


        if($request->has('atributos_modelos')) {
            $atributos_modelos = 'modelos:id,' .$request->atributos_modelos;
            $locacaoRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        } else {
            $locacaoRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        if($request->has('filtro')){
            $locacaoRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $locacaoRepository->selectAtributos($request->atributos);
        }

        return response()->json($locacaoRepository->getResultado(), 200);
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
    public function store(StoreLocacaoRequest $request)
    {
        $request->validate($this->locacao->rules());

        $locacao = $this->locacao->create([
            'cliente_id' => $request->cliente_id,
            'carro_id' => $request->carro_id,
            'data_inicio_periodo' => $request->data_inicio_periodo,
            'data_final_previsto_periodo' => $request->data_final_previsto_periodo,
            'data_final_realizado_periodo' => $request->data_final_realizado_periodo,
            'valor_diaria' => $request->valor_diaria,
            'km_inicial' => $request->km_inicial,
            'km_final'  => $request->km_final
        ]);
        
        return response()->json($locacao, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Locacao $locacao, $id)
    {
        $locacao = $this->locacao->with('modelos')->find($id);
        if($locacao === null) {
            return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404) ;        
        }
        return response()->json($locacao, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Locacao $locacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLocacaoRequest $request, Locacao $locacao, $id)
    {
        $locacao = $this->locacao->find($id);
        if($locacao === null) {
            return response()->json(['erro' => 'Impossivel relaziar a atualizaçao. Recurso nao encrotado'],404);
        }

        if($request->method() === 'PATCH'){

            $regrasDinamicas = array();
        
            foreach($locacao->rules() as $input => $regra){

                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas->rules(), $locacao->feedback());
        }
        else{

            $request->validate($locacao->rules(), $locacao->feedback());
        }

        if($request->file('imagem')) {
            Storage::disk('public')->delete($locacao->imagem);
        }
        
        $locacao->fill($request->all());
        $locacao->save();

       return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Locacao $locacao, $id)
    {
        $locacao = $this->locacao->find($id);

        if($locacao === null){
            return response()->json(['erro' => 'Voce nao pode apagar um registro q nao existe meu caro'],404);
        }

            Storage::disk('public')->delete($locacao->imagem);

        
        $locacao->delete();
        return response()->json(['msg' => 'A marca foi removida com sucesso'], 200);
    }
}
