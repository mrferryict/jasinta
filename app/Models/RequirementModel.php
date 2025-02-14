<?php

namespace App\Models;

use CodeIgniter\Model;

class RequirementModel extends Model
{
   protected $table = 'requirements';
   protected $primaryKey = 'id';
   protected $allowedFields = ['stage', 'description', 'answer_type', 'approved_at', 'created_at'];
   protected $useTimestamps = true;
}
