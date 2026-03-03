<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLeaveRequestDaysTable extends Migration
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
            'date' => [
                'type' => 'DATE',
            ],
            'day_type' => [
                'type' => 'ENUM',
                'constraint' => ['full', 'morning', 'afternoon', 'weekend', 'holiday'],
                'default' => 'full',
            ],
            'is_counted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'hours' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 8,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('leave_request_id', 'leave_requests', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('leave_request_days');
    }

    public function down()
    {
        $this->forge->dropTable('leave_request_days');
    }
}
