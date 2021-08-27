<?php

namespace App\Controllers;

use App\Models\PembelianModel;
use App\Models\DetailPembelianModel;
use App\Models\BahanmentahModel;

class Pembelian extends BaseController
{
	protected $PembelianModel;
	protected $DetailPembelianModel;
	public function __construct()
	{
		$this->PembelianModel = new PembelianModel();
		$this->DetailPembelianModel = new DetailPembelianModel();
	}

	public function index()
	{
		$data = [
			'title' => 'Pembelian',
			'page' => 'pembelian'
		];
		return view('pembelian/index', $data);
	}

	public function readPembelian()
	{
		$data = $this->PembelianModel->orderBy('beli_id', 'DESC')->findAll();
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil ambil data !!', 'data' => $data));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal ambil data !!'));
		}
	}

	public function getOnePembelian()
	{
		$data = $this->PembelianModel->find($this->request->getVar('id'));
		$detail = $this->DetailPembelianModel->join('bahan_mentah', 'bahan_mentah.id = detail_pembelian.detbeli_mentah')
			->where(['detbeli_beli' => $this->request->getVar('id')])
			->orderBy('detbeli_id', 'ASC')
			->find();
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil ambil data !!', 'data' => $data, 'detail' => $detail));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal ambil data !!'));
		}
	}

	public function deletePembelian()
	{
		$query = $this->PembelianModel->delete($this->request->getPost('id'));
		$query2 = $this->DetailPembelianModel->where(['detbeli_beli' => $this->request->getPost('id')])->delete();
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
			'title' => 'Tambah Pembelian',
			'page' => 'pembelian'
		];
		return view('pembelian/add', $data);
	}

	public function getBahanMentah()
	{
		$BahanmentahModel = new BahanmentahModel();
		$data = $BahanmentahModel->findAll();
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil ambil data !!', 'data' => $data));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal ambil data !!'));
		}
	}

	public function getCartPembelian()
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

	public function addCartPembelian()
	{
		$id = $this->request->getPost('id');
		$BahanmentahModel = new BahanmentahModel();
		$bahan = $BahanmentahModel->find($id);
		$cart = \Config\Services::cart();
		$data = $cart->insert(array(
			'id'      => $bahan['id'],
			'qty'     => 1,
			'price'   => $bahan['mentah_harga'],
			'name'    => $bahan['mentah_nama'],
			'satuan'    => $bahan['mentah_satuan'],
		));
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil ambil data !!'));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal ambil data !!'));
		}
	}

	public function updateQtyPembelian()
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

	public function updatePricePembelian()
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

	public function deleteCartPembelian()
	{
		$cart = \Config\Services::cart();
		$data = $cart->remove($this->request->getPost('id'));
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil hapus pembelian !!'));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal hapus data !!'));
		}
	}

	public function destroyCartPembelian()
	{
		$cart = \Config\Services::cart();
		$data = $cart->destroy();
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil mereset pembelian !!'));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Berhasil mereset pembelian !!'));
		}
	}

	public function savePembelian()
	{
		$validation = \Config\Services::validation();
		if ($this->validate($this->rulesValidation())) {
			$cart = \Config\Services::cart();
			$itemCart = $cart->contents();
			if ($itemCart == false) {
				echo json_encode(array('status' => 0, 'pesan' => 'Pembelian tidak boleh kosong !!'));
				return;
			}
			$data = [
				'beli_tgl' => date("Y-m-d", strtotime($this->request->getPost('beli_tgl'))),
				'beli_desc' => $this->request->getPost('beli_desc'),
				'beli_total' => $cart->total(),
			];
			if (!empty($this->request->getPost('beli_id'))) {
				$data['beli_id'] = $this->request->getPost('beli_id');
				$query = $this->PembelianModel->save($data);
				$beli_id = $this->request->getPost('beli_id');
				$this->DetailPembelianModel->where(['detbeli_beli' => $beli_id])->delete();
				foreach ($itemCart as $item) {
					$detail[] = [
						'detbeli_beli' => $beli_id,
						'detbeli_mentah' => $item['id'],
						'detbeli_jumlah' => $item['qty'],
						'detbeli_harga' => $item['price'],
						'detbeli_subtotal' => $item['subtotal'],
					];
				}
				$query2 = $this->DetailPembelianModel->insertBatch($detail);
			} else {
				$data['beli_status'] = 1;
				$query = $this->PembelianModel->save($data);
				$beli_id = $this->PembelianModel->getInsertID();
				foreach ($itemCart as $item) {
					$detail[] = [
						'detbeli_beli' => $beli_id,
						'detbeli_mentah' => $item['id'],
						'detbeli_jumlah' => $item['qty'],
						'detbeli_harga' => $item['price'],
						'detbeli_subtotal' => $item['subtotal'],
					];
				}
				$query2 = $this->DetailPembelianModel->insertBatch($detail);
			}
			if ($query && $query2) {
				$cart->destroy();
				echo json_encode(array('status' => 200, 'pesan' => 'Berhasil disimpan !!'));
			} else {
				echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !!'));
			}
		} else {
			$array = array(
				'beli_tgl' => $validation->getError('beli_tgl'),
				'beli_desc' => $validation->getError('beli_desc')
			);
			echo json_encode(array('status' => 400, 'pesan' => $array));
		}
	}

	public function edit($id = null)
	{
		if ($id == null) {
			return redirect()->to('/pembelian');
		}
		$cart = \Config\Services::cart();
		$cart->destroy();
		$BahanmentahModel = new BahanmentahModel();
		$beli = $this->PembelianModel->find($id);
		$detail = $this->DetailPembelianModel->where(['detbeli_beli' => $id])->find();
		foreach ($detail as $det) {
			$bahan = $BahanmentahModel->find($det['detbeli_mentah']);
			$data = $cart->insert(array(
				'id'      => $det['detbeli_mentah'],
				'qty'     => $det['detbeli_jumlah'],
				'price'   => $det['detbeli_harga'],
				'name'    => $bahan['mentah_nama'],
				'satuan'    => $bahan['mentah_satuan'],
			));
		}
		$data = [
			'title' => 'Edit Pembelian',
			'page' => 'pembelian',
			'beli' => $beli
		];
		return view('pembelian/edit', $data);
	}

	private function rulesValidation()
	{
		$config = [
			'beli_tgl' => [
				'label' => 'Tanggal pembelian',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} harus diisi.',
				],
			],
			'beli_desc' => [
				'label' => 'Deskripsi pembelian',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} harus diisi.',
				],
			]
		];

		return $config;
	}
}
