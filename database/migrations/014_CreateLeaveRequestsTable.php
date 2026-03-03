<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLeaveRequestsTable extends Migration
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
            'employee_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'leave_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'policy_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'submitted', 'pending_l1', 'pending_l2', 'pending_l3', 'approved', 'rejected', 'cancelled', 'expired', 'returned'],
                'default' => 'draft',
            ],
            'start_date' => [
                'type' => 'DATE',
            ],
            'end_date' => [
                'type' => 'DATE',
            ],
            'start_half' => [
                'type' => 'ENUM',
                'constraint' => ['full', 'morning', 'afternoon'],
                'default' => 'full',
            ],
            'end_half' => [
                'type' => 'ENUM',
                'constraint' => ['full', 'morning', 'afternoon'],
                'default' => 'full',
            ],
            'total_days' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'emergency_contact' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'current_approval_level' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'rejection_reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'return_reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'submitted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'processed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'processed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
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
        $this->forge->addForeignKey('employee_id', 'employees', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('leave_type_id', 'leave_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('policy_id', 'leave_policies', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('processed_by', 'employees', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('leave_requests');
    }

    public function down()
    {
        $this->forge->dropTable('leave_requests');
    }
}
