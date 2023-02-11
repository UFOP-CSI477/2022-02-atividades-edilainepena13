<?php

namespace App\Http\Controllers;

use App\Models\Doacoes;
use App\Models\LocaisColeta;
use App\Models\Pessoa;
use Illuminate\Http\Request;

class DoacoesController extends Controller
{
    private function retornaRespostaJsonCampoNaoEncontrado(string $campo)
    {
        return response()->json(['erro' => $campo . ' não encontrada(o).'], 404);
    }

    private function localExiste(int $idLocal)
    {
        return is_null(LocaisColeta::find($idLocal)) ? false: true;
    }

    private function pessoaExiste(int $idPessoa)
    {
        return is_null(Pessoa::find($idPessoa)) ? false: true;
    }

    private function retornaStringCampoNaoEncontrado($localExiste, $pessoaExiste)
    {
        if (!$localExiste && !$pessoaExiste) {
            return 'Local coleta e Pessoa';
        } elseif (!$localExiste) {
            return 'Local coleta';
        } else {
            return 'Pessoa';
        }
    }

    public function index()
    {
        $doacoes = Doacoes::all();

        if (count($doacoes) > 0) {
            return response()->json(['doacoes' => $doacoes], 200);
        } else {
            return response()->noContent();
        }
    }

    public function show(int $id)
    {
        $doacao = Doacoes::find($id);

        if (!is_null($doacao)) {
            return response()->json(['doacao' => $doacao], 200);
        } else {
            return $this->retornaRespostaJsonCampoNaoEncontrado('Doação');
        }
    }

    public function store(Request $request)
    {
        $regras = 'required';
        $request->validate([
            'pessoa_id' => $regras . '|integer',
            'local_id' => $regras . '|integer',
            'data' => $regras . '|date'
        ]);

        $localExiste = $this->localExiste($request->local_id);
        $pessoaExiste = $this->pessoaExiste($request->pessoa_id);

        if ($localExiste && $pessoaExiste) {
            $doacao = Doacoes::create($request->only('pessoa_id', 'local_id', 'data'));
            return response()->json(['doacao' => $doacao], 201);
        } else {
            return $this->retornaRespostaJsonCampoNaoEncontrado(
                $this->retornaStringCampoNaoEncontrado($localExiste, $pessoaExiste)
            );
        }
    }

    public function update(int $id, Request $request)
    {
        $request->validate([
            'pessoa_id' => 'integer',
            'local_id' => 'integer',
            'data' => 'date'
        ]);

        $localExiste = true;
        $pessoaExiste = true;

        if (isset($request->local_id)) {
            $localExiste = $this->localExiste($request->local_id);
        }

        if (isset($request->pessoa_id)) {
            $pessoaExiste = $this->pessoaExiste($request->pessoa_id);
        }

        $doacao = Doacoes::find($id);
        if (!is_null($doacao) && $localExiste && $pessoaExiste) {
            $doacao->fill($request->only('pessoa_id', 'local_id', 'data'));
            $doacao->save();
            
            return $doacao;
        } else {
            return $this->retornaRespostaJsonCampoNaoEncontrado(
                $this->retornaStringCampoNaoEncontrado($localExiste, $pessoaExiste)
            );
        }
    }

    public function destroy(int $id)
    {
        $qtdDoacoesExcluidas = Doacoes::destroy($id);

        if ($qtdDoacoesExcluidas > 0) {
            return response()->json(['sucesso' => 'Doação excluída com sucesso!'], 200);
        } else {
           return $this->retornaRespostaJsonCampoNaoEncontrado('Doação');
        }
    }
}
