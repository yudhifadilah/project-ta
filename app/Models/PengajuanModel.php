<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanModel extends Model
{
    protected $table = 'tbl_pengajuan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'nama_pengajuan', 'judul_pengajuan', 'isi_pengajuan',
        'created_at', 'updated_at', 'deleted_at', 'row_status'
    ];
    protected $useTimestamps = true;

    public function getPengajuan($id)
    {
        return $this->find($id);
    }

    public function soft_delete($id)
    {
        return $this->update($id, ['deleted_at' => date('Y-m-d H:i:s'), 'row_status' => 0]);
    }
}
