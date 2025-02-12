<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
   protected $table = 'announcements';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'title',
      'content',
      'created_by',
      'created_at',
      'updated_at',
      'deleted_at'
   ];
   protected $useTimestamps = true;
   protected $useSoftDeletes = true;
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';
   protected $deletedField = 'deleted_at';
}
