<?php

namespace App\Http\Controllers;

use App\Models\Cidades;
use App\Models\Pessoa;
use App\Models\TiposSanguineos;
use Illuminate\Http\Request;

class PessoaController extends Controller
{
    private function retornaRespostaJsonCampoNaoEncontrado(string $campo)
    {
        return response()->json(['erro' => $campo . ' não encontrada(o).'], 404);
    }

    private function cidadeExiste(int $idCidade)
    {
        return is_null(Cidades::find($idCidade)) ? false: true;
    }

    private function tipoSanguineoExiste(int $idTipoSanguineo)
    {
        return is_null(TiposSanguineos::find($idTipoSanguineo)) ? false: true;
    }

    private function retornaStringCampoNaoEncontrado($cidadeExiste, $tipoSanguineoExiste)
    {
        if (!$cidadeExiste && !$tipoSanguineoExiste) {
            return 'Cidade e Tipo Sanguíneo';
        } elseif (!$cidadeExiste) {
            return 'Cidade';
        } else {
            return 'Tipo Sanguíneo';
        }
    }

    public function index()
    {
        $pessoas = Pessoa::all();

        if (count($pessoas) > 0) {
            return response()->json(['pessoas' => $pessoas], 200);
        } else {
            return response()->noContent();
        }
    }

    public function show(int $id)
    {
        $pessoa = Pessoa::find($id);

        if (!is_null($pessoa)) {
            return response()->json(['pessoa' => $pessoa], 200);
        } else {
            return $this->retornaRespostaJsonCampoNaoEncontrado('Pessoa');
        }
    }

    public function showPorNome(string $nome)
    {
        $pessoas = Pessoa::query()->where('nome', '=', $nome)->get();

        if (count($pessoas) > 0) {
            return response()->json(['$pessoas' => $pessoas], 200);
        } else {
            return $this->retornaRespostaJsonCampoNaoEncontrado('Pessoa');
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
            'documento' => $regras,
            'cidade_id' => $regras . '|integer',
            'tipo_id' => $regras . '|integer'
        ]);

        $cidadeExiste = $this->cidadeExiste($request->cidade_id);
        $tipoSanguineoExiste = $this->tipoSanguineoExiste($request->tipo_id);

        if ($cidadeExiste && $tipoSanguineoExiste) {
            $pessoa = Pessoa::create(
                $request->only('nome', 'rua', 'numero', 'complemento', 'documento', 'cidade_id', 'tipo_id')
            );
            return response()->json(['pessoa' => $pessoa], 201);
        } else {
            return $this->retornaRespostaJsonCampoNaoEncontrado(
                $this->retornaStringCampoNaoEncontrado($cidadeExiste, $tipoSanguineoExiste)
            );
        }
    }

    public function update(int $id, Request $request)
    {
        $request->validate([
            'nome' => 'min:2',
            'cidade_id' => 'integer',
            'tipo_id' => 'integer'
        ]);

        $cidadeExiste = true;
        $tipoSanguineoExiste = true;

        if (isset($request->cidade_id)) {
            $cidadeExiste = $this->cidadeExiste($request->cidade_id);
        }

        if (isset($request->tipo_id)) {
            $tipoSanguineoExiste = $this->tipoSanguineoExiste($request->tipo_id);
        }

        $pessoa = Pessoa::find($id);
        if (!is_null($pessoa) && $cidadeExiste && $tipoSanguineoExiste) {
            $pessoa->fill(
                $request->only('nome', 'rua', 'numero', 'complemento', 'documento', 'cidade_id', 'tipo_id')
            );
            $pessoa->save();
            
            return $pessoa;
        } else {
            return $this->retornaRespostaJsonCampoNaoEncontrado(
                $this->retornaStringCampoNaoEncontrado($cidadeExiste, $tipoSanguineoExiste)
            );
        }
    }

    public function destroy(int $id)
    {
        $qtdPessoasExcluidas = Pessoa::destroy($id);

        if ($qtdPessoasExcluidas > 0) {
            return response()->json(['sucesso' => 'Pessoa excluída com sucesso!'], 200);
        } else {
           return $this->retornaRespostaJsonCampoNaoEncontrado('Pessoa');
        }
    }
}
