<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Investimento;
use App\Models\Transacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransacaoControllerTest extends TestCase
{
    use RefreshDatabase;

    // Testa se a criação de uma transação funciona corretamente
    public function test_can_create_transacao()
    {
        $user = User::factory()->create();
        $investimento = Investimento::factory()->create(['user_id' => $user->id]);

        $data = [
            'investimento_id' => $investimento->id,
            'tipo' => 'compra',
            'quantidade' => 100.00,
            'data' => '2024-10-21'
        ];

        $response = $this->actingAs($user)->postJson('/api/transacoes', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['tipo' => 'compra']);
    }

    // Testa se é possível listar transações
    public function test_can_list_transacoes()
    {
        $user = User::factory()->create();
        $investimento = Investimento::factory()->create(['user_id' => $user->id]);

        Transacao::factory()->create([
            'investimento_id' => $investimento->id,
            'tipo' => 'compra',
            'quantidade' => 100.00,
            'data' => '2024-10-21'
        ]);

        $response = $this->actingAs($user)->getJson('/api/transacoes');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'investimento_id', 'tipo', 'quantidade', 'data']
            ]);
    }

    // Testa se é possível exibir uma transação específica
    public function test_can_show_transacao()
    {
        $user = User::factory()->create();
        $investimento = Investimento::factory()->create(['user_id' => $user->id]);
        $transacao = Transacao::factory()->create([
            'investimento_id' => $investimento->id,
            'tipo' => 'compra',
            'quantidade' => 100.00,
            'data' => '2024-10-21'
        ]);

        $response = $this->actingAs($user)->getJson('/api/transacoes/' . $transacao->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['tipo' => 'compra']);
    }

    // Testa se é possível atualizar uma transação
    public function test_can_update_transacao()
    {
        $user = User::factory()->create();
        $investimento = Investimento::factory()->create(['user_id' => $user->id]);
        $transacao = Transacao::factory()->create([
            'investimento_id' => $investimento->id,
            'tipo' => 'compra',
            'quantidade' => 100.00,
            'data' => '2024-10-21'
        ]);

        $data = [
            'tipo' => 'venda',
            'quantidade' => 150.00,
            'data' => '2024-10-22'
        ];

        $response = $this->actingAs($user)->putJson('/api/transacoes/' . $transacao->id, $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['tipo' => 'venda']);
    }

    // Testa se é possível deletar uma transação
    public function test_can_delete_transacao()
    {
        $user = User::factory()->create();
        $investimento = Investimento::factory()->create(['user_id' => $user->id]);
        $transacao = Transacao::factory()->create([
            'investimento_id' => $investimento->id,
            'tipo' => 'compra',
            'quantidade' => 100.00,
            'data' => '2024-10-21'
        ]);

        $response = $this->actingAs($user)->deleteJson('/api/transacoes/' . $transacao->id);

        $response->assertStatus(204);
    }
}
