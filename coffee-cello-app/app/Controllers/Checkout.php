<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ProductModel;

class Checkout extends BaseController
{
   public function index()
{
    return view('checkout');
}
    public function place()
    {
        $customerName = trim((string) $this->request->getPost('customer_name'));
        $customerPhone = trim((string) $this->request->getPost('customer_phone'));
        $orderType = (string) $this->request->getPost('order_type');
        $tableNumber = trim((string) $this->request->getPost('table_number'));
        $notes = trim((string) $this->request->getPost('notes'));
        $cartJson = (string) $this->request->getPost('cart_data');

        if ($customerName === '' || strlen($customerName) > 100) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nama pelanggan wajib diisi (maksimal 100 karakter).');
        }

        if (strlen($customerPhone) > 30) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nomor telepon maksimal 30 karakter.');
        }

        if (!in_array($orderType, ['dine_in', 'takeaway'], true)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jenis pesanan tidak valid.');
        }

        if ($orderType === 'dine_in' && $tableNumber === '') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nomor meja wajib diisi untuk makan di tempat.');
        }

        $cart = json_decode($cartJson, true);

        if (!is_array($cart) || empty($cart)) {
            return redirect()->to('/checkout')
                ->with('error', 'Keranjang kosong atau data pesanan tidak valid.');
        }

        $productModel = new ProductModel();
        $orderItems = [];
        $total = 0;

        foreach ($cart as $item) {
            $productId = filter_var(
                $item['id'] ?? null,
                FILTER_VALIDATE_INT
            );
            $quantity = filter_var(
                $item['quantity'] ?? null,
                FILTER_VALIDATE_INT
            );

            if (!$productId || !$quantity || $quantity < 1 || $quantity > 99) {
                return redirect()->to('/checkout')
                    ->with('error', 'Ada produk atau jumlah pesanan yang tidak valid.');
            }

            $product = $productModel
                ->where('id', $productId)
                ->where('is_available', 1)
                ->first();

            if (!$product) {
                return redirect()->to('/checkout')
                    ->with('error', 'Salah satu produk sudah tidak tersedia.');
            }

            if (isset($product['stock']) && $quantity > (int) $product['stock']) {
                return redirect()->to('/checkout')
                    ->with('error', 'Stok produk ' . $product['name'] . ' tidak mencukupi.');
            }

            $price = (float) $product['price'];
            $subtotal = $price * $quantity;
            $total += $subtotal;

            $orderItems[] = [
                'product_id' => $product['id'],
                'product_name' => $product['name'],
                'price' => $price,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        if ($total <= 0) {
            return redirect()->to('/checkout')
                ->with('error', 'Total pesanan tidak valid.');
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $orderNumber = 'CC-' . date('YmdHis') . '-' . strtoupper(bin2hex(random_bytes(3)));

            $orders = new OrderModel();
           $orderId = $orders->insert([
    'order_number' => 'TEMP',
    'customer_name' => $customerName,
    'customer_phone' => $customerPhone ?: null,
    'order_type' => $orderType,
    'table_number' => $orderType === 'dine_in' ? $tableNumber : null,
    'notes' => $notes ?: null,
    'total_amount' => $total,
    'status' => 'pending',
    'payment_status' => 'unpaid',
]);

if (!$orderId) {
    throw new \RuntimeException('Gagal menyimpan pesanan.');
}

$orderNumber = 'CC-' . str_pad((string) $orderId, 4, '0', STR_PAD_LEFT);

$orders->update($orderId, [
    'order_number' => $orderNumber,
]);

            if (!$orderId) {
                throw new \RuntimeException('Gagal menyimpan pesanan.');
            }

            $itemsModel = new OrderItemModel();

            foreach ($orderItems as $item) {
                $item['order_id'] = $orderId;

                if (!$itemsModel->insert($item)) {
                    throw new \RuntimeException('Gagal menyimpan detail pesanan.');
                }
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi database gagal.');
            }

            $db->transCommit();

            session()->setFlashdata('order_number', $orderNumber);
session()->setFlashdata('customer_name', $customerName);

            return redirect()->to('/checkout/success');
        } catch (\Throwable $e) {
            $db->transRollback();

            log_message('error', 'Checkout gagal: ' . $e->getMessage());

            return redirect()->to('/checkout')
                ->with('error', 'Pesanan gagal disimpan. Silakan coba kembali.');
        }
    }

    public function success()
    {
        $orderNumber = session()->getFlashdata('order_number');

        if (!$orderNumber) {
            return redirect()->to('/');
        }

        return view('checkout_success', [
            'orderNumber' => $orderNumber,
        ]);
    }
}
