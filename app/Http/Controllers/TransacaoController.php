<?php

namespace App\Http\Controllers;

use App\Models\Transacao;
use Illuminate\Http\Request;

class TransacaoController extends Controller
{
    // Lista todas as transações
    public function index()
    {
        try {
            $transacoes = Transacao::all();
            return response()->json($transacoes, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao listar transações: ' . $e->getMessage()], 500);
        }
    }

    // Cria uma nova transação
    public function store(Request $request)
    {
        try {
            // Valida os dados da requisição
            $validatedData = $request->validate([
                'investimento_id' => 'required|exists:investimentos,id',
                'tipo' => 'required|string',
                'quantidade' => 'required|numeric',
                'data' => 'required|date',
            ]);

            // Cria a transação
            $transacao = Transacao::create($validatedData);

            // Retorna a transação criada
            return response()->json($transacao, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar transação: ' . $e->getMessage()], 500);
        }
    }

    // Exibe uma transação específica
    public function show($id)
    {
        try {
            $transacao = Transacao::findOrFail($id);
            return response()->json($transacao, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Transação não encontrada.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao exibir transação: ' . $e->getMessage()], 500);
        }
    }

    // Atualiza uma transação existente
    public function update(Request $request, $id)
    {
        try {
            // Valida os dados da requisição
            $validatedData = $request->validate([
                'investimento_id' => 'exists:investimentos,id',
                'tipo' => 'string',
                'quantidade' => 'numeric',
                'data' => 'date',
            ]);

            // Encontra a transação ou retorna um erro 404 se não encontrada
            $transacao = Transacao::findOrFail($id);

            // Atualiza os dados da transação
            $transacao->update($validatedData);

            // Retorna a transação atualizada
            return response()->json($transacao, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Transação não encontrada.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar transação: ' . $e->getMessage()], 500);
        }
    }

    // Deleta uma transação
    public function destroy($id)
    {
        try {
            $transacao = Transacao::findOrFail($id);
            $transacao->delete();

            return response()->json(null, 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Transação não encontrada.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar transação: ' . $e->getMessage()], 500);
        }
    }
}
