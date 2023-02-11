<?php

namespace App\Http\Controllers;

use App\Models\TiposSanguineos;
use Illuminate\Http\Request;

class TiposSanguineosController extends Controller
{

    private function retornaRespostaJsonTipoNaoEncontrado()
    {
        return response()->json(['erro' => 'Tipo Sanguíneo não encontrado.'], 404);
    }

    public function index()
    {
        $tiposSanguineos = TiposSanguineos::all();

        if (count($tiposSanguineos) > 0) {
            return response()->json(['tiposSanguineos' => $tiposSanguineos], 200);
        } else {
            return response()->noContent();
        }
    }

    public function show(int $id)
    {
        $tipoSanguineo = TiposSanguineos::find($id);

        if (!is_null($tipoSanguineo)) {
            return response()->json(['tipoSanguineo' => $tipoSanguineo], 200);
        } else {
            return response()->json(['erro' => 'Tipo Sanguíneo não encontrado.'], 404);
        }
    }

    public function store(Request $request)
    {
        $regras = ['required', 'max:3'];
        $request->validate([
            'tipo' => $regras,
            'fator' => $regras
        ]);

        $tipoSanguineo = TiposSanguineos::create($request->only('tipo', 'fator'));
        return response()->json(['tipoSanguineo' => $tipoSanguineo], 201);
    }

    public function update(int $id, Request $request)
    {
        $regras = ['min:1', 'max:3'];
        $request->validate([
            'tipo' => $regras,
            'fator' => $regras
        ]);

        $tipoSanguineo = TiposSanguineos::find($id);

        if (!is_null($tipoSanguineo)) {
            $tipoSanguineo->fill($request->only('tipo', 'fator'));
            $tipoSanguineo->save();
            
            return $tipoSanguineo;
        } else {
            return $this->retornaRespostaJsonTipoNaoEncontrado();
        }
    }

    public function destroy(int $id)
    {
        $qtdTipoExcluido = TiposSanguineos::destroy($id);

        if ($qtdTipoExcluido > 0) {
            return response()->json(['sucesso' => 'Tipo Sanguíneo excluído com sucesso!'], 200);
        } else {
           return $this->retornaRespostaJsonTipoNaoEncontrado();
        }
    }

}
