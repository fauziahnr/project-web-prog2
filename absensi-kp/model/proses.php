<?php 
session_start();
date_default_timezone_set('Asia/Jakarta');
include '../lib/db/dbconfig.php';


/*

		LOGIN

*/
if (isset($_POST['login'])) {
	$username = ($_POST['username']);
	$pwd = sha1($_POST['pwd']);

	$sql = "SELECT * FROM user WHERE username='$username' AND pwd_user='$pwd'";
	$query = $conn->query($sql);
	$hitung = $query->rowCount();
	if ($hitung!==0) {
		$ambil = $query->fetch(PDO::FETCH_ASSOC);
		extract($ambil);

		if ($level_user==='pb') {
			$_SESSION['pb']=$username;
			$_SESSION['id']=$id_user;
			header("Location:../index.php");
		} elseif ($level_user==='sw') {
			$_SESSION['sw']=$username;
			$_SESSION['id']=$id_user;
			header("Location:../index.php");
		}
	}else{
		header("location:../index.php?log=2");
	}
}
elseif (isset($_GET['logout'])) {
	session_destroy();
	
}

/*

 			PROSES UNTUK SISWA


*/
elseif (isset($_GET['absen'])) {
	if($_GET['absen']==1){
		$month = date("m");
		$day_tgl = date("d");
		$day = date("N");
		$hour = date("H.i")." WIB";
		$status = "Menunggu";
		$sql = "INSERT INTO data_absen (
			id_user,
			id_bln,
			id_hri,
			id_tgl,
			jam_msk,
			st_jam_msk) VALUES (
			:id,
			:bln,
			:hari,
			:tgl,
			:jam,
			:sts )";
		if ($statement = $conn->prepare($sql)) {
			$statement->bindParam(':id', $_SESSION['id']);
			$statement->bindParam(':bln', $month);
			$statement->bindParam(':hari', $day);
			$statement->bindParam(':tgl', $day_tgl);
			$statement->bindParam(':jam', $hour);
			$statement->bindParam(':sts', $status);
			if ($statement->execute()) {
				// Absen sukses
				$conn=null;
				header("location:../absen&ab=1");
			} else {
				header("location:../absen&ab=2");
			}
		}else {
			header("location:../absen&ab=2");
		}
		
	} else {
		// ABSEN = UPDATE JAM PULANG
		$id_user =  $_SESSION['id'];
		$id_bln = date("m");
		$day_tgl = date("d");
		$day = date("N");
		$hour = date("H.i")." WIB";
		$status = "Menunggu";
		$sql = "UPDATE data_absen SET jam_klr= :jam , st_jam_klr= :sts WHERE id_user='$id_user' AND id_tgl='$day_tgl' AND id_bln='$id_bln'";

		if ($statement= $conn->prepare($sql)) {
			$statement->bindParam(':jam', $hour);
			$statement->bindParam(':sts', $status);

			if ($statement->execute()) {
				$conn=null;
				header("location:../absen&ab=1");

			} else {
				header("location:../absen&ab=2");
			}
		} else {
			header("location:../absen&ab=2");
		}
		
	}
}
/*

		SIMPAN CATATAN

*/
elseif (isset($_POST['simpan_note'])) {
	
	if ($note !== "") {
		$id_user = $_SESSION['id'];
		$note = $_POST['note'];
		$month = date("m");
		$day_tgl = date("d");
		$day = date("N");
		$id_note = "NULL";
		$status = "Menunggu";
		$sql= "INSERT INTO catatan (id_cat,
			id_user,
			id_bln,
			id_hri,
			id_tgl,
			isi_cat,
			status_cat) VALUES (:id ,
			:uname ,
			:bln ,
			:hri ,
			:tgl ,
			:isi ,
			:sts )";
		if ($statement = $conn->prepare($sql)) {
			$statement->bindParam(':id', $id_note);
			$statement->bindParam(':uname', $id_user);
			$statement->bindParam(':bln', $month);
			$statement->bindParam(':hri', $day);
			$statement->bindParam(':tgl', $day_tgl);
			$statement->bindParam(':isi', $note);
			$statement->bindParam(':sts', $status);

			if ($statement->execute()) {
				header("location:../catatan&st=1");
				$statement=null;
			} else {
				header("location:../catatan&st=2");
			}
		}else {
			header("location:../catatan&st=2");
		}
	} else {
		header("location:../catatan&st=2");
	}
}

