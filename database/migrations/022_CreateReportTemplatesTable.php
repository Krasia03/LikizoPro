<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportTemplatesTable extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'report_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'dataset' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'fields' => [
                'type' => 'JSON',
            ],
            'filters' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'aggregations' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'sort_by' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'sort_order' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'default' => 'ASC',
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'is_shared' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
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
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('report_templates');
    }

    public function down()
    {
        $this->forge->dropTable('report_templates');
    }
}
