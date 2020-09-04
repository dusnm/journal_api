<?php

use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class JournalSeeder extends AbstractSeed
{
    public function run()
    {
        $faker = Factory::create();

        $journals = [];

        for ($i = 0; $i < 500; $i++) {
            $journals[] = [
                'name' => implode(" ", $faker->words()),
                'body' => implode(" ", $faker->paragraphs(5)),
                'user_id' => $faker->numberBetween(1, 500)
            ];
        }

        $table = $this->table('journals');
        $table->insert($journals)->saveData();
    }
}
