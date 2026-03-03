<?php

namespace App\Models;

use CodeIgniter\Model;

class AttachmentModel extends BaseModel
{
    protected $table = 'attachments';
    
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'leave_request_id', 'employee_id', 'file_name', 'original_name', 
        'file_type', 'file_size', 'file_path', 'uploaded_by'
    ];
}
