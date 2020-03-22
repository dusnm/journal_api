<?php

use Phinx\Migration\AbstractMigration;

class UserMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('users');

        $table->addColumn('first_name', 'string', ['length' => 25])
            ->addColumn('last_name', 'string', ['length' => 25])
            ->addColumn('email', 'string', ['length' => 50])
            ->addColumn('password', 'string', ['length' => 100])
            ->addColumn('verified', 'boolean', ['default' => false])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addIndex('email', ['unique' => true])
            ->create()
        ;
    }
}
