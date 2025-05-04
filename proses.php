<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Jika menggunakan Composer
// require 'path/to/PHPMailer/src/Exception.php'; // Jika manual
// require 'path/to/PHPMailer/src/PHPMailer.php';
// require 'path/to/PHPMailer/src/SMTP.php';
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars($_POST['nama']);
    $ttl = htmlspecialchars($_POST['ttl']);
    $telepon = htmlspecialchars($_POST['telepon']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $orang_tua = htmlspecialchars($_POST['orang_tua']);
    $telepon_orang_tua = htmlspecialchars($_POST['telepon_orang_tua']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $program = htmlspecialchars($_POST['program']);
    $pembelajaran = htmlspecialchars($_POST['pembelajaran']);
    $pertemuan = htmlspecialchars($_POST['pertemuan']);

    // Menghandle file upload
    $foto_nama = $_FILES['foto']['name'];
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $foto_path = "uploads/" . basename($foto_nama);
    
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }

    if (move_uploaded_file($foto_tmp, $foto_path)) {
        $foto_uploaded = true;
    } else {
        $foto_uploaded = false;
    }

    // Konfigurasi SMTP
    $mail = new PHPMailer(true);
    
    try {
        // Konfigurasi Server Email
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Ganti dengan SMTP provider Anda
        $mail->SMTPAuth = true;
        $mail->Username = 'yoseptest27@gmail.com'; // Ganti dengan email pengirim
        $mail->Password = 'qcmnbmdlmkolbnyv'; // Ganti dengan password email pengirim atau App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Pengaturan Email Pengirim dan Penerima
        $mail->setFrom('yoseptest27@gmail.com', 'Admin Pendaftaran');
        $mail->addAddress($email, $nama);
        
        // Lampiran Foto (Opsional)
        if ($foto_uploaded) {
            $mail->addAttachment($foto_path);
        }

        // Konten Email
        $mail->isHTML(true);
        $mail->Subject = "Konfirmasi Pendaftaran Kursus";
        $mail->Body = "
                    <html>
                    <head>
                        <style>
                            table {
                                width: 100%;
                                border-collapse: collapse;
                                font-family: Arial, sans-serif;
                            }
                            th, td {
                                border: 1px solid #ddd;
                                padding: 10px;
                                text-align: left;
                            }
                            th {
                                background-color: #f2f2f2;
                            }
                            h2 {
                                text-align: center;
                                color: #333;
                            }
                        </style>
                    </head>
                    <body>
                        <h2>Data Pendaftaran Kursus</h2>
                        <table>
                            <tr>
                                <th>Nama Lengkap</th>
                                <td>$nama</td>
                            </tr>
                            <tr>
                                <th>Tempat & Tanggal Lahir</th>
                                <td>$ttl</td>
                            </tr>
                            <tr>
                                <th>No. HP/WA</th>
                                <td>$telepon</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>$email</td>
                            </tr>
                            <tr>
                                <th>Nama Orang Tua</th>
                                <td>$orang_tua</td>
                            </tr>
                            <tr>
                                <th>No. HP Orang Tua</th>
                                <td>$telepon_orang_tua</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>$alamat</td>
                            </tr>
                            <tr>
                                <th>Program yang Diminati</th>
                                <td>$program</td>
                            </tr>
                            <tr>
                                <th>Program Pembelajaran</th>
                                <td>$pembelajaran</td>
                            </tr>
                            <tr>
                                <th>Sistem Pertemuan</th>
                                <td>$pertemuan</td>
                            </tr>
                        </table>
                        <p style='text-align: center; font-size: 14px; color: #555;'>
                            Terima kasih telah mendaftar.
                        </p>
                    </body>
                </html>";

        // Kirim Email
        $mail->send();
        // echo "Pendaftaran berhasil! Email telah dikirim.";
        echo "<script>
            alert('Pendaftaran berhasil! Email telah dikirim.');
            window.location.href = 'index.html';
            </script>";
    } catch (Exception $e) {
        // echo "Pendaftaran gagal! Pesan kesalahan: {$mail->ErrorInfo}";
        echo "<script>
                alert('Pendaftaran gagal! Pesan kesalahan: {$mail->ErrorInfo}');
                window.location.href = 'form-pendaftaran.html';
              </script>";
    }
}
?>
