<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Email;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Email>
 */
class emailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'contact_id' => Contact::factory()->create()->id,
            'email'=>Email::factory()->faker->email,
        ];
    }
}
