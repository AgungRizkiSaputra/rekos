<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Ambil data dari form jika ada
$preferensi = isset($_POST['preferensi']) ? $_POST['preferensi'] : [];

// Hitung rekomendasi menggunakan WP
$hasil_rekomendasi = hitungWP($preferensi);

// Jika request AJAX, kembalikan data JSON
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($hasil_rekomendasi);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Rekomendasi - Rekos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .playfair {
            font-family: 'Playfair Display', serif;
        }

        .kost-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .top-badge {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            background-color: #f59e0b;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: bold;
            z-index: 10;
        }

        .loading-spinner {
            display: inline-block;
            width: 2rem;
            height: 2rem;
            border: 0.25rem solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top-color: #3b82f6;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="fixed top-0 w-full bg-white shadow-lg text-black z-20 py-4 px-6">
        <div class="container mx-auto flex items-center justify-between">
            <a href="index.php" class="text-3xl font-bold tracking-wide italic text-black hover:text-blue-500 transition-all duration-300">
                Rekos
            </a>
            <a href="index.php" class="text-lg font-semibold text-blue-600 hover:text-blue-800 transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="pt-24 pb-12 px-6">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-4 text-center">
                    Hasil Rekomendasi Kost
                </h1>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    Berikut adalah rekomendasi kost terbaik untuk Anda berdasarkan perhitungan metode Weight Product (WP).
                </p>
            </div>

            <!-- Filter Options -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Filter Rekomendasi</h2>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="filter-murah" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="filter-murah" class="ml-2 text-gray-700">Harga Murah (< Rp1.5jt)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="filter-fasilitas" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="filter-fasilitas" class="ml-2 text-gray-700">Fasilitas Lengkap</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="filter-dekat" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="filter-dekat" class="ml-2 text-gray-700">Dekat Kampus</label>
                    </div>
                    <button id="reset-filter" class="ml-auto text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Reset Filter
                    </button>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div id="loading" class="text-center py-12">
                <div class="loading-spinner mx-auto"></div>
                <p class="mt-4 text-gray-600">Memproses rekomendasi...</p>
            </div>

            <!-- Results Container -->
            <div id="results-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 hidden">
                <!-- Results will be inserted here by JavaScript -->
            </div>

            <!-- No Results Message -->
            <div id="no-results" class="text-center py-12 hidden">
                <i class="fas fa-info-circle text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700">Tidak ada hasil yang sesuai</h3>
                <p class="text-gray-500 mt-2">Coba sesuaikan filter atau preferensi Anda.</p>
            </div>

            <!-- WP Calculation Details -->
            <div class="bg-white rounded-xl shadow-md p-6 mt-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Detail Perhitungan WP</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Normalisasi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="criteria-details">
                            <!-- Criteria details will be inserted here by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-blue-100 text-gray-800 py-6 text-center">
        <div class="max-w-4xl mx-auto px-6">
            <p class="text-gray-600 text-sm mt-2">
                Sistem Pemilihan Kost Ideal dengan metode Weight Product (WP).
            </p>
            <p class="text-gray-500 text-xs mt-2">
                &copy; <?= date('Y') ?> Rekos - All Rights Reserved
            </p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simulate loading data
            setTimeout(function() {
                // Hide loading indicator
                document.getElementById('loading').classList.add('hidden');

                // Get the results data from PHP
                const results = <?= json_encode($hasil_rekomendasi) ?>;

                // Get criteria data
                const criteria = [{
                        name: 'Harga',
                        weight: 0.3,
                        type: 'cost',
                        normalized: (0.3 / 1).toFixed(2)
                    },
                    {
                        name: 'Jarak ke Kampus',
                        weight: 0.25,
                        type: 'cost',
                        normalized: (0.25 / 1).toFixed(2)
                    },
                    {
                        name: 'Fasilitas',
                        weight: 0.2,
                        type: 'benefit',
                        normalized: (0.2 / 1).toFixed(2)
                    },
                    {
                        name: 'Keamanan',
                        weight: 0.15,
                        type: 'benefit',
                        normalized: (0.15 / 1).toFixed(2)
                    },
                    {
                        name: 'Kebersihan',
                        weight: 0.1,
                        type: 'benefit',
                        normalized: (0.1 / 1).toFixed(2)
                    }
                ];

                // Display criteria details
                const criteriaTable = document.getElementById('criteria-details');
                criteria.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.weight}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.type}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.normalized}</td>
                    `;
                    criteriaTable.appendChild(row);
                });

                // Display results
                displayResults(results);

                // Set up filter event listeners
                setupFilters();

            }, 1500); // Simulate 1.5s loading time

            function displayResults(results) {
                const container = document.getElementById('results-container');
                const noResults = document.getElementById('no-results');

                // Clear previous results
                container.innerHTML = '';

                if (results.length === 0) {
                    container.classList.add('hidden');
                    noResults.classList.remove('hidden');
                    return;
                }

                noResults.classList.add('hidden');

                results.forEach((kost, index) => {
                    const card = document.createElement('div');
                    card.className = 'kost-card bg-white rounded-xl shadow-lg overflow-hidden transition duration-300 relative';
                    card.dataset.harga = kost.harga;
                    card.dataset.fasilitas = kost.deskripsi.toLowerCase().includes('fasilitas lengkap') ? '1' : '0';
                    card.dataset.jarak = index < 3 ? 'dekat' : 'jauh';

                    let badge = '';
                    if (index < 3) {
                        badge = `<div class="top-badge">Top ${index + 1}</div>`;
                    }

                    card.innerHTML = `
                    ${badge}
                    <a href="detail.php?id=${kost.id_alternatif}" class="block h-full">
                        <img
                            src="images/${kost.gambar}"
                            alt="${kost.nama_alternatif}"
                            class="w-full h-56 object-cover transition-transform duration-300 group-hover:scale-105" />
                        <div class="flex flex-col flex-1 p-6">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2 group-hover:text-blue-600 transition-colors duration-200">
                                ${kost.nama_alternatif}
                            </h2>
                            <p class="text-gray-500 text-lg">Rp ${kost.harga.toLocaleString('id-ID')} / bulan</p>
                            <p class="text-gray-500 text-sm mt-4 line-clamp-3 flex-grow">
                                ${kost.deskripsi}
                            </p>
                            <div class="mt-auto pt-4 flex justify-between items-center">
                                <span class="text-blue-600 font-medium">
                                    Skor: ${kost.vektor.toFixed(4)}
                                </span>
                                <span class="text-sm text-blue-500 font-semibold group-hover:underline">
                                    Lihat detail
                                </span>
                            </div>
                        </div>
                    </a>
                `;


                    container.appendChild(card);
                });

                container.classList.remove('hidden');
            }

            function setupFilters() {
                const filterMurah = document.getElementById('filter-murah');
                const filterFasilitas = document.getElementById('filter-fasilitas');
                const filterDekat = document.getElementById('filter-dekat');
                const resetBtn = document.getElementById('reset-filter');

                function applyFilters() {
                    const results = <?= json_encode($hasil_rekomendasi) ?>;
                    let filteredResults = [...results];

                    if (filterMurah.checked) {
                        filteredResults = filteredResults.filter(kost => kost.harga <= 1500000);
                    }

                    if (filterFasilitas.checked) {
                        filteredResults = filteredResults.filter(kost => kost.deskripsi.toLowerCase().includes('fasilitas lengkap'));
                    }

                    if (filterDekat.checked) {
                        filteredResults = filteredResults.slice(0, 3); // Top 3 are considered "dekat"
                    }

                    displayResults(filteredResults);
                }

                filterMurah.addEventListener('change', applyFilters);
                filterFasilitas.addEventListener('change', applyFilters);
                filterDekat.addEventListener('change', applyFilters);

                resetBtn.addEventListener('click', function() {
                    filterMurah.checked = false;
                    filterFasilitas.checked = false;
                    filterDekat.checked = false;
                    displayResults(<?= json_encode($hasil_rekomendasi) ?>);
                });
            }
        });
    </script>
</body>

</html>