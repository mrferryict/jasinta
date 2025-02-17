<?php

namespace App\Models;

use CodeIgniter\Model;

class StageModel extends Model
{
   protected $table = 'stages';
   protected $primaryKey = 'id';
   protected $allowedFields = ['name', 'route'];

   public function getAllStages()
   {
      return $this->select('name, route')->asArray()->findAll();
   }

   public function getStageNames()
   {
      return array_column($this->select('name')->asArray()->findAll(), 'name');
   }

   public function getStageRoutes()
   {
      return array_column($this->select('route')->asArray()->findAll(), 'route');
   }
}
