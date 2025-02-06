<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use App\Models\SettingsModel;

class AppConfig extends BaseConfig
{
    public array $settings = [];

    public function __construct()
    {
        $settingsModel = new SettingsModel();
        $settings = $settingsModel->getAllSettings();

        foreach ($settings as $setting) {
            $this->settings[$setting['key']] = $setting['value'];
        }
    }

    public function get($key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }
}
