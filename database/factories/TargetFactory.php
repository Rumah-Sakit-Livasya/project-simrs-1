<?php

namespace Database\Factories;

use App\Models\Target;
use App\Models\User;
use App\Models\Organization;
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
        $minTarget = $this->faker->randomFloat(2, 0, 50);
        $target = $this->faker->randomFloat(2, 10, 100);
        $maxTarget = $this->faker->randomFloat(2, 50, 150);
        $actual = $this->faker->randomFloat(2, 0, 200); // Angka acak antara 0 dan 200

        return [
            'organization_id' => Organization::inRandomOrder()->value('id'),
            'user_id' => User::inRandomOrder()->value('id'),
            'title' => $this->faker->sentence,
            'actual' => $actual,
            'target' => $target,
            'min_target' => $minTarget,
            'max_target' => $maxTarget,
            'difference' => $actual - $target,
            'status' => $this->determineStatus($actual, $minTarget, $target, $maxTarget), // Menetapkan status
        ];
    }

    private function determineStatus($actual, $minTarget, $target, $maxTarget)
    {
        if ($actual == 0) {
            return 'Belum dikerjakan sama sekali';
        } elseif ($actual < $minTarget) {
            return 'Belum sesuai target';
        } elseif ($actual >= $minTarget && $actual < $target) {
            return 'Hampir mendekati target';
        } elseif ($actual >= $target) {
            return 'Sesuai target';
        } else {
            return 'Di luar rentang target';
        }
    }
}
