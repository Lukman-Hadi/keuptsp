<?php

function getInfo($field)
{
    $ci = get_instance();
    $ci->db->where('id_perusahaan', 1);
    $rs = $ci->db->get('tbl_perusahaan')->row_array();
    return $rs[$field];
}
function nomorPencairan($urut,$kodeKeg){
    $kodeno = '900';
    $urutan = $urut+1;
    $kode = $kodeKeg;
    $bulan = date('m');
    $tahun = date('Y');
    $no = $kodeno.'/'.$urutan.'-'.$kode.'/'.$bulan.'/'.$tahun;
    return $no;
}

function terbilang($angka)
{
    $angka = (float)$angka;

    $bilangan = array(
        '',
        'Satu',
        'Dua',
        'Tiga',
        'Empat',
        'Lima',
        'Enam',
        'Tujuh',
        'Delapan',
        'Sembilan',
        'Sepuluh',
        'Sebelas'
    );

    if ($angka < 12) {
        return $bilangan[$angka];
    } else if ($angka < 20) {
        return $bilangan[$angka - 10] . ' Belas';
    } else if ($angka < 100) {
        $hasil_bagi = (int)($angka / 10);
        $hasil_mod = $angka % 10;
        return trim(sprintf('%s Puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
    } else if ($angka < 200) {
        return sprintf('seratus %s', terbilang($angka - 100));
    } else if ($angka < 1000) {
        $hasil_bagi = (int)($angka / 100);
        $hasil_mod = $angka % 100;
        return trim(sprintf('%s Ratus %s', $bilangan[$hasil_bagi], terbilang($hasil_mod)));
    } else if ($angka < 2000) {
        return trim(sprintf('seribu %s', terbilang($angka - 1000)));
    } else if ($angka < 1000000) {
        $hasil_bagi = (int)($angka / 1000); // karena hasilnya bisa ratusan jadi langsung digunakan rekursif
        $hasil_mod = $angka % 1000;
        return sprintf('%s Ribu %s', terbilang($hasil_bagi), terbilang($hasil_mod));
    } else if ($angka < 1000000000) {
        $hasil_bagi = (int)($angka / 1000000);
        $hasil_mod = $angka % 1000000;
        return trim(sprintf('%s Juta %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
    } else if ($angka < 1000000000000) {
        $hasil_bagi = (int)($angka / 1000000000);
        $hasil_mod = fmod($angka, 1000000000);
        return trim(sprintf('%s Milyar %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
    } else if ($angka < 1000000000000000) {
        $hasil_bagi = $angka / 1000000000000;
        $hasil_mod = fmod($angka, 1000000000000);
        return trim(sprintf('%s Triliun %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
    } else {
        return 'Wow...';
    }
}

function is_login()
{
    $ci = get_instance();
    if (!$ci->session->userdata('_id')) {
        return false;
    } else {
        return true;
    }
}
function alert($class, $title, $description)
{
    return '<div class="alert ' . $class . ' alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> ' . $title . '</h4>
                ' . $description . '
              </div>';
}
// untuk chek akses level pada modul peberian akses
function checked_akses($id_user_level, $id_menu)
{
    $ci = get_instance();
    $ci->db->where('id_jabatan', $id_user_level);
    $ci->db->where('id_menu', $id_menu);
    $data = $ci->db->get('tbl_levels');
    if ($data->num_rows() > 0) {
        return "checked='checked'";
    }
}
function checked_privilege($idJabatan, $idProgress)
{
    $ci = get_instance();
    $ci->db->where('id_jabatan', $idJabatan);
    $ci->db->where('id_progress', $idProgress);
    $data = $ci->db->get('tbl_privilege');
    if ($data->num_rows() > 0) {
        return "checked='checked'";
    }
}
function privilegeCheck()
{
    $ci = get_instance();
    $ci->db->select('pr._id,nama_progress');
    $ci->db->from('tbl_privilege p');
    $ci->db->join('tbl_progress pr', 'pr._id = p.id_progress');
    $ci->db->where('p.id_jabatan', $ci->session->userdata('id_jabatan'));
    return $ci->db->get()->result();
}
function superCheck(){
    $can = privilegeCheck();
    foreach($can as $c){
        $userCan[] = $c->nama_progress;
    }
    if(in_array('All',$userCan)){
        return true;
    }else{
        return false;
    }
}
function canApproveCheck(){
    $ci = get_instance();
    $ci->load->model('Approve_model','amodel');
    $can = privilegeCheck();
    $userCan = array();
    foreach($can as $c){
        $userCan[] = $c->_id;
    }
    $canApprove = $ci->amodel->canApproveCheck($userCan);
    $userCanOrdinal = array();
    foreach($canApprove as $approve){
        $userCanOrdinal[] = $approve->ordinal-1;
    }
    // $test = $ci->amodel->getProgress($userCanOrdinal);
    // $userCanApprove = array();
    // foreach($test as $t){
    //     $userCanApprove[] = $t->id_progress;
    // }
    return $userCanOrdinal;
}
function cekAlur($ord){
    $ci = get_instance();
    $ci->db->select('status');
    $ci->db->from('tbl_alur');
    $ci->db->where('ordinal',$ord);
    return $ci->db->get()->row();
}


function seo_title($s)
{
    $c = array(' ');
    $d = array('-', '/', '\\', ',', '.', '#', ':', ';', '\'', '"', '[', ']', '{', '}', ')', '(', '|', '`', '~', '!', '@', '%', '$', '^', '&', '*', '=', '?', '+', '–');
    $s = str_replace($d, '', $s); // Hilangkan karakter yang telah disebutkan di array $d
    $s = strtolower(str_replace($c, '-', $s)); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua
    return $s;
}

function tgl_indo($tgl)
{
    $bln = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
    $tanggal = substr($tgl, 8, 2);
    $bulan = substr($tgl, 5, 2);
    $tahun = substr($tgl, 0, 4);
    return $tanggal . ' ' . $bln[(int)$bulan - 1] . ' ' . $tahun;
}

function bln_indo($tgl)
{
    $bln = array("Jan", "Feb", "Maret", "April", "Mei", "Juni", "Juli", "Agust", "Sep", "Okt", "Nov", "Des");
    $tanggal = substr($tgl, 8, 2);
    $bulan = substr($tgl, 5, 2);
    $tahun = substr($tgl, 0, 4);
    return $tanggal . ' ' . $bln[(int)$bulan - 1] . ' ' . $tahun;
}
function bulan($bln)
{
    switch ($bln) {
        case 1:
            return "Januari";
            break;
        case 2:
            return "Februari";
            break;
        case 3:
            return "Maret";
            break;
        case 4:
            return "April";
            break;
        case 5:
            return "Mei";
            break;
        case 6:
            return "Juni";
            break;
        case 7:
            return "Juli";
            break;
        case 8:
            return "Agustus";
            break;
        case 9:
            return "September";
            break;
        case 10:
            return "Oktober";
            break;
        case 11:
            return "November";
            break;
        case 12:
            return "Desember";
            break;
    }
}
function jam($tgl)
{
    $jam = substr($tgl, 11, 2);
    $menit = substr($tgl, 14, 2);
    $detik = substr($tgl, 17, 2);
    return $jam . ':' . $menit . ':' . $detik;
}


if (!function_exists('time_ago')) {
    function time_ago($time)
    {
        $periods = array("Detik", "Menit", "Jam", "Hari", "Minggu", "Bulan", "Tahun", "Decade");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");
        $now = time();
        $difference     = $now - $time;
        $tense         = "ago";
        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }
        $difference = round($difference);
        if ($difference != 1) {
            $periods[$j] .= "";
        }
        return "$difference $periods[$j] yang lalu";
    }
}


if (!function_exists('prefix_selular')) {
    function prefix_selular($id)
    {
        $simpati = array("0811", "0812", "0813", "0821", "0822");
        $as = array("0823", "0852", "0853", "0851");
        $mentari = array("0855", "0858", "0815", "0816");
        $im3 = array("0856", "0857");
        $m2 = array("0814");
        $xl = array("0817", "0818", "0819", "0859", "0877", "0878");
        $axis = array("0838", "0831", "0832", "0833");
        $tree = array("0895", "0896", "0897", "0898", "0899");
        $smartfreen = array("0881", "0882", "0883", "0884", "0885", "0886", "0887", "0888", "0889");
        $ceria = array("0828");
        $smarttelecom = array("0881", "0882", "0883", "0884", "0885", "0886", "0887");
        $data = array();
        $data[] = array(
            'simpati'       => $simpati,
            'as'            => $as,
            'mentari'       => $mentari,
            'im3'           => $im3,
            'm2'            => $m2,
            'xl'            => $xl,
            'axis'          => $axis,
            'tree'          => $tree,
            'smartfreen'    => $smartfreen,
            'ceria'         => $ceria,
            'smarttelecom'  => $smarttelecom
        );
        $value[] = array();
        foreach ($data as $key => $value) {
            if (in_array(substr($id, 0, 4), $value['simpati'])) {
                $val = 'Simpati';
            } else if (in_array(substr($id, 0, 4), $value['as'])) {
                $val = 'As';
            } else if (in_array(substr($id, 0, 4), $value['mentari'])) {
                $val = 'Mentari';
            } else if (in_array(substr($id, 0, 4), $value['im3'])) {
                $val = 'IM3';
            } else if (in_array(substr($id, 0, 4), $value['m2'])) {
                $val = 'M2';
            } else if (in_array(substr($id, 0, 4), $value['xl'])) {
                $val = 'XL Axiata';
            } else if (in_array(substr($id, 0, 4), $value['axis'])) {
                $val = 'Axis';
            } else if (in_array(substr($id, 0, 4), $value['tree'])) {
                $val = 'Tree';
            } else if (in_array(substr($id, 0, 4), $value['smartfreen'])) {
                $val = 'Smartfreen';
            } else if (in_array(substr($id, 0, 4), $value['ceria'])) {
                $val = 'Ceria';
            } else if (in_array(substr($id, 0, 4), $value['smarttelecom'])) {
                $val = 'Smart Telecom';
            }
        }
        return $val;
    }
}

if (!function_exists('search_prefix')) {
    function search_prefix($data, $array, $flag_invert = 0)
    {
        $found = [];
        foreach ($array as $key => $value) {
            if (isset($flag_invert) && $flag_invert == 1) {
                if (!preg_match($basis, $val)) {
                    $found[] = $val;
                }
            } else {
                if (preg_match($basis, $val)) {
                    $found[] = $val;
                }
            }
        }
        return $found;
    }
}

function arraySearch($array, $search)
{
    foreach ($array as $a) {
        if (strstr($a, $search)) {
            echo $a;
        }
    }
    return false;
}

function antrian($id, $date)
{
    $ci = get_instance();
    $today = date('Y-m-d');
    $query = "SELECT count(_id)as maxantrian FROM tbl_counter where id_loket='$id' AND date = '$today'";
    $data = $ci->db->query($query)->row_array();
    $kode = $data['maxantrian'];
    $noUrut = (int) substr($kode, -3);
    $noUrut += 1;
    $kodeBaru = sprintf("%03s", $noUrut);
    return $kodeBaru;
}

function jmlbooking($id)
{
    $ci = get_instance();
    $today = date('Y-m-d');
    $query = "SELECT count(id_layanan)as jml FROM tbl_booking INNER JOIN tbl_layanan ON tbl_booking.id_layanan=tbl_layanan._id INNER JOIN tbl_loket ON tbl_layanan.id_menumpp=tbl_loket.id_menumpp where tbl_loket.id_menumpp='$id' AND DATE_FORMAT(tbl_booking.createdate,'%Y-%m-%d') = '$today'";
    $data = $ci->db->query($query)->row_array();
    $jml = $data['jml'];
    return $jml;
}

function jmlantrian($id)
{
    $ci = get_instance();
    $today = date('Y-m-d');
    $query = "SELECT COUNT(id_loket) as jml FROM tbl_counter INNER JOIN tbl_loket ON tbl_counter.id_loket=tbl_loket._id where tbl_loket.id_menumpp='$id' AND tbl_counter.date= '$today'";
    $data = $ci->db->query($query)->row_array();
    $jml = $data['jml'];
    return $jml;
}

function allbooking()
{
    $ci = get_instance();
    $today = date('Y-m-d');
    $query = "SELECT count(id_layanan)as jml FROM tbl_booking INNER JOIN tbl_layanan ON tbl_booking.id_layanan=tbl_layanan._id INNER JOIN tbl_loket ON tbl_layanan.id_menumpp=tbl_loket.id_menumpp where DATE_FORMAT(tbl_booking.createdate,'%Y-%m-%d') = '$today'";
    $data = $ci->db->query($query)->row_array();
    $jml = $data['jml'];
    return $jml;
}
function allantrian()
{
    $ci = get_instance();
    $today = date('Y-m-d');
    $query = "SELECT COUNT(id_loket) as jml FROM tbl_counter INNER JOIN tbl_loket ON tbl_counter.id_loket=tbl_loket._id where tbl_counter.date= '$today'";
    $data = $ci->db->query($query)->row_array();
    $jml = $data['jml'];
    return $jml;
}
function allterlayani()
{
    $ci = get_instance();
    $today = date('Y-m-d');
    $query = "SELECT COUNT(antrian) as jml FROM tbl_counter INNER JOIN tbl_loket ON tbl_counter.id_loket=tbl_loket._id where tbl_counter.status <> 1 AND tbl_counter.date= '$today'";
    $data = $ci->db->query($query)->row_array();
    $jml = $data['jml'];
    return $jml;
}

function alltdkterlayani()
{
    $ci = get_instance();
    $today = date('Y-m-d');
    $query = "SELECT COUNT(antrian) as jml FROM tbl_counter INNER JOIN tbl_loket ON tbl_counter.id_loket=tbl_loket._id where tbl_counter.status = 1 AND tbl_counter.date= '$today'";
    $data = $ci->db->query($query)->row_array();
    $jml = $data['jml'];
    return $jml;
}
