<?php

namespace Database\Factories;

use App\Models\Investimento;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvestimentoFactory extends Factory
{
    protected $model = Investimento::class;

    public function definition()
    {
        return [
            'nome' => $this->faker->word,
            'saldo_atual' => $this->faker->randomFloat(2, 1000, 10000),
            'rentabilidade' => $this->faker->randomFloat(2, 1, 10),
            'user_id' => \App\Models\User::factory(),
        ];
    }

    public function withTransacoes($count = 1)
    {
        return $this->has(\App\Models\Transacao::factory()->count($count), 'transacoes');
    }
}
