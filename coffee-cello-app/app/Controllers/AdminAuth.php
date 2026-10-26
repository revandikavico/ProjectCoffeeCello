<?php

namespace App\Controllers;

use App\Models\UserModel;

class AdminAuth extends BaseController
{
    public function login()
    {
        if (session()->get('isAdmin') === true) {
            return redirect()->to('/admin/products');
        }

        if ($this->request->getMethod() === 'POST') {
            $email = trim((string) $this->request->getPost('email'));
            $password = (string) $this->request->getPost('password');

            $userModel = new UserModel();

            $user = $userModel
                ->where('email', $email)
                ->where('is_active', 1)
                ->first();

            if (
                $user &&
                $user['role'] === 'admin' &&
                password_verify($password, $user['password'])
            ) {
                session()->regenerate();

                session()->set([
                    'isAdmin' => true,
                    'adminId' => $user['id'],
                    'adminName' => $user['name'],
                ]);

                return redirect()->to('/admin/products');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau password tidak sesuai.');
        }

        return view('admin/login');
    }

    public function logout()
    {
        session()->remove([
            'isAdmin',
            'adminId',
            'adminName',
        ]);

        session()->regenerate();

        return redirect()->to('/admin/login');
    }
}
