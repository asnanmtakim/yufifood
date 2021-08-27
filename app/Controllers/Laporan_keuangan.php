<?php

namespace App\Controllers;

use App\Models\PembelianModel;
use App\Models\PenjualanModel;
use App\Models\BahanmentahModel;

class Laporan_keuangan extends BaseController
{
	protected $PembelianModel;
	protected $PenjualanModel;
	protected $session;
	public function __construct()
	{
		$this->PembelianModel = new PembelianModel();
		$this->PenjualanModel = new PenjualanModel();
		$this->session = \Config\Services::session();
	}
	public function index()
	{
		if ($this->session->get('sort') == 'today') {
			$this->session->set('sort', 'week');
		}
		$data = [
			'title' => 'Laporan Keuangan',
			'page' => 'laporan_keuangan',
		];
		return view('laporan/keuangan', $data);
	}

	public function setSession()
	{
		$name = $this->request->getPost('name');
		$value = $this->request->getPost('value');
		$this->session->set($name, $value);
		echo json_encode(array('status' => 200, 'pesan' => "Berhasil set session !!"));
	}

	public function getKeuangan()
	{
		if (!$this->session->get('sort') || $this->session->get('sort') == 'week') {
			$date = date('d-m-Y');
			$day = $this->week_from_monday($date);
			$data['data_x'] = [];
			$data['data_y'] = [];
			foreach ($day as $d) {
				$query = $this->PembelianModel->selectSum('beli_total', 'jumlah')->where(['beli_tgl' => $d['date']])->first();
				$query2 = $this->PenjualanModel->selectSum('jual_total', 'jumlah')->where(['jual_tgl' => $d['date']])->first();
				$x = $d['day'];
				array_push($data['data_x'], $x);
				array_push($data['data_y'], (intval($query2['jumlah']) - intval($query['jumlah'])));
			}
		} elseif ($this->session->get('sort') == 'today') {
			$today = date('Y-m-d');
			$data['data_x'] = [];
			$data['data_y'] = [];
			$query = $this->PembelianModel->selectSum('beli_total', 'jumlah')->where(['beli_tgl' => $today])->find();
			$query2 = $this->PenjualanModel->selectSum('jual_total', 'jumlah')->where(['jual_tgl' => $today])->find();
			array_push($data['data_x'], 'Hari ini');
			array_push($data['data_y'], (intval($query2['jumlah']) - intval($query['jumlah'])));
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
				$query = $this->PembelianModel->selectSum('beli_total', 'jumlah')->where(['beli_tgl >=' => $from, 'beli_tgl <=' => $to])->first();
				$query2 = $this->PenjualanModel->selectSum('jual_total', 'jumlah')->where(['jual_tgl >=' => $from, 'jual_tgl <=' => $to])->first();
				$x = date('d/m/Y', strtotime($from)) . '-' . date('d/m/Y', strtotime($to));
				array_push($data['data_x'], $x);
				array_push($data['data_y'], (intval($query2['jumlah']) - intval($query['jumlah'])));
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
				$query = $this->PembelianModel->selectSum('beli_total', 'jumlah')->like('beli_tgl', $month)->first();
				$query2 = $this->PenjualanModel->selectSum('jual_total', 'jumlah')->like('jual_tgl', $month)->first();
				array_push($data['data_x'], $name);
				array_push($data['data_y'], (intval($query2['jumlah']) - intval($query['jumlah'])));
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
