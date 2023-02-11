<?php

namespace App\Http\Controllers;

use App\Models\Cidades;
use App\Models\Estados;
use Illuminate\Http\Request;

class CidadesController extends Controller
{
    private function retornaRespostaJsonCampoNaoEncontrado(string $campo)
    {
        return response()->json(['erro' => $campo . ' não encontrada(o).'], 404);
    }

    private function estadoExiste(int $idEstado)
    {
        return is_null(Estados::find($idEstado)) ? false: true;
    }

    public function index()
    {
        $cidades = Cidades::all();

        if (count($cidades) > 0) {
            return response()->json(['cidades' => $cidades], 200);
        } else {
            return response()->noContent();
        }
    }

    public function show(int $id)
    {
        $cidade = Cidades::find($id);

        if (!is_null($cidade)) {
            return response()->json(['cidade' => $cidade], 200);
        } else {
            return $this->retornaRespostaJsonCampoNaoEncontrado('Cidade');
        }
    }

    public function showPorNome(string $nome)
    {
        $cidades = Cidades::query()->where('nome', '=', $nome)->get();

        if (count($cidades) > 0) {
            return response()->json(['cidade' => $cidades], 200);
        } else {
            return $this->retornaRespostaJsonCampoNaoEncontrado('Cidade');
        }
    }

    public function store(Request $request)
    {
        $regras = 'required';
        $request->validate([
            'nome' => $regras . '|min:2',
            'estado_id' => $regras . '|integer'
        ]);

        if ($this->estadoExiste($request->estado_id)) {
            $cidade = Cidades::create($request->only('nome', 'estado_id'));
            return response()->json(['cidade' => $cidade], 201);
        } else {
            return $this->retornaRespostaJsonCampoNaoEncontrado('Cidade');
        }
    }

    public function update(int $id, Request $request)
    {
        $request->validate([
            'nome' => 'min:3',
            'estado_id' => 'integer'
        ]);

        $cidade = Cidades::find($id);

        if (isset($request->estado_id)) {
            if (!$this->estadoExiste($request->estado_id)) {
                return $this->retornaRespostaJsonCampoNaoEncontrado('Estado');
            }
        }

        if (!is_null($cidade)) {
            $cidade->fill($request->only('nome', 'estado_id'));
            $cidade->save();
            
            return $cidade;
        } else {
            return $this->retornaRespostaJsonCampoNaoEncontrado('Cidade');
        }
    }

    public function destroy(int $id)
    {
        $qtdCidadesExcluidas = Cidades::destroy($id);

        if ($qtdCidadesExcluidas > 0) {
            return response()->json(['sucesso' => 'Cidade excluída com sucesso!'], 200);
        } else {
           return $this->retornaRespostaJsonCampoNaoEncontrado('Cidade');
        }
    }
}
