<?php

namespace App\Controllers;

class Home extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }
    public function index()
    {
        if (session()->has('isLoggedIn')) {
            // Cek peran pengguna dan arahkan sesuai
            if (in_array('ADMINISTRATOR', session()->get('roles'))) {
                return redirect()->to('admin');
            } elseif (in_array('LECTURER', session()->get('roles'))) {
                return redirect()->to('lecturer');
            } elseif (in_array('STUDENT', session()->get('roles'))) {
                return redirect()->to('student');
            }
        }
        return view('landing/index');
    }
}
