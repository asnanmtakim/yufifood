<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\BahanjadiModel;

class Penjualan extends BaseController
{
	protected $PenjualanModel;
	protected $DetailPenjualanModel;
	public function __construct()
	{
		$this->PenjualanModel = new PenjualanModel();
		$this->DetailPenjualanModel = new DetailPenjualanModel();
	}

	public function index()
	{
		$data = [
			'title' => 'Penjualan',
			'page' => 'penjualan'
		];
		return view('penjualan/index', $data);
	}

	public function readPenjualan()
	{
		$data = $this->PenjualanModel->orderBy('jual_id', 'DESC')->findAll();
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil ambil data !!', 'data' => $data));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal ambil data !!'));
		}
	}

	public function getOnePenjualan()
	{
		$data = $this->PenjualanModel->find($this->request->getVar('id'));
		$detail = $this->DetailPenjualanModel->join('bahan_jadi', 'bahan_jadi.id = detail_penjualan.detjual_jadi')
			->where(['detjual_jual' => $this->request->getVar('id')])
			->orderBy('detjual_id', 'ASC')
			->find();
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil ambil data !!', 'data' => $data, 'detail' => $detail));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal ambil data !!'));
		}
	}

	public function deletePenjualan()
	{
		$query = $this->PenjualanModel->delete($this->request->getPost('id'));
		$query2 = $this->DetailPenjualanModel->where(['detjual_jual' => $this->request->getPost('id')])->delete();
		if ($query && $query2) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil hapus data !!'));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal hapus data !!'));
		}
	}

	public function add()
	{
		$cart = \Config\Services::cart();
		$cart->destroy();
		$data = [
			'title' => 'Tambah Penjualan',
			'page' => 'penjualan'
		];
		return view('penjualan/add', $data);
	}

	public function getBahanJadi()
	{
		$BahanjadiModel = new BahanjadiModel();
		$data = $BahanjadiModel->findAll();
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil ambil data !!', 'data' => $data));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal ambil data !!'));
		}
	}

	public function getCartPenjualan()
	{
		$cart = \Config\Services::cart();
		$data = $cart->contents();
		$total = $cart->totalItems();
		$grand = $cart->total();
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil ambil data !!', 'data' => $data, 'total' => $total, 'grand' => $grand));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal ambil data !!'));
		}
	}

	public function addCartPenjualan()
	{
		$id = $this->request->getPost('id');
		$BahanjadiModel = new BahanjadiModel();
		$bahan = $BahanjadiModel->find($id);
		$cart = \Config\Services::cart();
		$data = $cart->insert(array(
			'id'      => $bahan['id'],
			'qty'     => 1,
			'price'   => $bahan['jadi_harga'],
			'name'    => $bahan['jadi_nama'],
			'satuan'    => $bahan['jadi_satuan'],
		));
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil ambil data !!'));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal ambil data !!'));
		}
	}

	public function updateQtyPenjualan()
	{
		$cart = \Config\Services::cart();
		$data = $cart->update(array(
			'rowid'   => $this->request->getPost('rowid'),
			'qty'     => $this->request->getPost('qty'),
		));
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil ambil data !!'));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal ambil data !!'));
		}
	}

	public function updatePricePenjualan()
	{
		$cart = \Config\Services::cart();
		$data = $cart->update(array(
			'rowid'   => $this->request->getPost('rowid'),
			'price'     => $this->request->getPost('price'),
		));
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil ambil data !!'));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal ambil data !!'));
		}
	}

	public function deleteCartPenjualan()
	{
		$cart = \Config\Services::cart();
		$data = $cart->remove($this->request->getPost('id'));
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil hapus penjualan !!'));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal hapus data !!'));
		}
	}

	public function destroyCartPenjualan()
	{
		$cart = \Config\Services::cart();
		$data = $cart->destroy();
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil mereset penjualan !!'));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Berhasil mereset penjualan !!'));
		}
	}

	public function savePenjualan()
	{
		$validation = \Config\Services::validation();
		if ($this->validate($this->rulesValidation())) {
			$cart = \Config\Services::cart();
			$itemCart = $cart->contents();
			if ($itemCart == false) {
				echo json_encode(array('status' => 0, 'pesan' => 'Penjualan tidak boleh kosong !!'));
				return;
			}
			$data = [
				'jual_tgl' => date("Y-m-d", strtotime($this->request->getPost('jual_tgl'))),
				'jual_desc' => $this->request->getPost('jual_desc'),
				'jual_total' => $cart->total(),
			];
			if (!empty($this->request->getPost('jual_id'))) {
				$data['jual_id'] = $this->request->getPost('jual_id');
				$query = $this->PenjualanModel->save($data);
				$jual_id = $this->request->getPost('jual_id');
				$this->DetailPenjualanModel->where(['detjual_jual' => $jual_id])->delete();
				foreach ($itemCart as $item) {
					$detail[] = [
						'detjual_jual' => $jual_id,
						'detjual_jadi' => $item['id'],
						'detjual_jumlah' => $item['qty'],
						'detjual_harga' => $item['price'],
						'detjual_subtotal' => $item['subtotal'],
					];
				}
				$query2 = $this->DetailPenjualanModel->insertBatch($detail);
			} else {
				$data['jual_status'] = 1;
				$query = $this->PenjualanModel->save($data);
				$jual_id = $this->PenjualanModel->getInsertID();
				foreach ($itemCart as $item) {
					$detail[] = [
						'detjual_jual' => $jual_id,
						'detjual_jadi' => $item['id'],
						'detjual_jumlah' => $item['qty'],
						'detjual_harga' => $item['price'],
						'detjual_subtotal' => $item['subtotal'],
					];
				}
				$query2 = $this->DetailPenjualanModel->insertBatch($detail);
			}
			if ($query && $query2) {
				$cart->destroy();
				echo json_encode(array('status' => 200, 'pesan' => 'Berhasil disimpan !!'));
			} else {
				echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !!'));
			}
		} else {
			$array = array(
				'jual_tgl' => $validation->getError('jual_tgl'),
				'jual_desc' => $validation->getError('jual_desc')
			);
			echo json_encode(array('status' => 400, 'pesan' => $array));
		}
	}

	public function edit($id = null)
	{
		if ($id == null) {
			return redirect()->to('/penjualan');
		}
		$cart = \Config\Services::cart();
		$cart->destroy();
		$BahanjadiModel = new BahanjadiModel();
		$jual = $this->PenjualanModel->find($id);
		$detail = $this->DetailPenjualanModel->where(['detjual_jual' => $id])->find();
		foreach ($detail as $det) {
			$bahan = $BahanjadiModel->find($det['detjual_jadi']);
			$data = $cart->insert(array(
				'id'      => $det['detjual_jadi'],
				'qty'     => $det['detjual_jumlah'],
				'price'   => $det['detjual_harga'],
				'name'    => $bahan['jadi_nama'],
				'satuan'    => $bahan['jadi_satuan'],
			));
		}
		$data = [
			'title' => 'Edit Penjualan',
			'page' => 'penjualan',
			'jual' => $jual
		];
		return view('penjualan/edit', $data);
	}

	private function rulesValidation()
	{
		$config = [
			'jual_tgl' => [
				'label' => 'Tanggal Penjualan',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} harus diisi.',
				],
			],
			'jual_desc' => [
				'label' => 'Deskripsi Penjualan',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} harus diisi.',
				],
			]
		];

		return $config;
	}
}
