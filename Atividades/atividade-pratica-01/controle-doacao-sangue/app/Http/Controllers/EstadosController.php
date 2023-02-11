<?php

namespace App\Http\Controllers;

use App\Models\Estados;
use Illuminate\Http\Request;

class EstadosController extends Controller
{
    private function retornaRespostaJsonEstadoNaoEncontrado()
    {
        return response()->json(['erro' => 'Estado não encontrado.'], 404);
    }

    public function index()
    {
        $estados = Estados::all();

        if (count($estados) > 0) {
            return response()->json(['estados' => $estados], 200);
        } else {
            return response()->noContent();
        }
    }

    public function show(int $id)
    {
        $estado = Estados::find($id);

        if (!is_null($estado)) {
            return response()->json(['estado' => $estado], 200);
        } else {
            return $this->retornaRespostaJsonEstadoNaoEncontrado();
        }
    }

    public function showPorNome(string $nome)
    {
        $estados = Estados::query()->where('nome', '=', $nome)->get();

        if (!count($estados) > 0) {
            return response()->json(['estado' => $estados], 200);
        } else {
            return $this->retornaRespostaJsonEstadoNaoEncontrado();
        }
    }

    public function store(Request $request)
    {
        $regras = 'required';
        $request->validate([
            'nome' => $regras . '|min:5|max:50',
            'sigla' => $regras . '|size:2'
        ]);

        $estado = Estados::create($request->only('nome', 'sigla'));
        return response()->json(['estado' => $estado], 201);
    }

    public function update(int $id, Request $request)
    {
        $request->validate([
            'nome' => 'min:5|max:50',
            'sigla' =>  'size:2|alpha'
        ]);

        $estado = Estados::find($id);
        if (!is_null($estado)) {
            $estado->fill($request->only('nome', 'sigla'));
            $estado->save();
            
            return $estado;
        } else {
            return $this->retornaRespostaJsonEstadoNaoEncontrado();
        }
    }

    public function destroy(int $id)
    {
        $qtdEstadoExcluido = Estados::destroy($id);

        if ($qtdEstadoExcluido > 0) {
            return response()->json(['sucesso' => 'Estado excluído com sucesso!'], 200);
        } else {
           return $this->retornaRespostaJsonEstadoNaoEncontrado();
        }
    }
}