/*

		PROSES UNTUK PEMBIMBING(ADMIN)

*/
elseif (isset($_GET['accx_absen'])) {
	if (!isset($_SESSION['pb'])) {
		header("location:home");
	}else{
		$id_absen=$_GET['accx_absen'];
		$type = $_GET['type'];
		if ($type==="in") {
			$query = "UPDATE data_absen SET st_jam_msk= :sts WHERE id_absen='$id_absen'";
			if ($statement = $conn->prepare($query)) {
				$status = "Dikonfirmasi";
				$statement->bindParam(':sts', $status);
				if ($statement->execute()) {
					// sukses update
					echo "Sukses";
				}else{
					//gagal update
					echo "Gagal";
				}
				$conn=null;
			} else {
				echo "Ga siap";
			}
			
		} else {
			$query = "UPDATE data_absen SET st_jam_klr= :sts WHERE id_absen='$id_absen'";
			if ($statement = $conn->prepare($query)) {
				$status = "Dikonfirmasi";
				$statement->bindParam(':sts', $status);
				if ($statement->execute()) {
					// sukses update
					echo "Sukses";
				}else{
					//gagal update
					echo "Gagal";
				}
				$conn=null;
			} else {
				echo "Ga siap";
			}
		}
	}
}
/*

		ACC ABSEN PEMBIMBING

*/
elseif (isset($_GET['acc_absen'])) {
	if (!isset($_SESSION['pb'])) {
		header("location:home");
	}else{
		$id_absen=$_GET['acc_absen'];
		$type = $_GET['type'];
		if ($type==="in") {
			$query = "UPDATE data_absen SET st_jam_msk= :sts WHERE id_absen='$id_absen'";
			if ($statement = $conn->prepare($query)) {
				$status = "Dikonfirmasi";
				$statement->bindParam(':sts', $status);
				if ($statement->execute()) {
					// sukses update
					header("location:../absen&ab=1");
				}else{
					//gagal update
					header("location:../absen&ab=2");
				}
				$conn=null;
			} else {
				header("location:../absen&ab=2");
			}
			
		} else {
			$query = "UPDATE data_absen SET st_jam_klr= :sts WHERE id_absen='$id_absen'";
			if ($statement = $conn->prepare($query)) {
				$status = "Dikonfirmasi";
				$statement->bindParam(':sts', $status);
				if ($statement->execute()) {
					// sukses update
					header("location:../absen&ab=1");
				}else{
					//gagal update
					header("location:../absen&ab=2");
				}
				$conn=null;
			} else {
				header("location:../absen&ab=2");
			}
		}
	}
}

/*

		DECLINE ABSEN

*/
elseif (isset($_GET['dec_absen'])) {
	if (!isset($_SESSION['pb'])) {
		header("location:home");
	}else{
		$id_absen=$_GET['dec_absen'];
		$type = $_GET['type'];
		if ($type==="in") {
			$query = "UPDATE data_absen SET st_jam_msk= :sts WHERE id_absen='$id_absen'";
			if ($statement = $conn->prepare($query)) {
				$status = "Ditolak";
				$statement->bindParam(':sts', $status);
				if ($statement->execute()) {
					// sukses update
					header("location:../absen&ab=3");
				}else{
					//gagal update
					header("location:../absen&ab=2");
				}
				$conn=null;
			} else {
				header("location:../absen&ab=2");
			}
			
		} else {
			$query = "UPDATE data_absen SET st_jam_klr= :jam WHERE id_absen='$id_absen'";
			if ($statement = $conn->prepare($query)) {
				$status = "Ditolak";
				$statement->bindParam(':jam', $status);
				if ($statement->execute()) {
					// sukses update
					header("location:../absen&ab=3");
				}else{
					//gagal update
					header("location:../absen&ab=2");
				}
				$conn=null;
			} else {
				header("location:../absen&ab=2");
			}
		}
	}
}


// acc Note
elseif (isset($_GET['acc_note'])) {
	if (!isset($_SESSION['pb'])) {
		header("location:home");
	}else{
		$id_note=$_GET['acc_note'];
		$sql = "UPDATE catatan SET status_cat= :sts WHERE id_cat='$id_note'";
		if ($statement = $conn->prepare($sql)) {
			$status= "Dikonfirmasi";
			$statement->bindParam(':sts', $status);
			if ($statement->execute()) {
				header("location:../req_catatan&ab=1");
			}else{
				//gagal update
				header("location:../req_catatan&ab=2");
			}
			$conn=null;
		} else {
			header("location:../req_catatan&ab=2");
		}
		
	}
}


// Decline Note
elseif (isset($_GET['dec_note'])) {
	if (!isset($_SESSION['pb'])) {
		header("location:../home");
	}else{
		$id_note=$_GET['dec_note'];
		$sql = "UPDATE catatan SET status_cat= :sts WHERE id_cat='$id_note'";
		if ($statement = $conn->prepare($sql)) {
			$status= "Ditolak";
			$statement->bindParam(':sts', $status);
			if ($statement->execute()) {
				header("location:../req_catatan&ab=3");
			}else{
				//gagal update
				header("location:../req_catatan&ab=2");
			}
			$conn=null;
		} else {
			header("location:../req_catatan&ab=2");
		}
		
	}
}



