<?php

namespace App\Models;

use CodeIgniter\Model;

class ThesisModel extends Model
{
   protected $table = 'thesis';
   protected $primaryKey = 'id';
   protected $allowedFields = ['student_id', 'title', 'stage', 'created_at'];
   protected $useTimestamps = true;
}
