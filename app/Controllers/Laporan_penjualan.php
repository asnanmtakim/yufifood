<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\BahanjadiModel;

class Laporan_penjualan extends BaseController
{
	protected $PenjualanModel;
	protected $DetailPenjualanModel;
	protected $BahanjadiModel;
	protected $session;
	public function __construct()
	{
		$this->PenjualanModel = new PenjualanModel();
		$this->DetailPenjualanModel = new DetailPenjualanModel();
		$this->BahanjadiModel = new BahanjadiModel();
		$this->session = \Config\Services::session();
	}
	public function index()
	{
		$today = date('Y-m-d');

		$day = date('w');
		$week_start = date('Y-m-d', strtotime('-' . ($day - 1) . ' days'));
		$week_end = date('Y-m-d', strtotime('+' . (7 - $day) . ' days'));

		$month = date('m');
		$year = date('Y');

		$jumlahToday = $this->PenjualanModel->where(['jual_tgl' => $today])->countAllResults();
		$uangToday = $this->PenjualanModel->selectSum('jual_total', 'jumlah')->where(['jual_tgl' => $today])->first();
		$jualWeek = $this->PenjualanModel->where(['jual_tgl >=' => $week_start, 'jual_tgl <=' => $week_end])->countAllResults();
		$uangWeek = $this->PenjualanModel->selectSum('jual_total', 'jumlah')->where(['jual_tgl >=' => $week_start, 'jual_tgl <=' => $week_end])->first();
		$jualMonth = $this->PenjualanModel->like('jual_tgl', "-$month-")->countAllResults();
		$uangMonth = $this->PenjualanModel->selectSum('jual_total', 'jumlah')->like('jual_tgl', "-$month-")->first();
		$jualYear = $this->PenjualanModel->like('jual_tgl', "$year", 'after')->countAllResults();
		$uangYear = $this->PenjualanModel->selectSum('jual_total', 'jumlah')->like('jual_tgl', "$year", 'after')->first();
		$data = [
			'title' => 'Laporan Penjualan',
			'page' => 'laporan_penjualan',
			'today' => [
				'jumlah' => $jumlahToday,
				'uang' => $uangToday['jumlah'],
			],
			'week' => [
				'jumlah' => $jualWeek,
				'uang' => $uangWeek['jumlah'],
			],
			'month' => [
				'jumlah' => $jualMonth,
				'uang' => $uangMonth['jumlah'],
			],
			'year' => [
				'jumlah' => $jualYear,
				'uang' => $uangYear['jumlah'],
			],
		];
		return view('laporan/penjualan', $data);
	}

	public function setSession()
	{
		$name = $this->request->getPost('name');
		$value = $this->request->getPost('value');
		$this->session->set($name, $value);
		echo json_encode(array('status' => 200, 'pesan' => 'Berhasil set session !!'));
	}

