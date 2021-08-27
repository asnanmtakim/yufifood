<?php

namespace App\Controllers;

use App\Models\PembelianModel;
use App\Models\PenjualanModel;
use App\Models\BahanmentahModel;
use App\Models\BahanjadiModel;

class Dashboard extends BaseController
{
	protected $BahanmentahModel;
	protected $BahanjadiModel;
	protected $PembelianModel;
	protected $PenjualanModel;
	public function __construct()
	{
		$this->PembelianModel = new PembelianModel();
		$this->PenjualanModel = new PenjualanModel();
		$this->BahanmentahModel = new BahanmentahModel();
		$this->BahanjadiModel = new BahanjadiModel();
	}
	public function index()
	{
		$data = [
			'title' => 'Dashboard',
			'page' => 'dashboard',
			'penjualan' => $this->PenjualanModel->orderBy('jual_id', 'DESC')->findAll(4),
			'pembelian' => $this->PembelianModel->orderBy('beli_id', 'DESC')->findAll(4),
			'total' => [
				'mentah' => $this->BahanmentahModel->countAllResults(),
				'jadi' => $this->BahanjadiModel->countAllResults(),
				'jual' => $this->PenjualanModel->countAllResults(),
				'beli' => $this->PembelianModel->countAllResults(),
			],
		];
		// dd($data);
		return view('dashboard/index', $data);
	}
}
