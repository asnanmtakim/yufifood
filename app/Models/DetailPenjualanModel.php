<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPenjualanModel extends Model
{
   protected $table = 'detail_penjualan';
   protected $primaryKey = 'detjual_id';
   protected $useTimestamps = true;
   protected $useSoftDeletes = true;
   protected $allowedFields = [
      'detjual_jual', 'detjual_jadi', 'detjual_jumlah', 'detjual_harga', 'detjual_subtotal', 'created_at', 'updated_at', 'deleted_at'
   ];
}
