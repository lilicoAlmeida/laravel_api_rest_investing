<?php

namespace App\Http\Controllers;

use App\Models\Investimento;
use Illuminate\Http\Request;

class InvestimentoController extends Controller
{
    public function index()
    {
        try {
            return Investimento::with('transacoes')->get();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao listar investimentos: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'saldo_atual' => 'required|numeric',
            'rentabilidade' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'saldo_atual.required' => 'O saldo atual é obrigatório e deve ser numérico.',
            'rentabilidade.required' => 'A rentabilidade é obrigatória e deve ser numérica.',
            'user_id.exists' => 'O usuário informado não foi encontrado.',
        ]);

        try {
            $investimento = Investimento::create($validatedData);
            return response()->json($investimento, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar o investimento: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $investimento = Investimento::with('transacoes')->findOrFail($id);
            return response()->json($investimento, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Investimento não encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao exibir investimento: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $investimento = Investimento::find($id);

            if (!$investimento) {
                return response()->json(['message' => 'Investimento não encontrado'], 404);
            }

            $validatedData = $request->validate([
                'nome' => 'sometimes|required|string|max:255',
                'saldo_atual' => 'sometimes|required|numeric',
                'rentabilidade' => 'sometimes|required|numeric',
            ]);

            $investimento->update($validatedData);

            return response()->json($investimento, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Investimento não encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar o investimento: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $investimento = Investimento::findOrFail($id);
            $investimento->delete();

            return response()->json(null, 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Investimento não encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar o investimento: ' . $e->getMessage()], 500);
        }
    }
}
