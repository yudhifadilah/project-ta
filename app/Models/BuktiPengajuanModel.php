<?php

namespace App\Models;

use CodeIgniter\Model;

class BuktiPengajuanModel extends Model
{
    protected $table = 'tbl_bukti_pengajuan';
    protected $useTimestamps = true;
    protected $allowedFields = ['id', 'pengajuan_id', 'img_satu', 'img_dua', 'img_tiga', 'img_empat','img_lima','img_enam', 'updated_at', 'deleted_at', 'row_status'];

    public function getBukti($pengajuan_id)
    {
        $this->where('pengajuan_id', $pengajuan_id);
        return $this->get()->getRowArray();
    }

    public function soft_delete($pengajuan_id)
    {
        $builder = $this->table('tbl_bukti_pengajuan');
        $builder->set('row_status', 0);
        $builder->set('deleted_at', date('Y-m-d H:i:s'));
        $builder->where('pengajuan_id', $pengajuan_id);
        $builder->update();
    }
}
