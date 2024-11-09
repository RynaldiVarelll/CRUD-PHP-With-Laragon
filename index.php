<?php
$host    = "localhost";
$user    = "root";
$pass    = "";
$db      = "akademik";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database");
}
$nis = "";
$nama = "";
$alamat = "";
$jurusan = "";   
$success = "";
$error = "";

if (isset($_GET['op'])) {   
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id = $_GET['id'];
    $sql1 = "DELETE FROM siswa WHERE id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $success = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan hapus data";
    }
}

if ($op == 'edit') {
    $id = $_GET['id'];
    $sql1 = "SELECT * FROM siswa WHERE id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    if ($r1) {
        $nis = $r1['nis'];
        $nama = $r1['nama'];
        $alamat = $r1['alamat'];
        $jurusan = $r1['jurusan'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) {
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $jurusan = $_POST['jurusan'];

    if ($nis && $nama && $alamat && $jurusan) {
        if ($op == 'edit') {
            $sql1 = "UPDATE siswa SET nis = '$nis', nama = '$nama', alamat = '$alamat', jurusan = '$jurusan' WHERE id = '$id'";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $success = "Data berhasil diupdate";
                // Reset form after update
                $nis = "";
                $nama = "";
                $alamat = "";
                $jurusan = "";
                $op = ""; // reset operation
            } else {
                $error = "Data gagal diupdate";
            }
        } else {
            $sql1 = "INSERT INTO siswa(nis, nama, alamat, jurusan) VALUES ('$nis', '$nama', '$alamat', '$jurusan')";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $success = "Berhasil memasukan data terbaru";
            } else {
                $error = "Gagal memasukan data terbaru";
            }
        }
    } else {
        $error = "Silakan masukan semua data";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin-top: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .btn-custom {
            border-radius: 50px;
            padding: 10px 20px;
            transition: background-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .alert {
            border-radius: 10px;
        }
        .table th {
            background-color: #e9ecef;
            transition: background-color 0.3s;
        }
        .table th:hover {
            background-color: #dee2e6;
        }
        .table td {
            transition: background-color 0.3s;
        }
        .table td:hover {
            background-color: #f1f3f5;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alert = document.querySelector('.alert');
                if (alert) {
                    alert.style.display = 'none';
                }
            }, 4000); // Menghilangkan setelah 4 detik
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Create / Edit Data</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success ?>
                    </div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" class="form-control" id="nis" name="nis" value="<?php echo htmlspecialchars($nis) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($nama) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo htmlspecialchars($alamat) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="jurusan" class="form-label">Jurusan</label>
                        <select class="form-select" name="jurusan" id="jurusan" required>
                            <option value="">Pilih Jurusan</option>
                            <option value="Rekayasa Perangkat Lunak" <?php if ($jurusan == "Rekayasa Perangkat Lunak") echo "selected"?>>Rekayasa Perangkat Lunak</option>
                            <option value="Teknik Komputer Jaringan" <?php if ($jurusan == "Teknik Komputer Jaringan") echo "selected"?>>Teknik Komputer Jaringan</option>
                            <option value="Multimedia" <?php if ($jurusan == "Multimedia") echo "selected"?>>Multimedia</option>
                            <option value="Perbankan Keuangan Mikro" <?php if ($jurusan == "Perbankan Keuangan Mikro") echo "selected"?>>Perbankan Keuangan Mikro</option>
                        </select>
                    </div>
                    <button type="submit" name="simpan" class="btn btn-primary btn-custom">Simpan Data</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="mb-0 text-white">Data Siswa</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Jurusan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "SELECT * FROM siswa ORDER BY id DESC";
                        $q2 = mysqli_query($koneksi, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id = $r2['id'];
                            $nis = $r2['nis'];
                            $nama = $r2['nama'];
                            $alamat = $r2['alamat'];
                            $jurusan = $r2['jurusan'];
                        ?>
                            <tr>
                                <td><?php echo $urut++ ?></td>
                                <td><?php echo htmlspecialchars($nis) ?></td>
                                <td><?php echo htmlspecialchars($nama) ?></td>
                                <td><?php echo htmlspecialchars($alamat) ?></td>
                                <td><?php echo htmlspecialchars($jurusan) ?></td>
                                <td>
                                    <a href="index.php?op=edit&id=<?php echo $id ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="index.php?op=delete&id=<?php echo $id ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>