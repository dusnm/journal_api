<?php

use Phinx\Migration\AbstractMigration;

class ImageMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('images');

        $table->addColumn('url', 'string', ['length' => 150])
            ->addColumn('user_id', 'integer', ['null' => true])
            ->addColumn('journal_id', 'integer', ['null' => true])
            ->addTimestampsWithTimezone()
            ->addIndex('url', ['unique' => true])
            ->addForeignKey('user_id', 'users', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->addForeignKey('journal_id', 'journals', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
            ->create()
        ;
    }
}
