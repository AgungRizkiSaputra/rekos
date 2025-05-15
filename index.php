<?php
$title = "Rekomendasi Kost";
include 'includes/navbar.php';
require_once 'includes/functions.php';

$hasil_rekomendasi = hitungWP();
?>

<!-- Header Start -->
<div class="mt-24 mx-6 md:mx-16 p-8 rounded-2xl bg-cover bg-center text-white" style="background-image: url('images/header.webp');">
    <!-- Overlay -->
    <div class="bg-black/60 p-12 rounded-2xl flex items-center justify-center text-center">
        <div class="max-w-3xl space-y-6">
            <h1 class="text-4xl font-bold italic" style="font-family: 'Playfair Display', serif">
                Temukan Kost Ideal Anda
            </h1>
            <p class="font-[Poppins]">
                "Pilih tempat tinggal terbaik dengan fasilitas yang sesuai kebutuhan Anda. Dari lokasi strategis hingga kenyamanan maksimal, kami membantu menemukan kost impian Anda. Dengan menggunakan metode Weight Product (WP)"
            </p>
            <a href="#rekomendasi" class="inline-block px-6 py-3 border border-white text-white font-semibold rounded-lg hover:bg-white hover:text-black transition duration-300">
                Lihat Kost
            </a>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- WP Start -->
<div class="container mt-12 mx-auto p-10 max-w-7xl rounded-lg grid grid-cols-3 gap-10 bg-gradient-to-r from-blue-50 to-gray-100 shadow-xl">
    <!-- Bagian Kiri -->
    <div class="text-center flex flex-col justify-between p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition duration-300">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900">
                Weight Product (WP)
            </h1>
            <p class="text-lg text-gray-600 mt-3">
                Metode Cerdas untuk Menentukan Kost Ideal.
            </p>
            <h2 class="text-2xl font-semibold text-gray-800 mt-6">
                Konsep Dasar WP
            </h2>
            <p class="text-gray-600 mt-3 leading-relaxed">
                WP menggunakan perkalian bobot pada setiap kriteria untuk menentukan
                skor akhir dari setiap alternatif kost yang tersedia dengan hasil
                yang lebih akurat dan objektif.
            </p>
        </div>
        <?php if ($isLoggedIn): ?>
            <a href="tentang.php" class="mt-8 px-8 py-3 bg-blue-600 text-white text-lg font-bold rounded-lg hover:bg-blue-700 transition duration-300 shadow-md inline-block">
                Pelajari Lebih Lanjut
            </a>
        <?php else: ?>
            <a href="login.php" onclick="alert('Silakan login terlebih dahulu untuk melihat proses lengkap.')" class="mt-8 px-8 py-3 bg-blue-600 text-white text-lg font-bold rounded-lg hover:bg-blue-700 transition duration-300 shadow-md inline-block">
                Pelajari Lebih Lanjut
            </a>
        <?php endif; ?>
    </div>

    <!-- Bagian Tengah (Penjelasan Proses) -->
    <div class="text-center flex flex-col justify-center p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition duration-300">
        <h2 class="text-3xl font-bold text-gray-800">Tahapan Metode WP</h2>
        <ul class="text-gray-700 mt-6 text-left space-y-4 text-lg">
            <li class="flex items-center gap-2">
                <span class="text-blue-500 text-xl">✔</span> Menentukan kriteria dan
                bobot kepentingan.
            </li>
            <li class="flex items-center gap-2">
                <span class="text-blue-500 text-xl">✔</span> Melakukan normalisasi
                bobot kriteria.
            </li>
            <li class="flex items-center gap-2">
                <span class="text-blue-500 text-xl">✔</span> Menghitung vektor
                preferensi untuk setiap alternatif.
            </li>
            <li class="flex items-center gap-2">
                <span class="text-blue-500 text-xl">✔</span> Mengurutkan hasil untuk
                menentukan kost terbaik.
            </li>
        </ul>
    </div>

    <!-- Bagian Kanan -->
    <div class="text-center flex flex-col justify-between p-8 bg-white rounded-2xl shadow-lg hover:shadow-2xl transition duration-300">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900">Keunggulan WP</h1>
            <p class="text-lg text-gray-600 mt-3">
                Metode yang Akurat, Efektif, dan Andal.
            </p>
            <h2 class="text-2xl font-semibold text-gray-800 mt-6">
                Mengapa Menggunakan WP?
            </h2>
            <p class="text-gray-600 mt-3 leading-relaxed">
                WP memberikan rekomendasi kost terbaik berdasarkan analisis
                multi-kriteria dengan hasil yang objektif dan sistematis.
            </p>
        </div>
        <?php if ($isLoggedIn): ?>
            <a href="proses.php" class="mt-8 px-8 py-3 bg-blue-600 text-white text-lg font-bold rounded-lg hover:bg-blue-700 transition duration-300 shadow-md inline-block">
                Coba Sekarang
            </a>
        <?php else: ?>
            <a href="login.php" onclick="alert('Silakan login terlebih dahulu untuk melihat proses lengkap.')" class="mt-8 px-8 py-3 bg-blue-600 text-white text-lg font-bold rounded-lg hover:bg-blue-700 transition duration-300 shadow-md inline-block">
                Coba Sekarang
            </a>
        <?php endif; ?>
    </div>
