<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLeavePoliciesTable extends Migration
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
            'leave_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'department_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'job_grade_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'location_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'employment_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'max_days_per_year' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'min_days_per_request' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 1,
            ],
            'max_days_per_request' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 30,
            ],
            'max_consecutive_days' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 30,
            ],
            'accrual_method' => [
                'type' => 'ENUM',
                'constraint' => ['monthly', 'yearly', 'pay_period', 'upfront'],
                'default' => 'monthly',
            ],
            'accrual_months' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 12,
            ],
            'pro_rata' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'carry_forward_enabled' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'carry_forward_max_days' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'carry_forward_expiry_months' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'notice_period_days' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'min_tenure_months' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'require_probation_completion' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'effective_from' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'effective_to' => [
                'type' => 'DATE',
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
        $this->forge->addForeignKey('leave_type_id', 'leave_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('department_id', 'departments', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('job_grade_id', 'job_grades', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('location_id', 'locations', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('leave_policies');
    }

    public function down()
    {
        $this->forge->dropTable('leave_policies');
    }
}
