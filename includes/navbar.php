<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&family=Playfair+Display:wght@700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <!-- Navbar Start -->
    <nav
      id="navbar"
      class="fixed top-0 w-full bg-white shadow-lg text-black z-20 py-4 px-6 transition-all duration-300"
    >
      <div class="container mx-auto flex items-center justify-between">
        <!-- Logo -->
        <a
          href="index.php"
          class="text-3xl font-bold tracking-wide italic text-black hover:text-blue-500 transition-all duration-300"
        >
          Rekos
        </a>

        <!-- Desktop Menu -->
        <div
          class="hidden md:flex space-x-6 items-center text-gray-600 tracking-wide italic"
        >
          <a
            href="#navbar"
            class="text-lg relative hover:text-blue-500 transition-all duration-300 after:content-[''] after:block after:h-[2px] after:bg-gradient-to-r after:from-blue-500 after:to-cyan-400 after:w-0 after:hover:w-full after:transition-all after:duration-300"
          >
            Home
          </a>
          <a
            href="#rekomendasi"
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
          href="#navbar"
          class="text-lg hover:text-blue-500 transition duration-300"
          >Home</a
        >
        <a
          id="#rekomendasi"
          class="text-lg hover:text-blue-500 transition duration-300"
          >Rekomendasi</a
        >
        <a
          href="tentang.php"
          class="text-lg hover:text-blue-500 transition duration-300"
          >Tentang WP</a
        >
      </div>
    </nav>
    <!-- Navbar End -->