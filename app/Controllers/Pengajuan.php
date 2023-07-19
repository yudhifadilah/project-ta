<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\PengajuanModel;
use App\Models\BuktiPengajuanModel;

class Pengajuan extends BaseController
{
    public function __construct()
    {
        $this->pengajuan = new PengajuanModel();
        $this->bukti = new BuktiPengajuanModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'user' => $this->user,
            'title' => 'Daftar Pengajuan Saya',
            'data' => $this->pengajuan->where(['user_id' => $this->user['id'], 'row_status' => 1])->orderBy('created_at', 'DESC')->findAll()
        ];
        return view('user/pengajuan/index', $data);
    }

    public function soft_delete($id)
    {
        $this->bukti->soft_delete($id); // update deleted_at, row_status

        $this->pengajuan->save([
            'id' => $id,
            'deleted_at' => date('Y-m-d H:i:s'),
            'row_status' => 0
        ]);

        session()->setFlashdata('msg', 'Data berhasil dihapus.');
        return redirect()->to('/pengajuan');
    }

    public function detail($id)
    {
        $data = [
            'user' => $this->user,
            'title' => 'Detail pengajuan',
            'data' =>  $this->pengajuan->find($id),
            'bukti' => $this->bukti->getBukti($id),
        ];

        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        return view('user/pengajuan/detail', $data);
    }

    public function tambah()
    {
        $data = [
            'user' => $this->user,
            'title' => 'Tambah Pengajuan Baru',
            'validation' => $this->validation
        ];
        return view('user/pengajuan/tambah_pengajuan', $data);
    }

    public function tambah_pengajuan()
    {
        $rules = [
            'judul_pengajuan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Perihal pengajuan wajib diisi.'
                ]
            ],
            'isi_pengajuan' => [
                'rules' => 'required|min_length[30]',
                'errors' => [
                    'required' => 'Isi pengajuan wajib diisi.',
                    'min_length' => 'Minimal 30 karakter.'
                ]
            ],
            'images' => [
                'rules' => 'uploaded[images.0]|max_size[images,1024]|is_image[images]|mime_in[images,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Satu file wajib ada.',
                    'max_size' => 'Anda mengupload file yang melebihi ukuran maksimal.',
                    'is_image' => 'Anda mengupload file yang bukan gambar.',
                    'mime_in' => 'Anda mengupload file yang bukan gambar.'
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/pengajuan/tambah')->withInput();
        }

        // JIKA LOLOS VALIDASI > CHECKING FILE
        $images = $this->request->getFileMultiple('images');
        $jumlahFile = count($images); // jumlah file yang di upload

        if ($jumlahFile > 6) { // jika jumlah file melebihi aturan (3)
            session()->setFlashdata('err-files', '<span class="text-danger">Jumlah file yang anda upload melebihi aturan.</span>');
            return redirect()->to('/pengajuan/tambah');
        }

        // checking nama pengajuan
        $namaPengajuan = $this->request->getPost('nama_pengaju');

        if ($namaPengajuan !== $this->user['nama']) {
            if ($namaPengajuan !== 'anonym') {
                $namaPengajuan = $this->user['nama'];
            }
        }

        $this->db->transBegin(); // Begin DB Transaction

        try {
            $this->pengajuan->save([
                'user_id' => $this->user['id'],
                'nama_pengaju' => $namaPengajuan,
                'judul_pengajuan' => $this->request->getPost('judul_pengajuan'),
                'isi_pengajuan' => $this->request->getPost('isi_pengajuan'),
            ]);

            foreach ($images as $i => $img) {
                if ($img->isValid() && !$img->hasMoved()) {
                    $files[$i] = $img->getRandomName();
                }
            }

            $pengajuan_id = $this->pengajuan->insertID(); // last insert id
            $img_dua = (array_key_exists(1, $files) ? $files[1] : null);
            $img_tiga = (array_key_exists(2, $files) ? $files[2] : null);
            $img_empat = (array_key_exists(3, $files) ? $files[3] : null);
            $img_lima = (array_key_exists(4, $files) ? $files[4] : null);
            $img_enam = (array_key_exists(5, $files) ? $files[5] : null);

            $this->bukti->save([
                'pengajuan_id' => $pengajuan_id,
                'img_satu' => $files[0],
                'img_dua' => $img_dua,
                'img_tiga' => $img_tiga,
                'img_empat' => $img_empat,
                'img_lima' => $img_lima,
                'img_enam' => $img_enam,

            ]);

            foreach ($images as $i => $img) {
                if ($img->isValid() && !$img->hasMoved()) {
                    $img->move('uploads', $files[$i]);
                }
            }

            $this->db->transCommit();
        } catch (\Exception $e) {
            $this->db->transRollback();

            // session()->setFlashdata('error-msg', $e->getMessage());
            session()->setFlashdata('error-msg', 'Terjadi kesalahan, data gagal ditambah.');
            return redirect()->to('/pengajuan');
        }

        session()->setFlashdata('msg', 'Pengajuan berhasil ditambah, silahkan menunggu untuk proses approval.');
        return redirect()->to('/pengajuan');
    }

    public function ubah($id)
    {
        $data = [
            'user' => $this->user,
            'title' => 'Ubah Data Pengajuan Saya',
            'data' => $this->pengajuan->find($id),
            'bukti' => $this->bukti->getBukti($id),
            'validation' => $this->validation
        ];

        // cegah id yang tidak jelas
        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        } else {
            // cek jika row_status = 0 | cegah akses form ubah.
            if ($data['data']['row_status'] == 0) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
            } else {
                // cek jika pengajuan sudah diproses tidak bisa diubah lagi
                if ($data['data']['status_pengajuan'] != 1) {
                    throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
                }
            }
        }

        return view('user/pengajuan/ubah_pengajuan', $data);
    }

    public function ubah_pengajuan()
    {
        $rules = [
            'judul_pengajuan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Perihal pengajuan wajib diisi.'
                ]
            ],
            'isi_pengajuan' => [
                'rules' => 'required|min_length[30]',
                'errors' => [
                    'required' => 'Isi pengajuan wajib diisi.',
                    'min_length' => 'Minimal 30 karakter.'
                ]
            ],
            'images' => [
                'rules' => 'max_size[images,1024]|is_image[images]|mime_in[images,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Anda mengupload file yang melebihi ukuran maksimal.',
                    'is_image' => 'Anda mengupload file yang bukan gambar.',
                    'mime_in' => 'Anda mengupload file yang bukan gambar.'
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/pengajuan/ubah/' . $this->request->getPost('id'))->withInput();
        }

        $id = $this->request->getPost('id');
        $namaPengajuan = $this->request->getPost('nama_pengaju');

        if ($namaPengajuan !== $this->user['nama']) {
            if ($namaPengajuan !== 'anonym') {
                $namaPengajuan = $this->user['nama'];
            }
        }

        $images = $this->request->getFileMultiple('images');
        $jumlahFile = count($images);

        if ($jumlahFile > 3) {
            session()->setFlashdata('err-files', '<span class="text-danger">Jumlah file yang anda upload melebihi aturan.</span>');
            return redirect()->to('/pengajuan/ubah/' . $id);
        }

        $this->db->transBegin(); // Begin DB Transaction

        try {
            $this->pengajuan->save([
                'id' => $id,
                'user_id' => $this->user['id'],
                'nama_pengaju' => $namaPengajuan,
                'judul_pengajuan' => $this->request->getPost('judul_pengajuan'),
                'isi_pengajuan' => $this->request->getPost('isi_pengajuan'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // karena upload file tetap mengembalikan string "" (kosong), jadi kita cek apakah file nya ada yg diupload
            if ($images[0]->getError() !== 4) {
                foreach ($images as $i => $img) {
                    if ($img->isValid() && !$img->hasMoved()) {
                        $files[$i] = $img->getRandomName();
                    }
                }

                // get data bukti
                $bukti = $this->bukti->getBukti($id);

                // hapus file lama
                unlink('uploads/' . $bukti['img_satu']);
                if ($bukti['img_dua'] != null) {
                    unlink('uploads/' . $bukti['img_dua']);
                }
                if ($bukti['img_tiga'] != null) {
                    unlink('uploads/' . $bukti['img_tiga']);
                }
                if ($bukti['img_empat'] != null) {
                    unlink('uploads/' . $bukti['img_empat']);
                }
                if ($bukti['img_lima'] != null) {
                    unlink('uploads/' . $bukti['img_lima']);
                }
                if ($bukti['img_enam'] != null) {
                    unlink('uploads/' . $bukti['img_enam']);
                }

                // update tbl_bukti
                $img_dua = (array_key_exists(1, $files) ? $files[1] : null);
                $img_tiga = (array_key_exists(2, $files) ? $files[2] : null);
                $img_empat = (array_key_exists(3, $files) ? $files[3] : null);
                $img_lima = (array_key_exists(4, $files) ? $files[4] : null);
                $img_enam = (array_key_exists(5, $files) ? $files[5] : null);



                $this->bukti->save([
                    'id' => $this->request->getPost('bukti_id'),
                    'img_satu' => $files[0],
                    'img_dua' => $img_dua,
                    'img_tiga' => $img_tiga,
                    'img_empat' => $img_empat,
                    'img_lima' => $img_lima,
                    'img_enam' => $img_enam,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                // move file baru
                foreach ($images as $i => $img) {
                    if ($img->isValid() && !$img->hasMoved()) {
                        $img->move('uploads', $files[$i]);
                    }
                }
            }

            $this->db->transCommit(); // Commit
        } catch (\Exception $e) {
            $this->db->transRollback(); // Rollback

            session()->setFlashdata('error-msg',  $e->getMessage());
            return redirect()->to('/pengajuan');
        }

        session()->setFlashdata('msg', 'Pengajuan berhasil diubah.');
        return redirect()->to('/pengajuan');
    }
}
