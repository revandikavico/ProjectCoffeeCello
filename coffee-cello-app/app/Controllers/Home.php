<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

class Home extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();

        // Ambil produk yang tersedia untuk pelanggan.
        $products = $productModel
            ->where('is_available', 1)
            ->orderBy('id', 'DESC')
            ->findAll();

        // Ambil kategori produk.
        $categories = $categoryModel->findAll();

        return view('home', [
            'products'   => $products,
            'categories' => $categories,
        ]);
    }
}
