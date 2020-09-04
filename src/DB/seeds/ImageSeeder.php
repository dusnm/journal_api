<?php

use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class ImageSeeder extends AbstractSeed
{
    public function run()
    {
        $faker = Factory::create();

        $userAvatars = [];
        $journalImages = [];

        for ($i = 0; $i < 500; $i++) {
            $userAvatars[] = [
                'url' => $faker->unique()->imageUrl(640, 480, 'cats'),
                'user_id' => $i + 1
            ];
            $journalImages[] = [
                'url' => $faker->unique()->imageUrl(640, 480, 'nature'),
                'journal_id' => $i + 1
            ];
        }

        $table = $this->table('images');
        $table->insert($userAvatars)->saveData();
        $table->insert($journalImages)->saveData();
    }
}
