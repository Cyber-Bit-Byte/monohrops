<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $department = fake()->randomElement(Employee::DEPARTMENTS);

        return [
            'name' => fake()->name(),
            'position' => fake()->jobTitle(),
            'department' => $department,
            'hire_date' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'salary' => fake()->numberBetween(30000, 100000),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
