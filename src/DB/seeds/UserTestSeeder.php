<?php
use Phinx\Seed\AbstractSeed;

class UserTestSeeder extends AbstractSeed
{
    public function run()
    {
        $table = $this->table('users');

        $users = [
            [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'janedoe@example.com',
                'password' => password_hash('janeiscool', PASSWORD_BCRYPT),
                'verified' => 1
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Stonefist',
                'email' => 'michaelstonefist@example.com',
                'password' => password_hash('toreadorsrule', PASSWORD_BCRYPT),
                'verified' => 0
            ]
        ];

        $table->insert($users)->saveData();
    }
}
