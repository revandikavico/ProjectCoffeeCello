<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;

class AdminOrders extends BaseController
{
    private function checkAdmin()
    {
        if (session()->get('isAdmin') !== true) {
            return redirect()->to('/admin/login');
        }

        return null;
    }

    // Menampilkan daftar pesanan
    public function index()
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $orders = (new OrderModel())
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('admin/orders/index', [
            'title' => 'Daftar Pesanan Coffee Cello',
            'orders' => $orders,
        ]);
    }

    // Memperbarui status pesanan
    public function updateStatus($id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $newStatus = (string) $this->request->getPost('status');

        $allowedStatuses = [
            'pending',
            'processing',
            'completed',
            'cancelled',
        ];

        if (!in_array($newStatus, $allowedStatuses, true)) {
            return redirect()->to('/admin/orders')
                ->with('error', 'Status pesanan tidak valid.');
        }

        $orderModel = new OrderModel();
        $order = $orderModel->find($id);

        if (!$order) {
            return redirect()->to('/admin/orders')
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        if ($orderModel->update($id, [
            'status' => $newStatus,
        ]) === false) {
            return redirect()->to('/admin/orders')
                ->with('error', 'Status pesanan gagal diperbarui.');
        }

        return redirect()->to('/admin/orders')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    // Menampilkan detail pesanan
    public function show($id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $orderModel = new OrderModel();
        $order = $orderModel->find($id);

        if (!$order) {
            return redirect()->to('/admin/orders')
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        $items = (new OrderItemModel())
            ->where('order_id', $id)
            ->findAll();

        return view('admin/orders/detail', [
            'title' => 'Detail Pesanan ' . $order['order_number'],
            'order' => $order,
            'items' => $items,
        ]);
    }
}
