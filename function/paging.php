<?php
class Paging{
    function cariPosisi($batas){
        $halaman = @$_GET['halaman'];
        if(empty($halaman)){
            $position = 0;
            $halaman = 1;
        }else{
            $position = ($halaman - 1) * $batas;
        }
        return $position;
    }
     
    function jmlHalaman($jmlData,$batas){
        $jmlHal = ceil($jmlData/$batas);
        return $jmlHal;
    }
     
    Function linkHal($halamanAktif,$jumlahHalaman){
        $link_halaman = "";
        $file = $_SERVER['PHP_SELF'];
         
        // Link First dan Previous
        $prev = $halamanAktif-1;
        if($halamanAktif < 2){
            $link_halaman .= "First &nbsp; Prev  ";
        }else{
            $link_halaman .= "<a href='$file?halaman=1'>FIRST</a> &nbsp; <a href='$file?halaman=$prev'>PREV</a> &nbsp; ";
        }
         
        // link halaman 1,2,3,...
        // Angka awal
        $angka = ($halamanAktif > 3 ? "... &nbsp; " : " ");
        for($i=$halamanAktif-2;$i<$halamanAktif;$i++){
            if ($i < 1 )continue;
            $angka .= "<a href='$file?halaman=$i'>$i</a> &nbsp; ";
        }
         
        // Angka tengah
        $angka .= "<b>$halamanAktif</b> &nbsp; ";
        for($i=$halamanAktif+1;$i<($halamanAktif+3);$i++){
            if($i > $jumlahHalaman) break;
            $angka .= "<a href='$file?halaman=$i'>$i</a> &nbsp;";
        }
         
        // ANgka Akhir
        $angka .= ($halamanAktif+2<$jumlahHalaman ? " ... &nbsp; <a href='$file?halaman=$jumlahHalaman'>$jumlahHalaman</a> &nbsp;" : "");
         
        $link_halaman .= $angka;
         
        // Link Next dan Last
        if($halamanAktif < $jumlahHalaman){
            $next = $halamanAktif+1;
            $link_halaman .= "<a href='$file?halaman=$next'>Next</a> &nbsp; <a href='$file?halaman=$jumlahHalaman'>Last</a> &nbsp;";
        }else{
            $link_halaman .="Next &nbsp; Last &nbsp;";
        }
        return $link_halaman;
    }
}
?>