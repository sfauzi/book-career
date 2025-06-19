<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Welcome to BukuKarir</title>
        <link rel="icon" type="image/x-icon" href="{{ url('images/logo.svg') }}" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&amp;display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }


            .gradient-bg {
                background: linear-gradient(135deg, #F6339A 0%, #AA1866 50%, #D4408F 100%);
            }

            .gradient-bg-dark {
                background: linear-gradient(135deg, #1f2937 0%, #111827 50%, #0f172a 100%);
            }

            .floating {
                animation: floating 3s ease-in-out infinite;
            }

            .floating-delayed {
                animation: floating 3s ease-in-out infinite;
                animation-delay: -1.5s;
            }

            @keyframes floating {

                0%,
                100% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-10px);
                }
            }

            .fade-in {
                animation: fadeIn 1s ease-out;
            }

            .slide-up {
                animation: slideUp 0.8s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .logo-glow {
                filter: drop-shadow(0 10px 20px rgba(246, 51, 154, 0.3));
            }

            .card-hover {
                transition: all 0.3s ease;
            }

            .card-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            }

            .dark .card-hover:hover {
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            }
        </style>
    </head>

    <body
        class="min-h-screen transition-colors duration-300 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
        <!-- Theme Toggle Button -->
        {{-- <button id="themeToggle"
            class="fixed top-6 right-6 z-50 p-3 rounded-full bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 group">
            <svg class="w-6 h-6 text-gray-800 dark:text-white group-hover:scale-110 transition-transform" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path class="dark:hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                <path class="hidden dark:block" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </button> --}}

        <!-- Main Container -->
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-8">
            <!-- Hero Section -->
            <div class="max-w-4xl mx-auto text-center fade-in">
                <!-- Logo with Animation -->
                <div class="mb-8 floating">
                    <div class="inline-block p-4 rounded-full bg-white dark:bg-gray-800 shadow-2xl">
                        <svg viewBox="0 0 222 43" xmlns="http://www.w3.org/2000/svg" class="h-16 w-auto">
                            <g transform="translate(0, 1) rotate(0)" id="logogram">
                                <path fill="#F6339A"
                                    d="M20 0.889954C31.0457 0.889954 40 9.84426 40 20.89V34.89C40 38.2037 37.3137 40.89 34 40.89H21V32.1161C21 30.114 21.1224 28.0404 22.1725 26.3357C23.6625 23.9168 26.1515 22.1871 29.0764 21.7093L29.4595 21.6467C29.7828 21.5358 30 21.2318 30 20.89C30 20.5481 29.7828 20.2441 29.4595 20.1332L29.0764 20.0706C24.836 19.378 21.512 16.054 20.8193 11.8135L20.7568 11.4305C20.6459 11.1071 20.3418 10.89 20 10.89C19.6582 10.89 19.3541 11.1071 19.2432 11.4305L19.1807 11.8135C18.7029 14.7385 16.9731 17.2274 14.5542 18.7175C12.8496 19.7676 10.7759 19.89 8.77382 19.89H0.0245667C0.545597 9.30884 9.28963 0.889954 20 0.889954Z">
                                </path>
                                <path fill="#AA1866"
                                    d="M0 21.89H8.77382C10.7759 21.89 12.8495 22.0123 14.5541 23.0624C15.8852 23.8823 17.0076 25.0047 17.8276 26.3358C18.8776 28.0405 19 30.114 19 32.1161V40.89H6C2.68629 40.89 0 38.2037 0 34.89V21.89Z">
                                </path>
                                <path fill="#D4408F"
                                    d="M40 2.88995C40 3.99452 39.1046 4.88995 38 4.88995C36.8954 4.88995 36 3.99452 36 2.88995C36 1.78538 36.8954 0.889954 38 0.889954C39.1046 0.889954 40 1.78538 40 2.88995Z">
                                </path>
                            </g>
                            <g transform="translate(46, 7)" id="logotype">
                                <path
                                    d="M19.58 29L8.17 29L8.17 4.43L19.16 4.43Q21.72 4.43 23.50 5.22Q25.29 6.00 26.23 7.42Q27.18 8.84 27.18 10.80L27.18 10.80Q27.18 12.62 26.39 13.90Q25.60 15.17 24.27 15.87Q22.94 16.57 21.33 16.68L21.33 16.68L21.93 16.26Q23.68 16.29 24.97 17.17Q26.27 18.04 27.00 19.39Q27.74 20.74 27.74 22.35L27.74 22.35Q27.74 24.34 26.77 25.85Q25.81 27.36 23.99 28.18Q22.17 29 19.58 29L19.58 29ZM14.09 18.71L14.09 24.24L18.53 24.24Q20.07 24.24 20.89 23.54Q21.72 22.84 21.72 21.54L21.72 21.54Q21.72 20.18 20.84 19.45Q19.97 18.71 18.46 18.71L18.46 18.71L14.09 18.71ZM14.09 9.15L14.09 14.37L18.04 14.37Q19.55 14.37 20.35 13.72Q21.16 13.07 21.16 11.81L21.16 11.81Q21.16 10.52 20.35 9.84Q19.55 9.15 18.04 9.15L18.04 9.15L14.09 9.15ZM43.24 26.02L43.24 26.02L43.24 9.47L49.16 9.47L49.16 29L46.22 29Q44.81 29 44.03 28.21Q43.24 27.43 43.24 26.02ZM37.26 29.25L37.26 29.25Q34.88 29.25 33.21 28.20Q31.55 27.14 30.68 25.25Q29.80 23.36 29.80 20.84L29.80 20.84L29.80 9.47L35.68 9.47L35.68 19.97Q35.68 22 36.71 23.12Q37.75 24.24 39.46 24.24L39.46 24.24Q40.65 24.24 41.49 23.64Q42.33 23.05 42.78 22Q43.24 20.95 43.24 19.62L43.24 19.62L44.05 20.63Q43.94 23.64 43.01 25.54Q42.09 27.43 40.58 28.34Q39.08 29.25 37.26 29.25ZM58.22 29L52.30 29L52.30 3.10L58.22 3.10L58.22 17.10Q59.83 16.75 61.28 15.66Q62.73 14.58 63.78 12.97Q64.83 11.36 65.22 9.47L65.22 9.47L71.48 9.47Q70.96 11.64 69.70 13.60Q68.44 15.56 66.78 17.03Q65.11 18.50 63.26 19.27L63.26 19.27Q64.69 19.62 66.06 20.56Q67.42 21.51 68.60 22.82Q69.77 24.13 70.59 25.73Q71.41 27.32 71.73 29L71.73 29L65.39 29Q65.08 27.50 64.38 26.22Q63.68 24.94 62.72 23.99Q61.75 23.05 60.60 22.45Q59.45 21.86 58.22 21.68L58.22 21.68L58.22 29ZM86.15 26.02L86.15 26.02L86.15 9.47L92.07 9.47L92.07 29L89.13 29Q87.73 29 86.94 28.21Q86.15 27.43 86.15 26.02ZM80.17 29.25L80.17 29.25Q77.79 29.25 76.12 28.20Q74.46 27.14 73.59 25.25Q72.71 23.36 72.71 20.84L72.71 20.84L72.71 9.47L78.59 9.47L78.59 19.97Q78.59 22 79.62 23.12Q80.66 24.24 82.37 24.24L82.37 24.24Q83.56 24.24 84.40 23.64Q85.24 23.05 85.70 22Q86.15 20.95 86.15 19.62L86.15 19.62L86.96 20.63Q86.85 23.64 85.92 25.54Q85.00 27.43 83.49 28.34Q81.99 29.25 80.17 29.25ZM101.13 29L95.22 29L95.22 4.43L101.13 4.43L101.13 14.09Q103.62 14.09 105.44 12.92Q107.26 11.74 108.32 9.59Q109.39 7.44 109.57 4.43L109.57 4.43L115.94 4.43Q115.76 6.56 115.08 8.51Q114.40 10.45 113.28 12.04Q112.16 13.63 110.69 14.79Q109.22 15.94 107.57 16.54L107.57 16.54Q109.95 17.10 111.88 18.80Q113.80 20.49 115.06 23.07Q116.32 25.64 116.71 29L116.71 29L110.34 29Q110.16 25.78 108.97 23.56Q107.78 21.34 105.79 20.14Q103.79 18.95 101.13 18.95L101.13 18.95L101.13 29ZM125.07 29.25L125.07 29.25Q122.52 29.25 120.61 27.91Q118.70 26.59 117.67 24.33Q116.64 22.07 116.64 19.23L116.64 19.23Q116.64 16.43 117.67 14.16Q118.70 11.88 120.61 10.55Q122.52 9.22 125.07 9.22L125.07 9.22Q127.31 9.22 128.97 10.43Q130.64 11.64 131.30 13.81L131.30 13.81L131.30 9.47L137.22 9.47L137.22 29L134.31 29Q132.91 29 132.11 28.21Q131.30 27.43 131.30 26.02L131.30 26.02L131.30 24.70Q130.64 26.83 128.97 28.04Q127.31 29.25 125.07 29.25ZM127.00 24.06L127.00 24.06Q128.15 24.06 129.13 23.50Q130.11 22.95 130.71 21.86Q131.30 20.77 131.30 19.23L131.30 19.23Q131.30 17.73 130.71 16.64Q130.11 15.56 129.13 14.96Q128.15 14.37 127.00 14.37L127.00 14.37Q125.81 14.37 124.83 14.96Q123.85 15.56 123.27 16.64Q122.69 17.73 122.69 19.23L122.69 19.23Q122.69 20.77 123.27 21.86Q123.85 22.95 124.84 23.50Q125.84 24.06 127.00 24.06ZM146.28 29L140.37 29L140.37 9.47L146.28 9.47L146.28 29ZM146.28 20.60L145.37 20.60L146.28 14.19Q146.91 11.92 148.49 10.61Q150.06 9.29 152.51 9.29L152.51 9.29L152.51 15.45Q152.13 15.38 151.78 15.35Q151.43 15.31 151.08 15.31L151.08 15.31Q149.78 15.31 148.70 15.93Q147.61 16.54 146.95 17.71Q146.28 18.88 146.28 20.60L146.28 20.60ZM159.90 29L154.02 29L154.02 9.47L159.90 9.47L159.90 29ZM156.99 7.40L156.99 7.40Q155.28 7.40 154.19 6.42Q153.11 5.44 153.11 3.97L153.11 3.97Q153.11 2.47 154.19 1.47Q155.28 0.47 156.99 0.47L156.99 0.47Q158.67 0.47 159.76 1.47Q160.84 2.47 160.84 3.97L160.84 3.97Q160.84 5.44 159.76 6.42Q158.67 7.40 156.99 7.40ZM168.86 29L162.94 29L162.94 9.47L168.86 9.47L168.86 29ZM168.86 20.60L167.95 20.60L168.86 14.19Q169.49 11.92 171.06 10.61Q172.64 9.29 175.09 9.29L175.09 9.29L175.09 15.45Q174.70 15.38 174.35 15.35Q174 15.31 173.65 15.31L173.65 15.31Q172.36 15.31 171.27 15.93Q170.19 16.54 169.52 17.71Q168.86 18.88 168.86 20.60L168.86 20.60Z"
                                    fill="currentColor" class="text-gray-800 dark:text-white"></path>
                            </g>
                        </svg>
                    </div>
                </div>

                <!-- Welcome Text -->
                <div class="slide-up">
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                        Selamat Datang di
                        <span
                            class="bg-gradient-to-r from-[#F6339A] via-[#AA1866] to-[#D4408F] bg-clip-text text-transparent">
                            BukuKarir
                        </span>
                    </h1>
                    <p
                        class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto leading-relaxed">
                        Catat dan pantau semua lamaran kerja Anda di satu tempat, dengan cara yang simpel dan efisien.
                    </p>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12 slide-up">
                    <button
                        class="group px-8 py-4 bg-gradient-to-r from-primary to-secondary text-gray-800 dark:text-white font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <a href="{{ route('filament.apps.auth.login') }}">
                            <span class="flex items-center">
                                Mulai Sekarang
                                <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </span>
                        </a>
                    </button>
                    <button
                        class="px-8 py-4 bg-white dark:bg-gray-800 text-gray-800 dark:text-white font-semibold rounded-full border-2 border-gray-300 dark:border-gray-600 hover:border-primary dark:hover:border-primary shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        Pelajari Lebih Lanjut
                    </button>
                </div>
            </div>

            <!-- Features Section -->
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <!-- Feature 1 -->
                <div
                    class="card-hover bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <div
                        class="w-16 h-16 bg-gradient-to-r from-primary to-secondary rounded-full flex items-center justify-center mb-6 floating">
                        <svg class="w-8 h-8 text-gray-800 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Catat Lamaran</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Simpan semua pekerjaan yang telah Anda lamar dalam satu tempat agar mudah diingat dan diakses
                        kembali kapan saja.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div
                    class="card-hover bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <div
                        class="w-16 h-16 bg-gradient-to-r from-secondary to-accent rounded-full flex items-center justify-center mb-6 floating-delayed">
                        <svg class="w-8 h-8 text-gray-800 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Pantau Status</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Lacak setiap tahapan proses rekrutmen: mulai dari apply, interview, hingga hasil akhir — semua
                        tercatat rapi.
                    </p>

                </div>

                <!-- Feature 3 -->
                <div
                    class="card-hover bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <div
                        class="w-16 h-16 bg-gradient-to-r from-accent to-primary rounded-full flex items-center justify-center mb-6 floating">
                        <svg class="w-8 h-8 text-gray-800 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>

                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Riwayat Lamaran</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Bangun riwayat karir Anda dengan mencatat perusahaan, posisi, dan tanggal apply — semuanya
                        tersimpan secara otomatis.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <footer class="text-center text-gray-500 dark:text-gray-400">
                <p>&copy; 2025 BukuKarir. Semua hak dilindungi.</p>
            </footer>
        </div>

        <!-- Decorative Elements -->
        <div class="fixed top-0 left-0 w-full h-full pointer-events-none overflow-hidden">
            <!-- Floating Dots -->
            <div class="absolute top-20 left-10 w-2 h-2 bg-primary rounded-full opacity-60 floating"></div>
            <div class="absolute top-40 right-20 w-3 h-3 bg-secondary rounded-full opacity-40 floating-delayed"></div>
            <div class="absolute bottom-32 left-20 w-2 h-2 bg-accent rounded-full opacity-50 floating"></div>
            <div class="absolute bottom-20 right-10 w-4 h-4 bg-primary rounded-full opacity-30 floating-delayed"></div>

            <!-- Gradient Circles -->
            <div
                class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-full blur-3xl">
            </div>
            <div
                class="absolute -bottom-20 -left-20 w-60 h-60 bg-gradient-to-tr from-secondary/20 to-accent/20 rounded-full blur-3xl">
            </div>
        </div>

        <script>
            // Theme Toggle Functionality
            const themeToggle = document.getElementById('themeToggle');
            const html = document.documentElement;

            // Check for saved theme preference or default to light mode
            const currentTheme = localStorage.getItem('theme') || 'light';
            html.classList.toggle('dark', currentTheme === 'dark');

            themeToggle.addEventListener('click', () => {
                const isDark = html.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });

            // Smooth scroll and animation triggers
            window.addEventListener('scroll', () => {
                const elements = document.querySelectorAll('.slide-up');
                elements.forEach(el => {
                    const elementTop = el.getBoundingClientRect().top;
                    const elementVisible = 150;

                    if (elementTop < window.innerHeight - elementVisible) {
                        el.style.opacity = '1';
                        el.style.transform = 'translateY(0)';
                    }
                });
            });

            // Initialize animations
            document.addEventListener('DOMContentLoaded', () => {
                // Add subtle parallax effect to floating elements
                const floatingElements = document.querySelectorAll('.floating, .floating-delayed');

                document.addEventListener('mousemove', (e) => {
                    const mouseX = e.clientX / window.innerWidth;
                    const mouseY = e.clientY / window.innerHeight;

                    floatingElements.forEach((el, index) => {
                        const speed = (index % 2 === 0 ? 1 : -1) * 0.5;
                        el.style.transform = `translate(${mouseX * speed}px, ${mouseY * speed}px)`;
                    });
                });
            });
        </script>
    </body>

</html>
