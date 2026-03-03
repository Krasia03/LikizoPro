<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLeaveTransactionsTable extends Migration
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
            'transaction_type' => [
                'type' => 'ENUM',
                'constraint' => ['ACCRUAL', 'SUBMISSION_PENDING', 'APPROVAL_USED', 'REJECTION_REVERSAL', 'CANCELLATION_REVERSAL', 'CARRY_FORWARD', 'EXPIRY', 'ADJUSTMENT', 'INITIAL'],
            ],
            'leave_request_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'days' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'balance_before' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'balance_after' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'reference_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'fiscal_year' => [
                'type' => 'YEAR',
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employee_id', 'employees', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('leave_type_id', 'leave_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('leave_request_id', 'leave_requests', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('processed_by', 'employees', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('leave_transactions');
    }

    public function down()
    {
        $this->forge->dropTable('leave_transactions');
    }
}
