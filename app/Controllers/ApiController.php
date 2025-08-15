<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ApiController extends ResourceController
{
    protected $kategoriModel;
    protected $transaksiModel;

    public function __construct()
    {
        $this->kategoriModel = new \App\Models\KategoriModel();
        $this->transaksiModel = new \App\Models\TransaksiModel();
    }

    // ================== KATEGORI ==================

    public function getKategori()
    {
        $data = $this->kategoriModel->findAll();
        return $this->respond($data);
    }

    public function createKategori()
    {
        $rules = [
            'nama_kategori' => 'required|min_length[3]',
            'tipe_kategori' => 'required|in_list[pemasukan,pengeluaran]'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }
        
        $data = [
            'nama_kategori' => $this->request->getVar('nama_kategori'),
            'tipe_kategori' => $this->request->getVar('tipe_kategori'),
        ];

        $this->kategoriModel->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Kategori berhasil ditambahkan'
            ]
        ];
        return $this->respondCreated($response);
    }

    public function updateKategori($id = null)
    {
        $data = $this->request->getRawInput();
        $this->kategoriModel->update($id, $data);
         $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Kategori berhasil diupdate'
            ]
        ];
        return $this->respond($response);
    }

    public function deleteKategori($id = null)
    {
        $data = $this->kategoriModel->find($id);
        if ($data) {
            $this->kategoriModel->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Kategori berhasil dihapus'
                ]
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('Kategori tidak ditemukan.');
        }
    }


    // ================== TRANSAKSI ==================

    public function getTransaksi()
    {
        // Join dengan tabel kategori untuk mendapatkan nama kategori
        $data = $this->transaksiModel
            ->select('transaksi.*, kategori.nama_kategori, kategori.tipe_kategori')
            ->join('kategori', 'kategori.id_kategori = transaksi.id_kategori')
            ->orderBy('tanggal_transaksi', 'DESC')
            ->findAll();

        return $this->respond($data);
    }
    
    public function createTransaksi()
    {
        $rules = [
            'id_kategori' => 'required|numeric',
            'jumlah' => 'required|numeric',
            'tanggal_transaksi' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $data = [
            'id_kategori'       => $this->request->getVar('id_kategori'),
            'deskripsi'         => $this->request->getVar('deskripsi'),
            'jumlah'            => $this->request->getVar('jumlah'),
            'tanggal_transaksi' => $this->request->getVar('tanggal_transaksi'),
        ];
        
        $this->transaksiModel->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Transaksi berhasil ditambahkan'
            ]
        ];
        return $this->respondCreated($response);
    }
     public function updateTransaksi($id = null)
    {
        // Ambil data input mentah (JSON) dari request
        $data = $this->request->getRawInput();

        // Validasi dasar
        if (empty($data)) {
            return $this->fail('Data untuk diupdate tidak boleh kosong.');
        }

        // Cari data transaksi yang ada berdasarkan ID
        $existingTransaksi = $this->transaksiModel->find($id);
        if (!$existingTransaksi) {
            return $this->failNotFound('Transaksi dengan ID ' . $id . ' tidak ditemukan.');
        }

        // Lakukan update
        $this->transaksiModel->update($id, $data);

        // Siapkan respons sukses
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Transaksi berhasil diupdate'
            ]
        ];
        return $this->respond($response);
    }

    public function deleteTransaksi($id = null)
    {
        $data = $this->transaksiModel->find($id);
        if ($data) {
            $this->transaksiModel->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Transaksi berhasil dihapus'
                ]
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('Transaksi tidak ditemukan.');
        }
    }
}