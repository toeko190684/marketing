<?php
require_once("config/koneksi.php");

$message = "<table border='1' style='border:1px solid black'><tr><tD>Hello</td></tr></table>";

$crud->sendmail("toeko.triyanto@morinaga-kino.co.id","halooo",$message);
?> 