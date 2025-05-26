<?php
// detail_kost.php
// Gabungkan Data Kost + Gallery Interior
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Ambil ID kost (id_alternatif)
$id_kost = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_kost <= 0) {
    header("Location: rekomendasi.php");
    exit;
}

// ------ Query: Data utama kost ------
$query_kost = "SELECT * FROM alternatif WHERE id_alternatif = ?";
$stmt_kost = $conn->prepare($query_kost);
$stmt_kost->bind_param("i", $id_kost);
$stmt_kost->execute();
$kost = $stmt_kost->get_result()->fetch_assoc();
$stmt_kost->close();
if (!$kost) {
    die("Data kost tidak ditemukan untuk ID: " . $id_kost);
}

// ------ Query: Ranking WP ------
$query_ranking = "
    SELECT a.id_alternatif,
            SUM(k.bobot * IF(k.jenis = 'cost', 1/n.nilai, n.nilai)) AS skor
    FROM alternatif a
    JOIN nilai_alternatif n ON a.id_alternatif = n.id_alternatif
    JOIN kriteria k ON n.id_kriteria = k.id_kriteria
    GROUP BY a.id_alternatif
    ORDER BY skor DESC
";
$ranking_result = $conn->query($query_ranking);
$ranking_data = $ranking_result->fetch_all(MYSQLI_ASSOC);
$ranking = 0;
foreach ($ranking_data as $index => $row) {
    if ($row['id_alternatif'] == $id_kost) {
        $ranking = $index + 1;
        break;
    }
}

// ------ Query: Nilai Kriteria ------
$query_nilai = "
    SELECT k.nama_kriteria, n.nilai, k.jenis, k.bobot
    FROM nilai_alternatif n
    JOIN kriteria k ON n.id_kriteria = k.id_kriteria
    WHERE n.id_alternatif = ?
";
$stmt_nilai = $conn->prepare($query_nilai);
$stmt_nilai->bind_param("i", $id_kost);
$stmt_nilai->execute();
$nilai_kriteria = $stmt_nilai->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_nilai->close();
if (empty($nilai_kriteria)) {
    die("Data kriteria tidak ditemukan untuk kost ini");
}

// ------ Hitung skor WP akhir ------
$skor = 1.0;
foreach ($nilai_kriteria as $nk) {
    if ($nk['jenis'] === 'cost') {
        $skor *= pow(1 / $nk['nilai'], $nk['bobot']);
    } else {
        $skor *= pow($nk['nilai'], $nk['bobot']);
    }
}
$skor = round($skor, 4);

// Query untuk mengambil gambar interior
$stmt_img = $conn->prepare("SELECT nama_file FROM gambar_interior WHERE id_alternatif = ?");
$stmt_img->bind_param('i', $id_kost);
$stmt_img->execute();
$result_img = $stmt_img->get_result();
$images = [];

