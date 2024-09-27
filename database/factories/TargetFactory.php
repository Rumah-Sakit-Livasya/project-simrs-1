<?php

namespace Database\Factories;

use App\Models\Target;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Target>
 */
class TargetFactory extends Factory
{
    protected $model = Target::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $baseline_data = $this->faker->randomNumber(2, false); // Generate a random integer
        $target = $this->faker->numberBetween($baseline_data + 1, 100); // Target must be greater than baseline_data
        $actual = $this->faker->numberBetween($baseline_data + 1, $target); // Actual must be greater than baseline_data
        $bulan = $this->faker->numberBetween(1, 12);
        $satuan = $this->faker->randomElement(['persen', 'baku']);
        $organisasi = $this->faker->randomElement(['10', '11', '13', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '28', '32', '33', '40', '42', '44', '45']);

        // Calculate movement and percentage
        $movement = (($actual - $baseline_data) / $target) * 100;
        $persentase = ($baseline_data > 0) ? ($actual  / $target) * 100 : 0; // Avoid division by zero

        return [
            'organization_id' => $organisasi,
            'user_id' => User::inRandomOrder()->value('id'),
            'title' => $this->faker->sentence,
            'status' => $this->determineStatus($persentase), // Determine status based on percentage
            'baseline_data' => $baseline_data,
            'actual' => $actual,
            'target' => $target,
            'movement' => $movement,
            'persentase' => $persentase,
            'pic' => User::inRandomOrder()->value('id'),
            'satuan' => $satuan,
            'bulan' => 1,
        ];
    }

    private function determineStatus($persentase)
    {
        if ($persentase >= 100) {
            return 'green';
        } elseif ($persentase >= 60) {
            return 'blue';
        } elseif ($persentase >= 30) {
            return 'yellow';
        } else {
            return 'red';
        }
    }
}
