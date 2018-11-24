<ul class="nav nav-sidebar">
<li id="output"></li>
   <?php
   		if (isset($_SESSION['pb'])) {
   			$link = array("","add_mahasiswa","mahasiswa","absen","absensi","req_catatan","catatan", "katasandi&id=$_SESSION[id]","keluar");
				$name = array("","Tambah Mahasiswa","Daftar Mahasiswa","Absen","Lihat Absensi","Catatan","Lihat Catatan","Ubah Katasandi", "Keluar");

				for ($i=1; $i <= count($link)-1 ; $i++) {
					if (strcmp($page, "$link[$i]")==0) {
								$status = "class='active'";
							} else {
								$status = "";
							}
					echo "<li $status><a href='$link[$i]'>$name[$i]</a></li>";
				}
   		} elseif (isset($_SESSION['sw'])) {
				$this_day = date("d");
				$sql = "SELECT * FROM data_absen NATURAL LEFT JOIN tanggal WHERE nama_tgl='$this_day' AND id_user='$_SESSION[id]'";
				$query = $conn->query($sql);

				$query_tday = $query->fetch(PDO::FETCH_ASSOC);

				$link=array("","absen","absensi","tambah_catatan","catatan","keluar");
				$name=array("","Absen","Absensiku","Tambah Catatan","Catatan","Keluar");
			
				for ($i=1; $i <= count($link)-1 ; $i++) {
					if (strcmp($page, "$link[$i]")==0) {
								$status = "class='active'";
							} else {
								$status = "";
							}
						if ($query->rowCount()==0 && $link[$i]==="absen") {
						$warning = "<img src='./lib/img/warning.png' width='20' />";
					} else {
						$warning = "";
					}
					echo "<li $status><a href='$link[$i]'>$name[$i] $warning</a></li>";
				}
   		}
	?>
</ul>