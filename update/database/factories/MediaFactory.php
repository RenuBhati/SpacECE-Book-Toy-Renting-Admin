<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Media::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => 'image',
            'name' => $this->faker->word,
            'src' => 'images/dummy/dummy-placeholder.png',
            'extention' => 'png',
            'description' => $this->faker->sentence,
            'path' => 'images/dummy/',
        ];
    }
}
