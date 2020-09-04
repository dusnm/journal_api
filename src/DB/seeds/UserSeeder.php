<?php

use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    public function run()
    {
        $faker = Factory::create();
        $table = $this->table('users');

        $users = [];

        $users[0] = [
            'first_name' => getenv('TEST_USER_FIRST_NAME'),
            'last_name' => getenv('TEST_USER_LAST_NAME'),
            'email' => getenv('TEST_USER_EMAIL'),
            'password' => password_hash(getenv('TEST_USER_PASSWORD'), PASSWORD_BCRYPT),
            'verified' => 1,
        ];

        for ($i = 1; $i < 500; ++$i) {
            $users[] = [
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->unique()->email,
                'password' => password_hash($faker->password, PASSWORD_BCRYPT),
                'verified' => $faker->boolean(),
            ];
        }

        $table->insert($users)->saveData();
    }
}
