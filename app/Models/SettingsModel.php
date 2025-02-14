<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['key', 'value'];

    public function getSetting($key)
    {
        return $this->where('key', $key)->first()['value'] ?? null;
    }

    public function getAllSettings()
    {
        return $this->findAll();
    }

    public function getAllDeadlines()
    {
        return $this->where('key LIKE', 'deadline_%')->findAll();
    }
}
