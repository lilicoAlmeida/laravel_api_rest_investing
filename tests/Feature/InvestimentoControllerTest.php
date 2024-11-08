<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Investimento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestimentoControllerTest extends TestCase
{
    use RefreshDatabase;

    // Testa se a listagem de investimentos funciona corretamente
    public function test_can_list_investimentos()
    {
        $user = User::factory()->create();
        Investimento::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/investimentos');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'nome', 'saldo_atual', 'rentabilidade']
            ]);
    }

    // Testa se a criação de um investimento funciona corretamente
    public function test_can_create_investimento()
    {
        $user = User::factory()->create();

        $data = [
            'nome' => 'Ação XYZ',
            'saldo_atual' => 1500.50,
            'rentabilidade' => 8.5,
            'user_id' => $user->id
        ];

        $response = $this->actingAs($user)->postJson('/api/investimentos', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['nome' => 'Ação XYZ']);
    }

    // Testa se é possível exibir um investimento específico
    public function test_can_show_investimento()
    {
        $user = User::factory()->create();
        $investimento = Investimento::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/investimentos/' . $investimento->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['nome' => $investimento->nome]);
    }

    // Testa se é possível deletar um investimento
    public function test_can_delete_investimento()
    {
        $user = User::factory()->create();
        $investimento = Investimento::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson('/api/investimentos/' . $investimento->id);

        $response->assertStatus(204);
    }
}
