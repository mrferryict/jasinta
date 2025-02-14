<?php

namespace App\Models;

use CodeIgniter\Model;

class StageModel extends Model
{
   protected $table = 'stages';
   protected $primaryKey = 'id';
   protected $allowedFields = ['name'];
}
