<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
   protected $table = 'penjualan';
   protected $primaryKey = 'jual_id';
   protected $useTimestamps = true;
   protected $useSoftDeletes = true;
   protected $allowedFields = [
      'jual_tgl', 'jual_total', 'jual_desc', 'jual_status', 'created_at', 'updated_at', 'deleted_at'
   ];
}
