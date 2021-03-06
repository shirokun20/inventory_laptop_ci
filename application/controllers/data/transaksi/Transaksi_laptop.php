<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_laptop extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->shiro_lib->cekLogin();
		$this->load->model('transaksi_model', 'tm');
	}

	public function index($value = 0)
	{
		$data = [
			'title' => 'Transaksi Tambah Laptop ' . ($value == 0 ? 'Masuk' : 'Keluar'),
			'subtitle' => 'Transaksi Tambah Laptop ' . ($value == 0 ? 'Masuk' : 'Keluar'),
		];

		$data['breadcrumb'] = [
			[
				'href' => true,
				'icon' => 'fa-exchange',
				'tujuan' => site_url('data/transaksi/' . ($value == 0 ? 'barang_masuk/' : 'barang_keluar/')),
				'title' => ''
			],
			[
				'href' => false,
				'icon' => '',
				'tujuan' => '',
				'title' => 'Transaksi'
			],
			[
				'href' => false,
				'icon' => '',
				'tujuan' => '',
				'title' => 'Laptop ' . ($value == 0 ? 'Masuk' : 'Keluar')
			], 
			[
				'href' => false,
				'icon' => '',
				'tujuan' => '',
				'title' => 'Tambah Laptop ' . ($value == 0 ? 'Masuk' : 'Keluar')
			]
		];
		$data['jenis_transaksi'] = $value;

		$this->shiro_lib->admin('transaksi/transaksi_laptop/vTransaksiLaptop', $data);
	}

	public function mengambilBarang()
	{
		$data = $this->db->get('tb_barang');
		echo json_encode([
			'shiro' => [
				'jumlah_data' => $data->num_rows(),
				'data' => $data->result(),
			]
 		]);
	}

	public function mengambilDetailBarang() 
	{
		$input = $this->input->get('barang_kode');
		$jumlah_data = 0;
		$data = [];
		if ($input) {
			$check = $this->db
					->join('tb_warna', 'tb_warna.warna_kode = tb_detail_barang.warna_kode', 'left')
					->where('barang_kode', $input)
					->get('tb_detail_barang');
			$jumlah_data = $check->num_rows();
			$data = $check->result();
		} 
		echo json_encode([
			'shiro' => [
				'jumlah_data' => $jumlah_data,
				'data' => $data,
			]
 		]);
	}

	public function mengambilStok() 
	{
		$input = $this->input->get('detail_barang_id');
		$jumlah_stok = 0;
		if ($input) {
			$check = $this->db
			->where('detail_barang_id', $input)
			->get('tb_stok');
			if ($check->num_rows() > 0) {
				$jumlah_stok = (int) $check->row()->stok_qty;
			}
		}

		echo json_encode([
			'shiro' => [
				'jumlah_stok' => $jumlah_stok,
			]
 		]);
	}

	public function getUniqCode()
	{
		$jenis_transaksi = $this->input->get('jenis_transaksi');
		$tanggal = $this->input->get('tanggal');
		$res = $this->tm->uniqCode(($jenis_transaksi == 'm' ? 0 : 1), ($tanggal == '' ? date('Y-m-d') : $tanggal));
		echo json_encode([
			'shiro' => [
				'uniqCode' => $res,
			]
 		]);
	}


	public function simpanTransaksi()
	{
		$input = $this->input->post();
		$status = 'gagal';
		$message = 'Proses transaksi mengalami error!!';
		//
		$dataTransaksi = [
			'transaksi_barang_no_faktur' => $input['transaksi_barang_no_faktur'],
			'transaksi_barang_tanggal' => $input['transaksi_barang_tanggal'] . date(' H:i:s'),
			'user_id' => $this->session->admin->user_id,
			'transaksi_barang_jenis' => $input['jenis_transaksi'] == 'm' ? 0 : 1,
		];
		//
		$response_transaksi = $this->tm->simpanKeTransaksi($dataTransaksi);
		//
		if ($response_transaksi['status'] == 'berhasil') {
			$data = [];
			foreach ($input['dataTabelDetail'] as $key) {
				$r = [];
				$r['transaksi_barang_id'] = $response_transaksi['insert_id'];
				$r['barang_kode'] = $key['barang_kode'];
				$r['detail_barang_id'] = $key['detail_barang_id'];
				$r['transaksi_barang_detail_jml'] = $key['transaksi_barang_detail_jml'];
				$data[] = $r;
				$dataTransaksi['transaksi_barang_jenis'] == 0 ? 
				$this->tm->menambahStok($r['detail_barang_id'], $r['barang_kode'], $r['transaksi_barang_detail_jml']) 
				: 
				$this->tm->mengurangiStok($r['detail_barang_id'], $r['barang_kode'], $r['transaksi_barang_detail_jml']);
			}
			if (count($data) > 0) {
				$status = 'berhasil';
				$message = 'Berhasil melakukan transaksi!!!';
				$this->tm->simpanKeDt($data);
			} 
		}
		//
		echo json_encode([
			'shiro' => [
				'status' => $status,
				'message' => $message,	
			]
 		]);
	}
}

/* End of file Transaksi_laptop.php */
/* Location: ./application/controllers/data/transaksi/Transaksi_laptop.php */
