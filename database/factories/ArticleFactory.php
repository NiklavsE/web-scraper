<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'external_id'  => $this->faker->randomNumber(8),
            'title'        => $this->faker->name,
            'link'         => $this->faker->text,
            'points'       => $this->faker->randomNumber(3),
            'date_created' => $this->faker->dateTime,
        ];
    }
}
