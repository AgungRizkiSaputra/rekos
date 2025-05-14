<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Ambil ID kost dari parameter URL
$id_kost = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mendapatkan data kost
$query = "SELECT * FROM alternatif WHERE id_alternatif = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_kost);
$stmt->execute();
$result = $stmt->get_result();
$kost = $result->fetch_assoc();

// Jika kost tidak ditemukan, redirect ke halaman rekomendasi
// if (!$kost) {
//     header("Location: rekomendasi.php");
//     exit();
// }

// Query untuk mendapatkan ranking kost
$query_ranking = "SELECT a.id_alternatif, 
                 SUM(k.bobot * IF(k.jenis = 'cost', 1/n.nilai, n.nilai)) AS skor
                 FROM alternatif a
                 JOIN nilai_alternatif n ON a.id_alternatif = n.id_alternatif
                 JOIN kriteria k ON n.id_kriteria = k.id_kriteria
                 GROUP BY a.id_alternatif
                 ORDER BY skor DESC";
$ranking_result = $conn->query($query_ranking);
$ranking_data = $ranking_result->fetch_all(MYSQLI_ASSOC);

// Cari ranking kost ini
$ranking = 0;
foreach ($ranking_data as $index => $row) {
    if ($row['id_alternatif'] == $id_kost) {
        $ranking = $index + 1;
        break;
    }
}

// Query untuk mendapatkan nilai kriteria kost
$query_nilai = "SELECT k.nama_kriteria, n.nilai, k.jenis, k.bobot
                FROM nilai_alternatif n
                JOIN kriteria k ON n.id_kriteria = k.id_kriteria
                WHERE n.id_alternatif = ?";
$stmt_nilai = $conn->prepare($query_nilai);
$stmt_nilai->bind_param("i", $id_kost);
$stmt_nilai->execute();
$nilai_kriteria = $stmt_nilai->get_result()->fetch_all(MYSQLI_ASSOC);

// Hitung skor WP
$skor = 1.0;
foreach ($nilai_kriteria as $nk) {
    if ($nk['jenis'] == 'cost') {
        // Untuk kriteria cost, gunakan 1/nilai
        $skor *= pow(1/$nk['nilai'], $nk['bobot']);
    } else {
        // Untuk kriteria benefit, gunakan nilai langsung
        $skor *= pow($nk['nilai'], $nk['bobot']);
    }
}
$skor = round($skor, 4);

// Jika kost tidak ditemukan, tampilkan pesan error dan stop eksekusi
if (!$kost) {
    die("Data kost tidak ditemukan untuk ID: " . $id_kost);
}

