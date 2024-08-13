<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Transaction;
use App\Models\Unit;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(),
            'tanggal_transaksi' => $this->faker->date(),
            'jenis_transaksi' => $this->faker->word(),
            'quantity' => $this->faker->numberBetween(-10000, 10000),
            'satuan' => $this->faker->word(),
            'harga_satuan' => $this->faker->numberBetween(-10000, 10000),
            'total' => $this->faker->numberBetween(-10000, 10000),
            'deskripsi' => $this->faker->text(),
        ];
    }
}
