<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lisanna Logistic - Layanan Pengiriman Cepat & Aman</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Navbar -->
    <nav class="absolute top-0 left-0 w-full p-6 z-50 flex justify-between items-center max-w-7xl mx-auto right-0">
        <a href="/" class="flex items-center gap-2 text-2xl font-extrabold text-slate-900 tracking-tight">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 md:h-10 w-auto object-contain drop-shadow-sm">
            Lisanna <span class="text-emerald-600">Logistic</span>
        </a>
        <div class="hidden md:flex items-center gap-8 font-medium text-slate-600">
            <a href="#services" class="hover:text-emerald-600 transition-colors">Layanan</a>
            <a href="#tracking" class="hover:text-emerald-600 transition-colors">Lacak Paket</a>
            <a href="/admin/login" class="bg-white text-emerald-600 border-2 border-emerald-500 px-6 py-2.5 rounded-full hover:bg-emerald-500 hover:text-white transition-all shadow-sm hover:shadow-emerald-500/25 font-semibold">Login Portal</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-[90vh] flex items-center justify-center px-4 overflow-hidden bg-gradient-to-br from-emerald-50 via-white to-slate-50 pt-20">
        <!-- Background Decoration -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-emerald-400/10 rounded-full blur-3xl -z-10"></div>
        
        <div class="max-w-4xl mx-auto z-10 text-center">
            <span class="px-4 py-1.5 bg-emerald-100 text-emerald-700 font-semibold rounded-full text-sm mb-6 inline-block border border-emerald-200">#1 Solusi Kurir Global</span>
                <div class="mb-8 flex justify-center mx-auto md:mx-0 w-full max-w-lg md:max-w-2xl">
                    <img src="{{ asset('images/logo.png') }}" alt="Lisanna Logistic" class="w-full h-auto object-contain drop-shadow-xl">
                </div>
            <p class="text-lg md:text-xl text-slate-900 font-medium mb-12 max-w-2xl mx-auto leading-relaxed">
                Kirim paket ke seluruh pelosok dengan kepastian pelacakan secara real-time dan pelayanan kurir yang profesional.
            </p>

            <!-- Tracking Card -->
            <div id="tracking" class="bg-white/80 backdrop-blur-xl border border-white/50 p-6 md:p-8 rounded-3xl shadow-2xl shadow-slate-200/50 max-w-2xl mx-auto ring-1 ring-slate-900/5 transition-transform hover:scale-[1.01] duration-300">
                <form action="/tracking" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="search" class="text-slate-400 w-5 h-5"></i>
                        </div>
                        <input type="text" name="receipt_number" class="w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all text-slate-700 placeholder-slate-400 text-lg" placeholder="Masukkan nomor resi..." required>
                    </div>
                    <button type="submit" class="bg-emerald-500 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:bg-emerald-600 hover:-translate-y-1 transition-all shadow-lg shadow-emerald-500/30 flex items-center justify-center gap-2 group whitespace-nowrap">
                        Lacak <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-24 bg-white px-4">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Layanan Unggulan Kami</h2>
            <p class="text-slate-500 mb-16 max-w-2xl mx-auto text-lg">Pilih layanan yang paling sesuai dengan kebutuhan bisnis atau personal Anda.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                <!-- Card 1 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:border-emerald-500 hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-300 group">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm text-emerald-500 group-hover:scale-110 transition-transform">
                        <i data-lucide="truck" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Kurir Domestik</h3>
                    <p class="text-slate-600 leading-relaxed">Pengiriman paket cepat dan efisien ke seluruh pelosok negeri dengan jaringan distribusi terluas dan harga kompetitif.</p>
                </div>

                <!-- Card 2 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:border-emerald-500 hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-300 group">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm text-emerald-500 group-hover:scale-110 transition-transform">
                        <i data-lucide="globe-2" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Pengiriman Internasional</h3>
                    <p class="text-slate-600 leading-relaxed">Kirim dokumen dan barang berharga ke mancanegara dengan kepastian waktu tiba dan keamanan yang terjamin.</p>
                </div>

                <!-- Card 3 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:border-emerald-500 hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-300 group">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm text-emerald-500 group-hover:scale-110 transition-transform">
                        <i data-lucide="package-check" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Solusi Supply Chain</h3>
                    <p class="text-slate-600 leading-relaxed">Manajemen rantai pasok untuk kebutuhan bisnis (B2B) yang membutuhkan sistem pengiriman skala besar secara rutin.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 text-center">
        <p>&copy; 2026 Lisanna Logistic. All rights reserved.</p>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