// Pastikan nilai kriteria ada
if (empty($nilai_kriteria)) {
    die("Data kriteria tidak ditemukan untuk kost ini");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail <?= htmlspecialchars($kost['nama_alternatif']) ?> - Rekos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }
        .playfair {
            font-family: 'Playfair Display', serif;
        }
        .badge-top {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background-color: #F59E0B;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 9999px;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }
        .rating-star {
            color: #F59E0B;
        }
        .criteria-card {
            transition: all 0.3s ease;
        }
        .criteria-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="fixed top-0 w-full bg-white shadow-md z-20 py-4 px-6">
        <div class="container mx-auto flex items-center justify-between">
            <a href="index.php" class="text-2xl font-bold italic text-blue-600">Rekos</a>
            <a href="rekomendasi.php" class="text-blue-600 hover:text-blue-800 transition duration-300 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Rekomendasi
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="pt-24 pb-12 px-4 max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="relative mb-8 rounded-xl overflow-hidden shadow-lg">
            <!-- <div class="badge-top">
                Top <?= $ranking ?>
            </div> -->
            <img src="images/<?= htmlspecialchars($kost['gambar']) ?>" alt="<?= htmlspecialchars($kost['nama_alternatif']) ?>" class="w-full h-96 object-cover">
        </div>

        <!-- Kost Info Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-8 mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2 playfair"><?= htmlspecialchars($kost['nama_alternatif']) ?></h1>
                    <p class="text-gray-600 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i> 
                        <?= htmlspecialchars($kost['alamat']) ?>
                    </p>
                    
                    <div class="flex flex-wrap items-center justify-between mb-6">
                        <div>
                            <span class="text-2xl font-bold text-blue-600">Rp <?= number_format($kost['harga'], 0, ',', '.') ?></span>
                            <span class="text-gray-500">/ bulan</span>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="rating-star mr-2">
                                <?php 
                                $rating = 4.5; // Anda bisa mengambil dari database jika ada
                                $full_stars = floor($rating);
                                $half_star = ($rating - $full_stars) >= 0.5;
                                
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $full_stars) {
                                        echo '<i class="fas fa-star"></i>';
                                    } elseif ($half_star && $i == $full_stars + 1) {
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    } else {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                }
                                ?>
                            </div>
                            <span class="text-gray-600"><?= $rating ?> (120 reviews)</span>
                        </div>
                    </div>
                    
                    <p class="text-gray-700 mb-8 leading-relaxed"><?= htmlspecialchars($kost['deskripsi']) ?></p>
                    
                    <div class="border-t border-gray-200 pt-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Fasilitas Utama</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <i class="fas fa-wifi text-blue-500 mr-3 text-lg"></i>
                                <span>Wi-Fi Gratis</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-snowflake text-blue-500 mr-3 text-lg"></i>
                                <span>AC</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-utensils text-blue-500 mr-3 text-lg"></i>
                                <span>Dapur Bersama</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-car text-blue-500 mr-3 text-lg"></i>
                                <span>Parkir Motor</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-tv text-blue-500 mr-3 text-lg"></i>
                                <span>TV Ruang Tamu</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-key text-blue-500 mr-3 text-lg"></i>
                                <span>Keamanan 24 Jam</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Gallery Section -->
                <div class="bg-white rounded-xl shadow-md p-8 mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Galeri Foto</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <img src="images/<?= htmlspecialchars($kost['gambar']) ?>" alt="Kost <?= htmlspecialchars($kost['nama_alternatif']) ?>" class="rounded-lg h-40 object-cover">
                        <img src="images/kost-interior1.jpg" alt="Interior 1" class="rounded-lg h-40 object-cover">
                        <img src="images/kost-interior2.jpg" alt="Interior 2" class="rounded-lg h-40 object-cover">
                        <img src="images/kost-kamar.jpg" alt="Kamar" class="rounded-lg h-40 object-cover">
                        <img src="images/kost-kamar-mandi.jpg" alt="Kamar Mandi" class="rounded-lg h-40 object-cover">
                        <img src="images/kost-ruang-tamu.jpg" alt="Ruang Tamu" class="rounded-lg h-40 object-cover">
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Skor WP -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Skor Rekomendasi</h2>
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <div class="text-4xl font-bold text-blue-600 mb-2"><?= $skor ?></div>
                        <p class="text-gray-600">Weight Product Score</p>
                    </div>
                    <p class="text-gray-600 mt-4 text-sm">
                        Skor ini dihitung berdasarkan metode Weight Product dengan mempertimbangkan berbagai kriteria seperti harga, jarak, fasilitas, dan lainnya.
                    </p>
                </div>
                
                <!-- Detail Kriteria -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Detail Kriteria</h2>
                    <div class="space-y-4">
                        <?php foreach ($nilai_kriteria as $kriteria): ?>
                        <div class="criteria-card bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold text-gray-800"><?= htmlspecialchars($kriteria['nama_kriteria']) ?></h3>
                                    <p class="text-gray-600 text-sm mt-1">Nilai: <?= $kriteria['nilai'] ?></p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-medium 
                                    <?= $kriteria['jenis'] == 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $kriteria['jenis'] == 'benefit' ? 'Benefit' : 'Cost' ?>
                                </span>
                            </div>
                            <div class="mt-2">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: <?= ($kriteria['nilai']/5)*100 ?>%"></div>
                                </div>
                                <p class="text-right text-xs text-gray-500 mt-1">Bobot: <?= $kriteria['bobot'] ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Contact Box -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Hubungi Pemilik</h2>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <i class="fas fa-phone-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Telepon</p>
                                <p class="font-medium">0812-3456-7890</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <i class="fas fa-envelope text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Email</p>
                                <p class="font-medium">pemilik@<?= strtolower(str_replace(' ', '', $kost['nama_alternatif'])) ?>.com</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Alamat</p>
                                <p class="font-medium"><?= htmlspecialchars($kost['alamat']) ?></p>
                            </div>
                        </div>
                    </div>
                    <button class="w-full mt-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 font-medium">
                        <i class="fas fa-comment-dots mr-2"></i> Kirim Pesan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-blue-100 text-gray-800 py-8 text-center">
        <div class="max-w-4xl mx-auto px-6">
            <p class="text-gray-600 text-sm mt-2">
                Sistem Pemilihan Kost Ideal dengan metode Weight Product (WP).
            </p>
            <p class="text-gray-500 text-xs mt-2">
                &copy; <?= date('Y') ?> Rekos - All Rights Reserved
            </p>
        </div>
    </footer>
</body>
</html>