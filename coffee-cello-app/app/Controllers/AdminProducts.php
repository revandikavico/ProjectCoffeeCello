<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

class AdminProducts extends BaseController
{
    private function checkAdmin()
    {
        if (session()->get('isAdmin') !== true) {
            return redirect()->to('/admin/login');
        }

        return null;
    }

    public function index()
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $products = (new ProductModel())
            ->orderBy('id', 'DESC')
            ->findAll();

        $categories = (new CategoryModel())->findAll();
        $categoryNames = [];

        foreach ($categories as $category) {
            $categoryNames[$category['id']] = $category['name'];
        }

        return view('admin/products/index', [
            'title' => 'Kelola Produk Coffee Cello',
            'products' => $products,
            'categories' => $categoryNames,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        return view('admin/products/form', [
            'title' => 'Tambah Produk',
            'product' => null,
            'categories' => (new CategoryModel())->findAll(),
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function store()
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $rules = [
            'category_id' => 'required|is_natural_no_zero',
            'name' => 'required|min_length[2]|max_length[150]',
            'description' => 'permit_empty|max_length[2000]',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'stock' => 'required|integer|greater_than_equal_to[0]',
            'image' => 'permit_empty|is_image[image]|max_size[image,2048]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $imageName = null;
        $image = $this->request->getFile('image');

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/products', $imageName);
        }

        helper('url');

        $name = trim($this->request->getPost('name'));
        $slug = url_title($name, '-', true);
        $model = new ProductModel();

        if ($model->where('slug', $slug)->first()) {
            $slug .= '-' . time();
        }

        $model->insert([
            'category_id' => $this->request->getPost('category_id'),
            'name' => $name,
            'slug' => $slug,
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'image' => $imageName,
            'stock' => $this->request->getPost('stock'),
            'is_available' => $this->request->getPost('is_available') ? 1 : 0,
        ]);

        return redirect()->to('/admin/products')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $product = (new ProductModel())->find($id);

        if (!$product) {
            return redirect()->to('/admin/products')
                ->with('error', 'Produk tidak ditemukan.');
        }

        return view('admin/products/form', [
            'title' => 'Edit Produk',
            'product' => $product,
            'categories' => (new CategoryModel())->findAll(),
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function update($id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $model = new ProductModel();
        $product = $model->find($id);

        if (!$product) {
            return redirect()->to('/admin/products')
                ->with('error', 'Produk tidak ditemukan.');
        }

        $rules = [
            'category_id' => 'required|is_natural_no_zero',
            'name' => 'required|min_length[2]|max_length[150]',
            'description' => 'permit_empty|max_length[2000]',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'stock' => 'required|integer|greater_than_equal_to[0]',
            'image' => 'permit_empty|is_image[image]|max_size[image,2048]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $imageName = $product['image'];
        $image = $this->request->getFile('image');

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/products', $imageName);

            if (!empty($product['image'])) {
                $oldPath = FCPATH . 'uploads/products/' . $product['image'];

                if (is_file($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        helper('url');

        $name = trim($this->request->getPost('name'));
        $slug = url_title($name, '-', true);
        $duplicate = $model->where('slug', $slug)->first();

        if ($duplicate && (int) $duplicate['id'] !== (int) $id) {
            $slug .= '-' . time();
        }

        $model->update($id, [
            'category_id' => $this->request->getPost('category_id'),
            'name' => $name,
            'slug' => $slug,
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'image' => $imageName,
            'stock' => $this->request->getPost('stock'),
            'is_available' => $this->request->getPost('is_available') ? 1 : 0,
        ]);

        return redirect()->to('/admin/products')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function delete($id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $model = new ProductModel();
        $product = $model->find($id);

        if (!$product) {
            return redirect()->to('/admin/products')
                ->with('error', 'Produk tidak ditemukan.');
        }

        if (!empty($product['image'])) {
            $imagePath = FCPATH . 'uploads/products/' . $product['image'];

            if (is_file($imagePath)) {
                unlink($imagePath);
            }
        }

        $model->delete($id);

        return redirect()->to('/admin/products')
            ->with('success', 'Produk berhasil dihapus.');
    }
}