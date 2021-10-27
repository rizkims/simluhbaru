<?php

namespace App\Models\KelembagaanPenyuluhan\Kecamatan;

use CodeIgniter\Model;
use \Config\Database;

class KecamatanModel extends Model
{
    protected $table      = 'tblbpp';
    protected $primaryKey = 'id';


    //protected $returnType     = 'array';
    //protected $useSoftDeletes = true;

    protected $allowedFields = [
        'kode_prop', 'satminkal', 'bentuk_lembaga', 'nama_bpp', 'kecamatan', 'alamat', 'tgl_berdiri', 'bln_berdiri',
        'thn_berdiri', 'status_gedung', 'kondisi_bangunan', 'koord_lu_ls', 'lu_ls', 'koord_bt', 'telp_bpp', 'email', 'website', 'ketua',
        'kode_koord_penyuluh', 'nama_koord_penyuluh', 'nama_koord_penyuluh_thl', 'koord_lainya_nip', 'koord_lainya_nama'
    ];


    // protected $useTimestamps = false;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;


    public function getKecTotal($kode_kab)
    {
        $db = Database::connect();
        $query = $db->query("select nama_dati2 as nama_kab from tbldati2 where id_dati2='$kode_kab'");
        $row   = $query->getRow();
        $query2 = $db->query("SELECT count(idpos) as jum_des FROM tb_posluhdes where kode_kab ='$kode_kab'");
        $row2   = $query2->getRow();
        $query3  = $db->query("select * , b.nama, c.deskripsi, a.tgl_update, a.alamat,f.jumgap,f.kode_bp3k,g.jumkep,d.jumpok,e.jumthl,h.jumpns,i.unit_kerja
                                from tblbpp a
                                left join tbldasar b on a.nama_koord_penyuluh=b.nip
                                left join tbldaerah c on a.kecamatan=c.id_daerah  
                                left join (select kode_bp3k, count(id_poktan) as jumpok from tb_poktan GROUP BY kode_bp3k)d on a.kode_bp3k=d.kode_bp3k
                                left join(select unit_kerja,count(id_thl) as jumthl from tbldasar_thl GROUP BY unit_kerja) e on  a.id=e.unit_kerja
                                left join(select kode_bp3k, count(id_gap) as jumgap from tb_gapoktan GROUP BY kode_bp3k)f on a.kode_bp3k=f.kode_bp3k
                                left join(select kode_kec,count(id_kep) as jumkep from tb_kep GROUP BY kode_kec )g on a.kecamatan=g.kode_kec
                                left join (select unit_kerja,count(id) as jumpns from tbldasar GROUP BY unit_kerja) h on a.id=h.unit_kerja and kode_kab='4' and status !='1' and status !='2' and status !='3'
                                left join(select unit_kerja,count(id_swa) as jumswa from tbldasar_swa GROUP BY unit_kerja) i on a.id=i.unit_kerja
                                where a.satminkal='$kode_kab' and a.kecamatan !='0'  
                                order by nama_bpp");
        $results = $query3->getResultArray();

        $data =  [
            'jum_des' => $row2->jum_des,
            'nama_kab' => $row->nama_kab,
            'table_data' => $results,
        ];

        return $data;
    }

    public function getProfilKec($kode_kec)
    {
        $db = Database::connect();
        $query  = $db->query("select * , b.nama, c.deskripsi, a.tgl_update, a.alamat,f.jumgap,f.kode_bp3k,g.jumkep,d.jumpok,e.jumthl,h.jumpns,i.unit_kerja
                                from tblbpp a
                                left join tbldasar b on a.nama_koord_penyuluh=b.nip
                                left join tbldaerah c on a.kecamatan=c.id_daerah  
                                left join (select kode_bp3k, count(id_poktan) as jumpok from tb_poktan GROUP BY kode_bp3k)d on a.kode_bp3k=d.kode_bp3k
                                left join(select unit_kerja,count(id_thl) as jumthl from tbldasar_thl GROUP BY unit_kerja) e on  a.id=e.unit_kerja
                                left join(select kode_bp3k, count(id_gap) as jumgap from tb_gapoktan GROUP BY kode_bp3k)f on a.kode_bp3k=f.kode_bp3k
                                left join(select kode_kec,count(id_kep) as jumkep from tb_kep GROUP BY kode_kec )g on a.kecamatan=g.kode_kec
                                left join (select unit_kerja,count(id) as jumpns from tbldasar GROUP BY unit_kerja) h on a.id=h.unit_kerja and kode_kab='4' and status !='1' and status !='2' and status !='3'
                                left join(select unit_kerja,count(id_swa) as jumswa from tbldasar_swa GROUP BY unit_kerja) i on a.id=i.unit_kerja
                                left join tblbpp_wil_kec j on a.kode_bp3k = j.kode_bp3k
                                where a.kecamatan='$kode_kec' and a.kecamatan !='0'  
                                order by nama_bpp");
        $row   = $query->getRowArray();
        return $row;
    }

    public function getKec($kode_kab)
    {
        $query = $this->db->query("select * from tbldaerah where id_dati2 LIKE '" . $kode_kab . "%' ORDER BY deskripsi ASC");
        $row   = $query->getResultArray();
        return $row;
    }

    public function getPenyuluhPNS($kode_kab)
    {

        $query = $this->db->query("select * from tbldasar where satminkal LIKE '" . $kode_kab . "%' and kode_kab='4'
                                    ORDER BY nama ASC ");
        $row   = $query->getResultArray();
        return $row;
    }

    public function getPenyuluhTHL($kode_kab)
    {

        $query = $this->db->query("select * from tbldasar_thl where satminkal LIKE '" . $kode_kab . "%' and penyuluh_di='kecamatan'
                                    ORDER BY nama ASC ");
        $row   = $query->getResultArray();
        return $row;
    }
}
