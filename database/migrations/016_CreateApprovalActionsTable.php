<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateApprovalActionsTable extends Migration
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
            'leave_request_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'approver_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'approval_level' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'action' => [
                'type' => 'ENUM',
                'constraint' => ['approved', 'rejected', 'returned', 'delegated'],
            ],
            'comments' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'delegated_from' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('leave_request_id', 'leave_requests', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approver_id', 'employees', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('delegated_from', 'employees', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('approval_actions');
    }

    public function down()
    {
        $this->forge->dropTable('approval_actions');
    }
}
