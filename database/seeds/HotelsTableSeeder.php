<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Faker\Factory as Faker;

class HotelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $placesId = App\Model\Place::all('id')->pluck('id')->toarray();
        $faker = Faker::create();
        for ($i = 0; $i < 10; $i++) {
            factory(App\Model\Hotel::class, 1)->create([
                'place_id' => $faker->randomElement($placesId)
            ]);
        }
        SlugService::createSlug(App\Model\Hotel::class, 'slug', 'My First Hotel');
        Model::reguard();
    }
}
