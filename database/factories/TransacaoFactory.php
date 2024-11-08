<?php

namespace Database\Factories;

use App\Models\Transacao;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransacaoFactory extends Factory
{
    protected $model = Transacao::class;

    public function definition()
    {
        return [
            'investimento_id' => \App\Models\Investimento::factory(),
            'tipo' => $this->faker->randomElement(['compra', 'venda']),
            'quantidade' => $this->faker->randomFloat(2, 10, 1000),
            'data' => $this->faker->date(),
        ];
    }
}
