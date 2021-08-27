<?php

namespace App\Controllers;

use App\Models\BahanmentahModel;

class Bahanmentah extends BaseController
{
	protected $BahanmentahModel;
	public function __construct()
	{
		$this->BahanmentahModel = new BahanmentahModel();
	}

	public function index()
	{
		$data = [
			'title' => 'Bahan Mentah',
			'page' => 'bahanmentah'
		];
		return view('bahanmentah/index', $data);
	}

	function readBahanmentah()
	{
		$data = $this->BahanmentahModel->findAll();
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil ambil data !!', 'data' => $data));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal ambil data !!'));
		}
	}

	function getOneBahanmentah()
	{
		$data = $this->BahanmentahModel->find($this->request->getVar('id'));
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil ambil data !!', 'data' => $data));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal ambil data !!'));
		}
	}

	function deleteBahanmentah()
	{
		$data = $this->BahanmentahModel->delete($this->request->getPost('id'));
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil hapus data !!'));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal hapus data !!'));
		}
	}

	public function saveBahanmentah()
	{
		$validation = \Config\Services::validation();
		if ($this->validate($this->rulesValidation())) {
			$data = [
				'mentah_nama' => $this->request->getPost('mentah_nama'),
				'mentah_satuan' => $this->request->getPost('mentah_satuan'),
				'mentah_harga' => $this->request->getPost('mentah_harga'),
				'mentah_desc' => $this->request->getPost('mentah_desc'),
			];
			if (!empty($this->request->getPost('mentah_id'))) {
				$data['id'] = $this->request->getPost('mentah_id');
				$query = $this->BahanmentahModel->save($data);
			} else {
				$data['mentah_status'] = 1;
				$query = $this->BahanmentahModel->save($data);
			}
			if ($query) {
				echo json_encode(array('status' => 200, 'pesan' => 'Berhasil disimpan !!'));
			} else {
				echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !!'));
			}
		} else {
			$array = array(
				'mentah_nama' => $validation->getError('mentah_nama'),
				'mentah_satuan' => $validation->getError('mentah_satuan'),
				'mentah_harga' => $validation->getError('mentah_harga'),
			);
			echo json_encode(array('status' => 400, 'pesan' => $array));
		}
	}

	private function rulesValidation()
	{
		$config = [
			'mentah_nama' => [
				'label' => 'Nama bahan mentah',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} harus diisi.',
				],
			],
			'mentah_satuan' => [
				'label' => 'Satuan bahan mentah',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} harus diisi.',
				],
			],
			'mentah_harga' => [
				'label' => 'Harga bahan mentah',
				'rules' => 'required|numeric',
				'errors' => [
					'required' => '{field} harus diisi.',
					'numeric' => '{field} harus berisi angka.',
				],
			]
		];

		return $config;
	}
}
