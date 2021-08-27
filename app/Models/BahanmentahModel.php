<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanmentahModel extends Model
{
   protected $table = 'bahan_mentah';
   protected $primaryKey = 'id';
   protected $useTimestamps = true;
   protected $useSoftDeletes = true;
   protected $allowedFields = [
      'mentah_nama', 'mentah_desc', 'mentah_status', 'mentah_satuan', 'mentah_harga', 'created_at', 'updated_at', 'deleted_at'
   ];
}