// Tambah siswa
elseif (isset($_POST['add_siswa'])) {
	$query = $conn->query("SELECT id_user FROM user ORDER BY id_user DESC");
	$ambil = $query->fetch(PDO::FETCH_ASSOC);
	$id = $ambil['id_user']+1;
	$nis = $_POST['nis'];
	$username = $_POST['username'];
	$pwd = sha1($_POST['pwd_cek']);
	$pwd_cek = sha1($_POST['pwd']);
	
	$nama = $_POST['nama'];
	$jk = $_POST['jk'];
	$sklh = $_POST['sklh'];
	$level = "sw";
	
	$sql_user = "INSERT INTO user (id_user,
		username,
		pwd_user,
		level_user) VALUES(:id ,
		:uname ,
		:pwd ,
		:lvl )";
	$sql_detail = "INSERT INTO detail_user (id_user,
		nis_user,
		name_user,
		sklh_user,
		jk_user) VALUES(:id ,
		:nis ,
		:uname ,
		:sklh ,
		:jk )";
	if ($nis==="" || $username==="" || $pwd==="" || $nama==="" || $jk==="" || $sklh==="") {
		header("location:../add_siswa&st=4");
	}else {
		if ($pwd !== "$pwd_cek") {
			header("location:../add_siswa&st=5");
		} else {
			$cek =$conn->query("SELECT * FROM user WHERE username='$username'")->rowCount();
			$cek_nis = $conn->query("SELECT (nis_user) FROM detail_user WHERE nis_user='$nis'")->rowCount();
			if ($cek==0) {
				if ($cek_nis==0) {
					if ($statement= $conn->prepare($sql_user) AND $statement1 = $conn->prepare($sql_detail)) {
						$statement->bindParam(':id', $id);
						$statement->bindParam(':uname', $username);
						$statement->bindParam(':pwd', $pwd);
						$statement->bindParam(':lvl', $level);
						
						$statement1->bindParam(':id', $id);
						$statement1->bindParam(':nis', $nis);
						$statement1->bindParam(':uname', $nama);
						$statement1->bindParam(':sklh', $sklh);
						$statement1->bindParam(':jk', $jk);

						if ($statement->execute() && $statement1->execute()) {
							header("location:../add_siswa&st=1");
						} else {
							header("location:../add_siswa&st=2");
						}
					} else {
						header("location:../add_siswa&st=2");
					}
				} else {
					header("location:../add_siswa&st=6");
				}
				$conn=null;
			} else {
				header("location:../add_siswa&st=3");
			}
		}
	}
}


// Edit siswa
elseif (isset($_POST['edit_siswa'])) {
	$id = $_POST['id_user'];
	$nis = $_POST['nis'];
	$nama = $_POST['nama'];
	$jk = $_POST['jk'];
	$sklh = $_POST['sklh'];

	$sql_detail = "UPDATE detail_user SET nis_user= :nis , name_user= :nama , sklh_user= :sklh , jk_user= :jk WHERE id_user='$id'";
	if ($nis==="" || $id==="" || $nama==="" || $jk==="" || $sklh==="") {
		header("location:../siswa&id=$id&st=4");
	}else {
		if ($statement= $conn->prepare($sql_detail)) {
				//$statement->bind_param("ssss", $nis, $nama, $sklh, $jk);
				$statement->bindParam(':nis', $nis);
				$statement->bindParam(':nama', $nama);
				$statement->bindParam(':sklh', $sklh);
				$statement->bindParam(':jk', $jk);
				if ($statement->execute()) {
					header("location:../mahasiswa&id_siswa=$id&st=1");
				} else {
					header("location:../mahasiswa&id_siswa=$id&st=2");
				}
			} else {
				header("location:../mahasiswa&id_siswa=$id&st=2");
			}
		$conn=null;
	}
}



// Delete siswa
elseif (isset($_GET['del_mahasiswa'])) {
	$id = $_GET['del_mahasiswa'];
	$sql_d = "DELETE FROM detail_user WHERE id_user= :id ";
	$sql_u = "DELETE FROM user WHERE id_user= :id ";
	if ($stmt= $conn->prepare($sql_d) AND $stmt1 = $conn->prepare($sql_u)) {
		$stmt->bindParam(':id', $id);
		$stmt1->bindParam(':id', $id);
		if ($stmt->execute() && $stmt1->execute()) {
			header("location:../mahasiswa&st=3");
		} else {
			header("location:../mahasiswa&st=5");
		}
	} else {
		header("location:../mahasiswa&st=5");
	}
}



// Ganti password
elseif (isset($_POST['change-pwd'])) {
	$id = $_POST['id'];
	$new = sha1($_POST['new-pwd']);
	$cek = sha1($_POST['new-pwd-cek']);
	if (!empty($new) || !empty($cek) || !empty($id)) {
			if ($new !== $cek) {
				header("location:../katasandi&id=$id&st=5");
			} else {
				$sqlc = "UPDATE user SET pwd_user= :pwd WHERE id_user='$id'";
				if ($update= $conn->prepare($sqlc)) {
					$update->bindParam(':pwd', $new);
					if ($update->execute()) {
						header("location:../katasandi&id=$id&st=1");
					} else {
						header("location:../katasandi&id=$id&st=2");
					}
				} else {
					header("location:../katasandi&id=$id&st=2");
				}
			}
	} else {
		header("location:../katasandi&id=$id&st=4");
	}
}
else {
	echo "<script>window.alert('ooowwwww... tidak bisaaaa~ ');window.location=('../home');</script>";
}
?>