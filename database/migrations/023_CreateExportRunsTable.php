<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExportRunsTable extends Migration
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
            'report_template_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'report_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'report_type' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'file_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'file_size' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'null' => true,
            ],
            'record_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'filters_applied' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'processing', 'completed', 'failed'],
                'default' => 'pending',
            ],
            'error_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'run_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('report_template_id', 'report_templates', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('run_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('export_runs');
    }

    public function down()
    {
        $this->forge->dropTable('export_runs');
    }
}
