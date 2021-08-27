<?php

namespace App\Models;

use CodeIgniter\Model;

class PembelianModel extends Model
{
   protected $table = 'pembelian';
   protected $primaryKey = 'beli_id';
   protected $useTimestamps = true;
   protected $useSoftDeletes = true;
   protected $allowedFields = [
      'beli_tgl', 'beli_total', 'beli_desc', 'beli_status', 'created_at', 'updated_at', 'deleted_at'
   ];
}
