<?php

namespace App\Controllers;

use App\Models\BahanjadiModel;

class Bahanjadi extends BaseController
{
	protected $BahanjadiModel;
	public function __construct()
	{
		$this->BahanjadiModel = new BahanjadiModel();
	}

	public function index()
	{
		$data = [
			'title' => 'Bahan Jadi',
			'page' => 'bahanjadi'
		];
		return view('bahanjadi/index', $data);
	}

	function readBahanjadi()
	{
		$data = $this->BahanjadiModel->findAll();
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil ambil data !!', 'data' => $data));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal ambil data !!'));
		}
	}

	function getOneBahanjadi()
	{
		$data = $this->BahanjadiModel->find($this->request->getVar('id'));
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil ambil data !!', 'data' => $data));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal ambil data !!'));
		}
	}

	function deleteBahanjadi()
	{
		$data = $this->BahanjadiModel->delete($this->request->getPost('id'));
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil hapus data !!'));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal hapus data !!'));
		}
	}

	public function saveBahanjadi()
	{
		$validation = \Config\Services::validation();
		if ($this->validate($this->rulesValidation())) {
			$data = [
				'jadi_nama' => $this->request->getPost('jadi_nama'),
				'jadi_satuan' => $this->request->getPost('jadi_satuan'),
				'jadi_harga' => $this->request->getPost('jadi_harga'),
				'jadi_desc' => $this->request->getPost('jadi_desc'),
			];
			if (!empty($this->request->getPost('jadi_id'))) {
				$data['id'] = $this->request->getPost('jadi_id');
				$query = $this->BahanjadiModel->save($data);
			} else {
				$data['jadi_status'] = 1;
				$query = $this->BahanjadiModel->save($data);
			}
			if ($query) {
				echo json_encode(array('status' => 200, 'pesan' => 'Berhasil disimpan !!'));
			} else {
				echo json_encode(array('status' => 0, 'pesan' => 'Gagal disimpan !!'));
			}
		} else {
			$array = array(
				'jadi_nama' => $validation->getError('jadi_nama'),
				'jadi_satuan' => $validation->getError('jadi_satuan'),
				'jadi_harga' => $validation->getError('jadi_harga'),
			);
			echo json_encode(array('status' => 400, 'pesan' => $array));
		}
	}

	private function rulesValidation()
	{
		$config = [
			'jadi_nama' => [
				'label' => 'Nama bahan jadi',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} harus diisi.',
				],
			],
			'jadi_satuan' => [
				'label' => 'Satuan bahan jadi',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} harus diisi.',
				],
			],
			'jadi_harga' => [
				'label' => 'Harga bahan jadi',
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
