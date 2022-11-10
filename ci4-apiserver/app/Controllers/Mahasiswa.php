<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelmahasiswa;

class Mahasiswa extends BaseController
{
    public function index()
    {
        $modelMhs = new Modelmahasiswa();
        $data = $modelMhs->findAll();
        $response = [
            'status' => 200,
            'error' => "false",
            'message' => '',
            'totaldata' => count($data),
            'data' => $data,
        ];
        return $this->response->setJSON($response, 200);
    }

    public function show($cari = null)
    {
        $modelMhs = new Modelmahasiswa();
        $data = $modelMhs->orLike('mhsnobp', $cari)->orLike('mhsnama', $cari)->get()->getResult();
        if (count($data) > 1) {
            $response = [
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ];
            return $this->response->setJSON($response, 200);
        } else if (count($data) == 1) {
            $response = [
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ];
            return $this->response->setJSON($response, 200);
        } else {
            return $this->failNotFound->setJSON('maaf data ' . $cari . ' tidak ditemukan');
        }
    }

    public function create()
    {
        $modelMhs = new Modelmahasiswa();
        $nobp = $this->request->getPost("mhsnobp");
        $nama = $this->request->getPost("mhsnama");
        $alamat = $this->request->getPost("mhsalamat");
        $prodi = $this->request->getPost("prodinama");
        $tgllahir = $this->request->getPost("mhstgllhr");
        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'mhsnobp' => [
                'rules' => 'is_unique[mahasiswa.mhsnobp]',
                'label' => 'Nomor Induk Mahasiswa',
                'errors' => [
                    'is_unique' => "{field} sudah ada"
                ]
            ]
        ]);
        if (!$valid) {
            $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("mhsnobp"),
            ];
            return $this->response->setJSON($response, 404);
        } else {
            $modelMhs->insert([
                'mhsnobp' => $nobp,
                'mhsnama' => $nama,
                'mhsalamat' => $alamat,
                'prodinama' => $prodi,
                'mhstgllhr' => $tgllahir,
            ]);
            $response = [
                'status' => 201,
                'error' => "false",
                'message' => "Data berhasil disimpan"
            ];
            return $this->response->setJSON($response, 201);
        }
    }

    public function update($nobp = null)
    {
        $model = new Modelmahasiswa();
        $data = [
            'mhsnama' => $this->request->getVar("mhsnama"),
            'mhsalamat' => $this->request->getVar("mhsalamat"),
            'prodinama' => $this->request->getVar("prodinama"),
            'mhstgllhr' => $this->request->getVar("mhstgllhr"),
        ];
        $data = $this->request->getRawInput();
        $model->update($nobp, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => "Data Anda dengan NIM $nobp berhasil dibaharukan"
        ];
        return $this->response->setJSON($response, 200);
    }

    public function delete($nobp = null)
    {
        $modelMhs = new Modelmahasiswa();
        $cekData = $modelMhs->find($nobp);
        if ($cekData) {
            $modelMhs->delete($nobp);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => "Selamat data sudah berhasil dihapus maksimal"
            ];
            return $this->response->setJSON($response, 200);
        } else {
            return $this->failNotFound->setJSON('maaf data ' . $nobp . ' tidak ditemukan');
        }
    }
}
