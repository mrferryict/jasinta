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
            if ('ADMIN' == session()->get('division')) {
                return redirect()->to('admin');
            } elseif ('LECTURER' == session()->get('division')) {
                return redirect()->to('lecturer');
            } elseif ('STUDENT' == session()->get('division')) {
                return redirect()->to('student');
            }
        }
        return view('landing/index');
    }
}
