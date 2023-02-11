<?php

namespace App\Http\Controllers;

use App\Models\Cidades;
use App\Models\LocaisColeta;
use Illuminate\Http\Request;

class LocaisColetaController extends Controller
{
    private function retornaRespostaJsonCampoNaoEncontrado(string $campo)
    {
        return response()->json(['erro' => $campo . ' não encontrada(o).'], 404);
    }

    private function cidadeExiste(int $idCidade)
    {
        return is_null(Cidades::find($idCidade)) ? false: true;
    }

    public function index()
    {
        $locaisColeta = LocaisColeta::all();

        if (count($locaisColeta) > 0) {
            return response()->json(['locaisColeta' => $locaisColeta], 200);
        } else {
            return response()->noContent();
        }
    }

    public function show(int $id)
    {
        $localColeta = LocaisColeta::find($id);

        if (!is_null($localColeta)) {
            return response()->json(['localColeta' => $localColeta], 200);
        } else {
            return $this->retornaRespostaJsonCampoNaoEncontrado('Local coleta');
        }
    }

    public function showPorNome(string $nome)
    {
        $locaisColeta = LocaisColeta::query()->where('nome', '=', $nome)->get();

        if (count($locaisColeta) > 0) {
            return response()->json(['locaisColeta' => $locaisColeta], 200);
        } else {
            return $this->retornaRespostaJsonCampoNaoEncontrado('Local coleta');
        }
    }

    public function store(Request $request)
    {
        $regras = 'required';
        $request->validate([
            'nome' => $regras . '|min:2',
            'rua' => $regras,
            'numero' => $regras,
            'complemento' => $regras,
            'cidade_id' => $regras . '|integer'
        ]);

        if ($this->cidadeExiste($request->cidade_id)) {
            $localColeta = LocaisColeta::create($request->only('nome', 'rua', 'numero', 'complemento', 'cidade_id'));
            return response()->json(['localColeta' =>$localColeta], 201);
        } else {
            return $this->retornaRespostaJsonCampoNaoEncontrado('Cidade');
        }
    }

    public function update(int $id, Request $request)
    {
        $request->validate([
            'nome' => 'min:2',
            'cidade_id' => 'integer'
        ]);

        $localColeta = LocaisColeta::find($id);

        if (isset($request->cidade_id)) {
            if (!$this->cidadeExiste($request->cidade_id)) {
                return $this->retornaRespostaJsonCampoNaoEncontrado('Cidade');
            }
        }

        if (!is_null($localColeta)) {
            $localColeta->fill($request->only('nome', 'rua', 'numero', 'complemento', 'cidade_id'));
            $localColeta->save();
            
            return $localColeta;
        } else {
            return $this->retornaRespostaJsonCampoNaoEncontrado('Local coleta');
        }
    }

    public function destroy(int $id)
    {
        $qtdLocaisColetaExcluidos = LocaisColeta::destroy($id);

        if ($qtdLocaisColetaExcluidos > 0) {
            return response()->json(['sucesso' => 'Local coleta excluído com sucesso!'], 200);
        } else {
           return $this->retornaRespostaJsonCampoNaoEncontrado('Local coleta');
        }
    }
}
