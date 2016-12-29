<?php
	$jumlahData = count($jumlah_record);
	$jml_halaman = $p->jmlHalaman($jumlahData,$batas);
	$link = $p->linkHal(@$_GET['halaman'],$jml_halaman);
	echo "<br\>Page : $link";
?>