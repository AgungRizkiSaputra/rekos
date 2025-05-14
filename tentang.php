<?php 
$title = "Tentang WP";
include 'includes/navbar.php'; 
?>

<!-- Navbar Start -->
<nav
      id="navbar"
      class="fixed top-0 w-full bg-white shadow-lg text-black z-20 py-4 px-6 transition-all duration-300"
    >
      <div class="container mx-auto flex items-center justify-between">
        <!-- Logo -->
        <a
          href="#"
          class="text-3xl font-bold tracking-wide italic text-black hover:text-blue-500 transition-all duration-300"
        >
          Rekos
        </a>

        <!-- Desktop Menu -->
        <div
          class="hidden md:flex space-x-6 items-center text-gray-600 tracking-wide italic"
        >
          <a
            href="index.php"
            class="text-lg relative hover:text-blue-500 transition-all duration-300 after:content-[''] after:block after:h-[2px] after:bg-gradient-to-r after:from-blue-500 after:to-cyan-400 after:w-0 after:hover:w-full after:transition-all after:duration-300"
          >
            Home
          </a>
          <a
            href="index.php"
            class="text-lg relative hover:text-blue-500 transition-all duration-300 after:content-[''] after:block after:h-[2px] after:bg-gradient-to-r after:from-blue-500 after:to-cyan-400 after:w-0 after:hover:w-full after:transition-all after:duration-300"
          >
            Rekomendasi
          </a>
          <a
            href="tentang.php"
            class="text-lg relative hover:text-blue-500 transition-all duration-300 after:content-[''] after:block after:h-[2px] after:bg-gradient-to-r after:from-blue-500 after:to-cyan-400 after:w-0 after:hover:w-full after:transition-all after:duration-300"
          >
            Tentang WP
          </a>
        </div>

        <!-- Mobile Menu Button -->
        <button
          id="menu-button"
          class="md:hidden text-3xl text-black hover:text-blue-500 transition-transform duration-300"
        >
          &#9776;
        </button>
      </div>

      <!-- Mobile Menu -->
      <div
        id="mobile-menu"
        class="hidden opacity-0 transform scale-y-0 origin-top transition-all duration-300 md:hidden flex flex-col space-y-4 items-center mt-4 py-4 italic"
      >
        <a
          href="index.html"
          class="text-lg hover:text-blue-500 transition duration-300"
          >Home</a
        >
        <a
          href="index.html"
          class="text-lg hover:text-blue-500 transition duration-300"
          >Rekomendasi</a
        >
        <a
          href="tentang.html"
          class="text-lg hover:text-blue-500 transition duration-300"
          >Tentang WP</a
        >
      </div>
    </nav>
    <!-- Navbar End -->

<!-- Header Start -->
<header
      class="relative bg-blue-900 text-white py-24 shadow-xl text-center rounded-3xl mx-6 mt-28 overflow-hidden"
    >
      <!-- Efek Blur Glassmorphism -->
      <div
        class="absolute inset-0 bg-white/10 backdrop-blur-xl rounded-3xl shadow-lg"
      ></div>

      <!-- Elemen Dekoratif -->
      <div
        class="absolute -top-10 left-1/4 w-32 h-32 bg-blue-500 rounded-full blur-3xl opacity-30"
      ></div>
      <div
        class="absolute -bottom-10 right-1/4 w-40 h-40 bg-purple-500 rounded-full blur-3xl opacity-30"
      ></div>

      <h1
        class="relative z-10 animate-fade-in text-5xl font-extrabold tracking-wide"
      >
        Tentang Metode Weight Product (WP)
      </h1>
      <p class="relative z-10 mt-4 text-lg text-white/80">
        Teknik dalam pengambilan keputusan berbasis bobot kriteria
      </p>
    </header>
    <!-- Header End -->

    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <h2 class="text-2xl font-semibold mb-4">Apa itu Metode Weight Product?</h2>
        <p class="text-gray-700 leading-relaxed mb-4">
            Metode Weight Product (WP) adalah salah satu metode dalam Sistem Pendukung Keputusan (SPK) yang digunakan untuk mengevaluasi beberapa alternatif berdasarkan sejumlah kriteria tertentu. Metode ini menggunakan operasi perkalian untuk menghubungkan rating atribut, dimana rating setiap atribut dipangkatkan dengan bobot atribut yang bersangkutan.
        </p>
        <p class="text-gray-700 leading-relaxed">
            Kelebihan utama metode WP adalah kemampuannya dalam menangani data yang memiliki skala pengukuran berbeda tanpa perlu normalisasi tambahan, karena proses normalisasi sudah termasuk dalam perhitungan vektor preferensi.
        </p>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <h2 class="text-2xl font-semibold mb-4">Langkah-langkah Metode WP</h2>
        <ol class="list-decimal list-inside space-y-4 text-gray-700">
            <li class="pl-4">
                <strong>Menentukan kriteria dan bobot:</strong> Menentukan kriteria-kriteria yang akan dijadikan acuan dalam pengambilan keputusan beserta bobot kepentingannya.
            </li>
            <li class="pl-4">
                <strong>Normalisasi bobot:</strong> Melakukan normalisasi bobot dengan membagi setiap bobot kriteria dengan jumlah total seluruh bobot.
            </li>
            <li class="pl-4">
                <strong>Menghitung vektor S:</strong> Menghitung vektor S untuk setiap alternatif dengan mengalikan rating setiap kriteria yang sudah dipangkatkan dengan bobot normalisasi.
            </li>
            <li class="pl-4">
                <strong>Menghitung vektor V:</strong> Menghitung vektor V dengan membagi setiap vektor S dengan jumlah total vektor S.
            </li>
            <li class="pl-4">
                <strong>Perankingan:</strong> Mengurutkan alternatif berdasarkan nilai vektor V dari yang terbesar hingga terkecil.
            </li>
        </ol>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-semibold mb-4">Rumus Weight Product</h2>
        <div class="bg-gray-100 p-4 rounded-lg mb-4">
            <p class="font-mono text-lg">
                S<sub>i</sub> = ∏(x<sub>ij</sub>)<sup>w<sub>j</sub></sup>
            </p>
        </div>
        <p class="text-gray-700 mb-4">
            Dimana:
        </p>
        <ul class="list-disc list-inside space-y-2 text-gray-700">
            <li>S<sub>i</sub> = Vektor preferensi untuk alternatif ke-i</li>
            <li>x<sub>ij</sub> = Nilai alternatif ke-i pada kriteria ke-j</li>
            <li>w<sub>j</sub> = Bobot normalisasi kriteria ke-j</li>
        </ul>
    </div>

<?php include 'includes/footer.php'; ?>