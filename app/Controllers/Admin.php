<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PersonModel;
use App\Models\UserModel;
use App\Models\AnnouncementModel;
use App\Models\MessageModel;
use App\Models\ActivityLogModel;
use Config\Services;

class Admin extends Controller
{
   protected $settings;
   protected $session;
   protected $personModel;
   protected $userModel;
   protected $announcementModel;
   protected $messageModel;
   protected $activityLogModel;

   public function __construct()
   {
      $settingsService = Services::settingsService();
      $this->settings = $settingsService->getSettingsAsArray();
      $this->session = session();
      $this->personModel = new PersonModel();
      $this->userModel = new UserModel();
      $this->announcementModel = new AnnouncementModel();
      $this->messageModel = new MessageModel();
      $this->activityLogModel = new ActivityLogModel();
   }

   public function index()
   {
      $data = ['settings' => $this->settings, 'sessions' => $this->session];
      return view('admin/dashboard', $data);
   }

   // ===================== MASTER DATA =====================
   public function mahasiswa()
   {
      $data['mahasiswa'] = $this->personModel->where('division', 'mahasiswa')->findAll();
      return view('admin/mahasiswa', $data);
   }

   public function dosen()
   {
      $data['dosen'] = $this->personModel->where('division', 'dosen')->findAll();
      return view('admin/dosen', $data);
   }

   // ===================== PENGUMUMAN =====================
   public function pengumuman()
   {
      $data['pengumuman'] = $this->announcementModel->findAll();
      return view('admin/pengumuman', $data);
   }

   // ===================== PESAN =====================
   public function pesan()
   {
      $data['pesan'] = $this->messageModel->findAll();
      return view('admin/pesan', $data);
   }

   // ===================== PENDAFTARAN BIMBINGAN =====================
   public function pendaftaranBimbingan()
   {
      return view('admin/pendaftaran_bimbingan');
   }

   public function persyaratanPraBimbingan()
   {
      return view('admin/persyaratan_pra_bimbingan');
   }

   public function pengajuanProposal()
   {
      return view('admin/pengajuan_proposal');
   }

   public function skBimbingan()
   {
      return view('admin/sk_bimbingan');
   }

   // ===================== SIDANG DAN REVISI =====================
   public function persyaratanPraSidang()
   {
      return view('admin/persyaratan_pra_sidang');
   }

   public function sidang()
   {
      return view('admin/sidang');
   }

   public function revisiAkhir()
   {
      return view('admin/revisi_akhir');
   }

   public function persyaratanPascaSidang()
   {
      return view('admin/persyaratan_pasca_sidang');
   }

   // ===================== SURAT KETERANGAN LULUS =====================
   public function suratKeteranganLulus()
   {
      return view('admin/surat_keterangan_lulus');
   }

   // ===================== AKTIVITAS PENGGUNA (AUDIT TRAILS) =====================
   public function aktivitasPengguna()
   {
      $data['logs'] = $this->activityLogModel->orderBy('created_at', 'DESC')->findAll();
      return view('admin/aktivitas_pengguna', $data);
   }
}
