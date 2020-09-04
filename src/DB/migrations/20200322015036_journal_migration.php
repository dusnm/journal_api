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
            ->addTimestampsWithTimezone()
            ->addForeignKey('user_id', 'users', 'id', [
                'update' => 'CASCADE',
                'delete' => 'CASCADE',
            ])
            ->create()
        ;
    }
}