</div>
<!-- WP End -->

<!-- Rekomendasi Section -->
<div id="rekomendasi" class="mx-auto py-12 px-6">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-4 text-center">
        Rekomendasi Kost Ideal
    </h1>
    <p class="text-gray-600 max-w-2xl mx-auto text-lg text-center">
        Temukan kost terbaik untuk Anda dengan metode Weight Product (WP) yang
        memberikan rekomendasi berdasarkan kriteria objektif.
    </p>

    <!-- Filter Navigation -->
    <div class="mt-6">
        <ul class="flex justify-center space-x-6 text-lg font-semibold text-gray-700">
            <li>
                Murah
            </li>
            <li>
                Nyaman
            </li>
            <li>
                Dekat Kampus
            </li>
            <li>
                Fasilitas Lengkap
            </li>
        </ul>
    </div>

    <!-- Kost Recommendations -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
        <?php foreach ($hasil_rekomendasi as $index => $kost): ?>
            <?php if ($isLoggedIn): ?>
                <a href="detail.php?id=<?= $kost['id_alternatif'] ?>" class="group block h-full">
                <?php else: ?>
                    <a href="login.php" onclick="alert('Silakan login terlebih dahulu untuk melihat detail kost.')" class="group block h-full">
                    <?php endif; ?>

                    <div class="flex flex-col bg-white rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition duration-300 relative h-full">
                        <?php if ($index < 3): ?>
                            <div class="absolute top-4 left-4 bg-yellow-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg z-10">
                                Top <?= $index + 1 ?>
                            </div>
                        <?php endif; ?>
                        <img
                            src="images/<?= htmlspecialchars($kost['gambar']) ?>"
                            alt="<?= htmlspecialchars($kost['nama_alternatif']) ?>"
                            class="w-full h-56 object-cover transition-transform duration-300 group-hover:scale-105" />
                        <div class="flex flex-col flex-1 p-6">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2 group-hover:text-blue-600 transition-colors duration-200">
                                <?= htmlspecialchars($kost['nama_alternatif']) ?>
                            </h2>
                            <p class="text-gray-500 text-lg">Rp <?= number_format($kost['harga'], 0, ',', '.') ?> / bulan</p>
                            <p class="text-gray-500 text-sm mt-4 line-clamp-3 flex-grow">
                                <?= htmlspecialchars($kost['deskripsi']) ?>
                            </p>
                            <div class="mt-auto pt-4 flex justify-between items-center">
                                <span class="text-blue-600 font-medium">
                                    Skor: <?= number_format($kost['vektor'], 4) ?>
                                </span>
                                <span class="text-sm text-blue-500 font-semibold group-hover:underline">
                                    Lihat detail
                                </span>
                            </div>
                        </div>
                    </div>
                    </a>
                <?php endforeach; ?>
    </div>

    <br>
    <?php if ($isLoggedIn): ?>
        <a href="proses.php" class="text-blue-500 hover:text-blue-700 text-sm font-semibold">
            Lihat Selengkapnya >
        </a>
    <?php else: ?>
        <a href="login.php" onclick="alert('Silakan login terlebih dahulu untuk melihat proses lengkap.')" class="text-blue-500 hover:text-blue-700 text-sm font-semibold">
            Lihat Selengkapnya >
        </a>
    <?php endif; ?>

</div>
<!-- Rekomendasi End -->

<?php include 'includes/footer.php'; ?>