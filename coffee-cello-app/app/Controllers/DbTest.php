<?php

namespace App\Controllers;

class DbTest extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $categories = $db->table('categories')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($categories);
    }
}
