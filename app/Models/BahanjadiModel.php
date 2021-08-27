<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanjadiModel extends Model
{
   protected $table = 'bahan_jadi';
   protected $primaryKey = 'id';
   protected $useTimestamps = true;
   protected $useSoftDeletes = true;
   protected $allowedFields = [
      'jadi_nama', 'jadi_desc', 'jadi_status', 'jadi_satuan', 'jadi_harga', 'created_at', 'updated_at', 'deleted_at'
   ];
}
