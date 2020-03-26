<?php

use Phinx\Migration\AbstractMigration;

class JournalMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('journals');

        $table->addColumn('name', 'string', ['length' => 50])
            ->addColumn('body', 'text')
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('user_id', 'users', 'id', [
                'update' => 'CASCADE',
                'delete' => 'CASCADE',
            ])
            ->create()
        ;
    }
}
