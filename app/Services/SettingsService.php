<?php

namespace App\Services;

use App\Models\SettingsModel;

class SettingsService
{
   protected $settingsModel;

   public function __construct()
   {
      $this->settingsModel = new SettingsModel();
   }

   public function getSettingsAsArray()
   {
      // Mengambil semua data settings
      $settings = $this->settingsModel->findAll();

      // Mengonversi data ke dalam array dengan key => value
      $settingsArray = [];
      foreach ($settings as $setting) {
         $settingsArray[$setting['key']] = $setting['value'];
      }

      return $settingsArray;
   }
}