	public function getPenjualan()
	{
		if (!$this->session->get('sort') || $this->session->get('sort') == 'week') {
			$date = date('d-m-Y');
			$day = $this->week_from_monday($date);
			$data['data_x'] = [];
			$data['data_y'] = [];
			foreach ($day as $d) {
				$query = $this->PenjualanModel->selectSum('jual_total', 'jumlah')->where(['jual_tgl' => $d['date']])->first();
				$x = $d['day'];
				array_push($data['data_x'], $x);
				array_push($data['data_y'], intval($query['jumlah']));
			}
		} elseif ($this->session->get('sort') == 'today') {
			$today = date('Y-m-d');
			$data['data_x'] = [];
			$data['data_y'] = [];
			$query = $this->PenjualanModel->where(['jual_tgl' => $today])->find();
			foreach ($query as $q) {
				array_push($data['data_x'], $q['jual_desc']);
				array_push($data['data_y'], intval($q['jual_total']));
			}
		} elseif ($this->session->get('sort') == 'month') {
			$data['data_x'] = [];
			$data['data_y'] = [];
			$month = date('m');
			$year = date('Y');
			$date = new \DateTime("now");
			$date->setDate($year, $month, 1);
			$date->setTime(0, 0, 0);

			//last day of the month
			$maxDay = intval($date->format("t"));

			//getting the first monday
			$dayOfTheWeek = intval($date->format("N"));
			if ($dayOfTheWeek != 1) {
				//print a partial week if needed
				$diff = 8 - $dayOfTheWeek;
				if ($dayOfTheWeek <= 5) {
					$from = $date->format("Y-m-d");
					$diff2 = 7 - $dayOfTheWeek;
					$date->modify(sprintf("+%d days", $diff2));
					$to = $date->format("Y-m-d");
					echo sprintf("from: %s to %s\n", $from, $to);
					$diff -= $diff2;
				}
				$date->modify(sprintf("+%d days", $diff));
			}
			while (intval($date->format("n")) == $month) {
				$from = $date->format("Y-m-d");
				$date->modify("+6 days");
				if (intval($date->format("n")) > $month) {
					$date->setDate($year, $month, $maxDay);
				}
				$to = $date->format("Y-m-d");
				$date->modify("+1 days");

				// echo sprintf("from: %s to %s\n", $from, $to);
				// echo '<br>';
				$query = $this->PenjualanModel->selectSum('jual_total', 'jumlah')->where(['jual_tgl >=' => $from, 'jual_tgl <=' => $to])->first();
				$x = date('d/m/Y', strtotime($from)) . '-' . date('d/m/Y', strtotime($to));
				array_push($data['data_x'], $x);
				array_push($data['data_y'], intval($query['jumlah']));
			}
		} elseif ($this->session->get('sort') == 'year') {
			$data['data_x'] = [];
			$data['data_y'] = [];
			$year = date('Y');
			for ($i = 1; $i <= 12; $i++) {
				if ($i == 1) {
					$name = 'Januari';
				} elseif ($i == 2) {
					$name = 'Februari';
				} elseif ($i == 3) {
					$name = 'Maret';
				} elseif ($i == 4) {
					$name = 'April';
				} elseif ($i == 5) {
					$name = 'Mei';
				} elseif ($i == 6) {
					$name = 'Juni';
				} elseif ($i == 7) {
					$name = 'Juli';
				} elseif ($i == 8) {
					$name = 'Agustus';
				} elseif ($i == 9) {
					$name = 'September';
				} elseif ($i == 10) {
					$name = 'Oktober';
				} elseif ($i == 11) {
					$name = 'November';
				} elseif ($i == 12) {
					$name = 'Desember';
				}
				$month = date("Y-m", strtotime("$year-$i"));
				$query = $this->PenjualanModel->selectSum('jual_total', 'jumlah')->like('jual_tgl', $month)->first();
				array_push($data['data_x'], $name);
				array_push($data['data_y'], intval($query['jumlah']));
			}
		}
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil mengambil data !!', 'data' => $data));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal mengambil data !!'));
		}
	}

	public function getDetailPenjualan()
	{
		if (!$this->session->get('sort') || $this->session->get('sort') == 'week') {
			$day = date('w');
			$week_start = date('Y-m-d', strtotime('-' . ($day - 1) . ' days'));
			$week_end = date('Y-m-d', strtotime('+' . (7 - $day) . ' days'));

			$data['data_x'] = [];
			$data['data_y'] = [];
			$query = $this->DetailPenjualanModel->select('detjual_jadi, jadi_nama')
				->selectSum('detjual_jumlah', 'jumlah')
				->where(['jual_tgl >=' => $week_start, 'jual_tgl <=' => $week_end])
				->join('penjualan', 'penjualan.jual_id=detail_penjualan.detjual_jual', 'left')
				->join('bahan_jadi', 'bahan_jadi.id=detail_penjualan.detjual_jadi', 'left')
				->groupBy('detjual_jadi')
				->find();
			foreach ($query as $q) {
				array_push($data['data_x'], $q['jadi_nama']);
				array_push($data['data_y'], intval($q['jumlah']));
			}
		} elseif ($this->session->get('sort') == 'today') {
			$today = date('Y-m-d');
			$data['data_x'] = [];
			$data['data_y'] = [];
			$query = $this->DetailPenjualanModel->select('detjual_jadi, jadi_nama')
				->selectSum('detjual_jumlah', 'jumlah')
				->where(['jual_tgl' => $today])
				->join('penjualan', 'penjualan.jual_id=detail_penjualan.detjual_jual', 'left')
				->join('bahan_jadi', 'bahan_jadi.id=detail_penjualan.detjual_jadi', 'left')
				->groupBy('detjual_jadi')
				->find();
			foreach ($query as $q) {
				array_push($data['data_x'], $q['jadi_nama']);
				array_push($data['data_y'], intval($q['jumlah']));
			}
		} elseif ($this->session->get('sort') == 'month') {
			$date = date('Y-m-d');
			$data['data_x'] = [];
			$data['data_y'] = [];
			$day_start = date('Y-m-01', strtotime($date));
			$day_end = date('Y-m-t', strtotime($date));
			$query = $this->DetailPenjualanModel->select('detjual_jadi, jadi_nama')
				->selectSum('detjual_jumlah', 'jumlah')
				->where(['jual_tgl >=' => $day_start, 'jual_tgl <=' => $day_end])
				->join('penjualan', 'penjualan.jual_id=detail_penjualan.detjual_jual', 'left')
				->join('bahan_jadi', 'bahan_jadi.id=detail_penjualan.detjual_jadi', 'left')
				->groupBy('detjual_jadi')
				->find();
			foreach ($query as $q) {
				array_push($data['data_x'], $q['jadi_nama']);
				array_push($data['data_y'], intval($q['jumlah']));
			}
		} elseif ($this->session->get('sort') == 'year') {
			$year = date('Y');
			$data['data_x'] = [];
			$data['data_y'] = [];
			$query = $this->DetailPenjualanModel->select('detjual_jadi, jadi_nama')
				->selectSum('detjual_jumlah', 'jumlah')
				->like('jual_tgl', $year)
				->join('penjualan', 'penjualan.jual_id=detail_penjualan.detjual_jual', 'left')
				->join('bahan_jadi', 'bahan_jadi.id=detail_penjualan.detjual_jadi', 'left')
				->groupBy('detjual_jadi')
				->find();
			foreach ($query as $q) {
				array_push($data['data_x'], $q['jadi_nama']);
				array_push($data['data_y'], intval($q['jumlah']));
			}
		}
		if ($data) {
			echo json_encode(array('status' => 200, 'pesan' => 'Berhasil mengambil data !!', 'data' => $data));
		} else {
			echo json_encode(array('status' => 400, 'pesan' => 'Gagal mengambil data !!'));
		}
	}

	private function week_from_monday($date)
	{
		// Assuming $date is in format DD-MM-YYYY
		list($day, $month, $year) = explode("-", $date);

		// Get the weekday of the given date
		$wkday = date('l', mktime('0', '0', '0', $month, $day, $year));

		switch ($wkday) {
			case 'Monday':
				$numDaysToMon = 0;
				break;
			case 'Tuesday':
				$numDaysToMon = 1;
				break;
			case 'Wednesday':
				$numDaysToMon = 2;
				break;
			case 'Thursday':
				$numDaysToMon = 3;
				break;
			case 'Friday':
				$numDaysToMon = 4;
				break;
			case 'Saturday':
				$numDaysToMon = 5;
				break;
			case 'Sunday':
				$numDaysToMon = 6;
				break;
		}

		// Timestamp of the monday for that week
		$monday = mktime('0', '0', '0', $month, $day - $numDaysToMon, $year);

		$seconds_in_a_day = 86400;

		// Get date for 7 days from Monday (inclusive)
		for ($i = 0; $i < 7; $i++) {
			if ($i == 0) {
				$day = 'Senin';
			} elseif ($i == 1) {
				$day = 'Selasa';
			} elseif ($i == 2) {
				$day = 'Rabu';
			} elseif ($i == 3) {
				$day = 'Kamis';
			} elseif ($i == 4) {
				$day = "Jum'at";
			} elseif ($i == 5) {
				$day = 'Sabtu';
			} elseif ($i == 6) {
				$day = 'Minggu';
			}
			$dates[$i] = [
				'day' => $day,
				'date' => date('Y-m-d', $monday + ($seconds_in_a_day * $i))
			];
		}

		return $dates;
	}
}
