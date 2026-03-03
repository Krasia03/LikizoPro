<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDelegationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'delegator_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'delegate_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'start_date' => [
                'type' => 'DATE',
            ],
            'end_date' => [
                'type' => 'DATE',
            ],
            'leave_types' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'is_approved' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'approved_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('delegator_id', 'employees', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('delegate_id', 'employees', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'employees', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('delegations');
    }

    public function down()
    {
        $this->forge->dropTable('delegations');
    }
}
