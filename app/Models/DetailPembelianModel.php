<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPembelianModel extends Model
{
   protected $table = 'detail_pembelian';
   protected $primaryKey = 'detbeli_id';
   protected $useTimestamps = true;
   protected $useSoftDeletes = true;
   protected $allowedFields = [
      'detbeli_beli', 'detbeli_mentah', 'detbeli_jumlah', 'detbeli_harga', 'detbeli_subtotal', 'created_at', 'updated_at', 'deleted_at'
   ];
}
