<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'order_number',
        'customer_name',
        'customer_phone',
        'order_type',
        'table_number',
        'notes',
        'total_amount',
        'status',
        'payment_status',
    ];
}