while ($row = $result_img->fetch_assoc()) {
    // Gunakan path langsung dari database
    $image_path = $row['nama_file'];

    // Cek jika file ada di server
    if (file_exists($image_path)) {
        $images[] = $image_path;
    } else {
        error_log("Gambar tidak ditemukan: " . $image_path);
        // Optional: tampilkan gambar placeholder jika gambar tidak ada
        // $images[] = 'images/placeholder.jpg';
    }
}
$stmt_img->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail <?= htmlspecialchars($kost['nama_alternatif']) ?> - Rekos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="js/style.js">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

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

        #carouselImage {
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }

        .carousel-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.5s ease-in-out;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="pt-24 pb-12 px-4 max-w-6xl mx-auto">
        <div class="relative mb-8 rounded-xl overflow-hidden shadow-lg">
            <img src="images/<?= htmlspecialchars($kost['gambar']) ?>" alt="<?= htmlspecialchars($kost['nama_alternatif']) ?>" class="w-full h-96 object-cover">
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
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
                            <span class="text-gray-600"><?= $rating ?> (120 ulasan)</span>
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

                <div class="bg-white rounded-xl shadow-xl p-8 mb-8 max-w-4xl mx-auto">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">Galeri Foto Interior</h2>
                    <div class="relative overflow-hidden rounded-lg h-80 bg-gray-100">
                        <!-- Carousel Container -->
                        <div id="carousel" class="h-full w-full relative">
                            <?php foreach ($images as $index => $image): ?>
                                <img src="<?= htmlspecialchars($image) ?>"
                                    alt="Interior Kost <?= $index + 1 ?>"
                                    class="carousel-image absolute top-0 left-0 w-full h-full object-cover transition-opacity duration-500 <?= $index === 0 ? 'opacity-100' : 'opacity-0' ?>"
                                    data-index="<?= $index ?>"
                                    onclick="openLightbox('<?= htmlspecialchars($image) ?>')">
                            <?php endforeach; ?>

                            <?php if (empty($images)): ?>
                                <div class="flex items-center justify-center h-full">
                                    <p class="text-gray-500">Belum ada foto interior untuk kost ini</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Navigation Buttons -->
                        <?php if (!empty($images) && count($images) > 1): ?>
                            <button id="prevBtn" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white/80 text-gray-800 p-3 rounded-full shadow-md hover:bg-white transition z-10">
                                ‹
                            </button>
                            <button id="nextBtn" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white/80 text-gray-800 p-3 rounded-full shadow-md hover:bg-white transition z-10">
                                ›
                            </button>

                            <!-- Dots Indicator -->
                            <div id="dots" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10"></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Lightbox -->
                <div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4">
                    <div class="relative max-w-4xl w-full">
                        <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-2xl z-50">
                            <i class="fas fa-times"></i>
                        </button>
                        <img id="lightbox-img" class="max-h-[90vh] max-w-full mx-auto rounded-lg">
                    </div>
                </div>

                <div id="lightbox"
                    class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center p-4 hidden z-50">
                    <div class="relative max-w-4xl w-full">
                        <button onclick="closeLightbox()"
                            class="absolute top-4 right-4 text-white text-3xl p-2 rounded-full
                                hover:bg-white hover:bg-opacity-20 transition z-50">
                            <i class="fas fa-times"></i>
                        </button>
                        <img id="lightbox-img"
                            class="max-h-[90vh] max-w-full rounded-lg shadow-lg z-40 mx-auto" />
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Initialize variables
                        const images = <?= json_encode($images) ?>;
                        let currentIndex = 0;
                        let timerId = null;
                        const carousel = document.getElementById('carousel');
                        const dotsContainer = document.getElementById('dots');
                        const prevBtn = document.getElementById('prevBtn');
                        const nextBtn = document.getElementById('nextBtn');
                        const lightbox = document.getElementById('lightbox');
                        const lightboxImg = document.getElementById('lightbox-img');

                        // Function to update dot indicators
                        function updateDots() {
                            if (!dotsContainer || !images.length) return;

                            dotsContainer.innerHTML = '';
                            images.forEach((_, idx) => {
                                const dot = document.createElement('div');
                                dot.className = `w-3 h-3 rounded-full cursor-pointer transition-colors ${idx === currentIndex ? 'bg-white' : 'bg-white/50'}`;
                                dot.dataset.index = idx;
                                dot.addEventListener('click', () => {
                                    showImage(idx);
                                    resetAutoRotate();
                                });
                                dotsContainer.appendChild(dot);
                            });
                        }

                        // Function to show specific image
                        function showImage(index) {
                            if (!images.length) return;

                            // Validate and normalize index
                            currentIndex = (index + images.length) % images.length;
                            const carouselImages = document.querySelectorAll('.carousel-image');

                            // Hide all images
                            carouselImages.forEach(img => {
                                img.classList.remove('opacity-100');
                                img.classList.add('opacity-0');
                            });

                            // Show current image
                            const currentImage = document.querySelector(`.carousel-image[data-index="${currentIndex}"]`);
                            if (currentImage) {
                                currentImage.classList.remove('opacity-0');
                                currentImage.classList.add('opacity-100');
                            }

                            updateDots();
                        }

                        // Navigation functions
                        function showNextImage() {
                            showImage(currentIndex + 1);
                        }

                        function showPrevImage() {
                            showImage(currentIndex - 1);
                        }

                        // Auto-rotation control
                        function startAutoRotate() {
                            clearInterval(timerId);
                            if (images.length > 1) {
                                timerId = setInterval(showNextImage, 5000);
                            }
                        }

                        function resetAutoRotate() {
                            clearInterval(timerId);
                            startAutoRotate();
                        }

                        // Lightbox functions
                        function openLightbox(src) {
                            if (lightbox && lightboxImg) {
                                lightboxImg.src = src;
                                lightbox.classList.remove('hidden');
                                document.body.style.overflow = 'hidden';
                            }
                        }

                        function closeLightbox() {
                            if (lightbox) {
                                lightbox.classList.add('hidden');
                                document.body.style.overflow = '';
                            }
                        }

                        // Initialize event listeners
                        if (prevBtn && nextBtn) {
                            prevBtn.addEventListener('click', (e) => {
                                e.stopPropagation();
                                showPrevImage();
                                resetAutoRotate();
                            });

                            nextBtn.addEventListener('click', (e) => {
                                e.stopPropagation();
                                showNextImage();
                                resetAutoRotate();
                            });
                        }

                        if (lightbox) {
                            lightbox.addEventListener('click', function(e) {
                                if (e.target === this || e.target.classList.contains('fa-times')) {
                                    closeLightbox();
                                }
                            });
                        }

                        // Keyboard navigation
                        document.addEventListener('keydown', function(e) {
                            if (!lightbox.classList.contains('hidden')) {
                                if (e.key === 'Escape') {
                                    closeLightbox();
                                }
                                return;
                            }

                            switch (e.key) {
                                case 'ArrowLeft':
                                    showPrevImage();
                                    resetAutoRotate();
                                    break;
                                case 'ArrowRight':
                                    showNextImage();
                                    resetAutoRotate();
                                    break;
                            }
                        });

                        // Initial setup
                        if (images.length > 0) {
                            showImage(0);
                            startAutoRotate();
                        }

                        // Touch events for mobile swipe
                        let touchStartX = 0;
                        let touchEndX = 0;

                        if (carousel) {
                            carousel.addEventListener('touchstart', (e) => {
                                touchStartX = e.changedTouches[0].screenX;
                            }, {
                                passive: true
                            });

                            carousel.addEventListener('touchend', (e) => {
                                touchEndX = e.changedTouches[0].screenX;
                                handleSwipe();
                            }, {
                                passive: true
                            });
                        }

                        function handleSwipe() {
                            if (touchEndX < touchStartX - 50) {
                                // Swipe left
                                showNextImage();
                            } else if (touchEndX > touchStartX + 50) {
                                // Swipe right
                                showPrevImage();
                            }
                            resetAutoRotate();
                        }
                    });
                </script>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Skor Rekomendasi</h2>
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <div class="text-4xl font-bold text-blue-600 mb-2"><?= $skor ?></div>
                        <p class="text-gray-600">Skor Weight Product</p>
                    </div>
                    <p class="text-gray-600 mt-4 text-sm">
                        Skor ini dihitung berdasarkan metode Weight Product dengan mempertimbangkan berbagai kriteria seperti harga, jarak, fasilitas, dan lainnya.
                    </p>
                </div>

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
                                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                        <?php
                                        $nilai = floatval($kriteria['nilai']);
                                        $persentase = min(100, ($nilai / 5) * 100); // pastikan tidak lebih dari 100%
                                        ?>
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: <?= $persentase ?>%"></div>
                                    </div>
                                    <p class="text-right text-xs text-gray-500 mt-1">Bobot: <?= $kriteria['bobot'] ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button onclick="toggleContact()" class="fixed bottom-6 right-6 bg-blue-600 text-white p-4 rounded-full shadow-lg hover:bg-blue-700 transition duration-300 z-50">
                    <i class="fas fa-comments text-2xl"></i>
                </button>

                <div id="contactCard" class="hidden fixed bottom-24 right-6 bg-white rounded-xl shadow-md p-6 w-80 z-40">
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

                <script>
                    function toggleContact() {
                        const contactCard = document.getElementById('contactCard');
                        contactCard.classList.toggle('hidden');
                    }
                </script>

            </div>
        </div>
    </div>

    <footer class="bg-blue-100 text-gray-800 py-8 text-center">
        <div class="max-w-4xl mx-auto px-6">
            <p class="text-gray-600 text-sm mt-2">
                Sistem Pemilihan Kost Ideal dengan metode Weight Product (WP).
            </p>
            <p class="text-gray-500 text-xs mt-2">
                &copy; <?= date('Y') ?> Rekos - Hak Cipta Dilindungi
            </p>
        </div>
    </footer>
</body>

</html>