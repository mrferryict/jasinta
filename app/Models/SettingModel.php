<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['key', 'value', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Ambil semua pengaturan dalam sistem
     */
    public function getAllSettings()
    {
        return $this->orderBy('key', 'ASC')->findAll();
    }

    /**
     * Ambil pengaturan berdasarkan key
     */
    public function getSetting($key)
    {
        return $this->where('key', $key)->first();
    }

    /**
     * Perbarui pengaturan berdasarkan key
     */
    public function updateSetting($key, $value)
    {
        $setting = $this->where('key', $key)->first();

        if ($setting) {
            return $this->update($setting['id'], ['value' => $value]);
        } else {
            return $this->insert(['key' => $key, 'value' => $value, 'created_at' => date('Y-m-d H:i:s')]);
        }
    }

    /**
     * Hapus pengaturan berdasarkan key
     */
    public function deleteSetting($key)
    {
        return $this->where('key', $key)->delete();
    }
}
