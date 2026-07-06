<!DOCTYPE html>
<html lang="tr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RehberKoçum - Akıllı Öğrenci Takip ve Eğitim Koçluğu Platformu</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FAF9F6;
        }
        h1, h2, h3, h4, .font-display {
            font-family: 'Outfit', sans-serif;
        }
        .hero-gradient {
            background: radial-gradient(circle at 80% 20%, rgba(99, 102, 241, 0.08) 0%, rgba(255, 255, 255, 0) 50%),
                        radial-gradient(circle at 20% 80%, rgba(16, 185, 129, 0.08) 0%, rgba(255, 255, 255, 0) 50%);
        }
        .glass-header {
            backdrop-filter: blur(12px);
            background-color: rgba(250, 249, 246, 0.8);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px -10px rgba(0, 0, 0, 0.08);
        }
        .btn-gradient-blue {
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient-blue:hover {
            box-shadow: 0 4px 15px -3px rgba(79, 70, 229, 0.4);
            opacity: 0.95;
        }
        .text-gradient {
            background: linear-gradient(135deg, #1e1b4b 0%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col justify-between hero-gradient text-slate-800">

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 glass-header w-full transition-all">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <span class="text-2xl font-black tracking-tight text-slate-900">
                    rehber<span class="text-indigo-600">koçum</span>
                </span>
            </div>
            
            <nav class="hidden md:flex items-center space-x-8 text-sm font-semibold text-slate-600">
                <a href="#nedir" class="hover:text-indigo-600 transition">Nedir?</a>
                <a href="#neden-var" class="hover:text-indigo-600 transition">Neden RehberKoçum?</a>
                <a href="#ozellikler" class="hover:text-indigo-600 transition">Özellikler</a>
                <a href="#paketler" class="hover:text-indigo-600 transition">Paketler</a>
            </nav>
            
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isCoach() ? route('coach.dashboard') : route('student.dashboard')) }}" 
                       class="px-5 py-2.5 btn-gradient-blue text-white rounded-xl text-sm font-bold shadow-md">
                        Panele Git
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 border border-slate-200 text-slate-700 bg-white rounded-xl text-sm font-bold hover:bg-slate-50 transition shadow-sm">
                        Giriş Yap
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Section: Hero & Introduction -->
    <main class="flex-grow w-full max-w-7xl mx-auto px-6 py-12 md:py-20 space-y-24">
        
        <!-- Hero Section -->
        <section class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
            <!-- Left: Hero Text & Demo -->
            <div class="flex-1 space-y-8">
                <div class="space-y-4">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm">
                        ⚡ Yapay Zeka Destekli & Akıllı Koçluk Sistemi
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 leading-tight">
                        Öğrencilerinizi <br>
                        <span class="text-gradient">Akıllı İlerleme</span> <br>ile Takip Edin
                    </h1>
                    <p class="text-base md:text-lg text-slate-600 max-w-xl leading-relaxed">
                        Sınava hazırlanan öğrenciler için ders, konu analizi, günlük soru takibi ve deneme gelişimlerini tek bir akıllı platformdan yönetin. Koçluk verimliliğinizi 3 katına çıkarın.
                    </p>
                </div>

                <!-- Demo Access Box -->
                <div class="bg-white rounded-3xl p-6 shadow-xl border border-slate-100 space-y-4 transition-all hover:shadow-2xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Hızlı Demo Girişleri</h3>
                        <span class="text-[10px] text-indigo-500 font-bold bg-indigo-50 px-2 py-0.5 rounded">Kurulumsuz Test</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <!-- Admin Login -->
                        <form method="POST" action="{{ route('login') }}" class="w-full">
                            @csrf
                            <input type="hidden" name="email" value="admin@ogrenci.com">
                            <input type="hidden" name="password" value="password">
                            <button type="submit" class="w-full py-3.5 px-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition flex flex-col items-center justify-center gap-0.5 shadow-md">
                                <span class="text-xs">🔑 Demo Admin</span>
                                <span class="text-[9px] text-slate-400 font-normal">Sistem Yönetimi</span>
                            </button>
                        </form>

                        <!-- Coach Login -->
                        <form method="POST" action="{{ route('login') }}" class="w-full">
                            @csrf
                            <input type="hidden" name="email" value="coach1@ogrenci.com">
                            <input type="hidden" name="password" value="password">
                            <button type="submit" class="w-full py-3.5 px-4 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition flex flex-col items-center justify-center gap-0.5 shadow-md">
                                <span class="text-xs">🧠 Demo Koç</span>
                                <span class="text-[9px] text-indigo-200 font-normal">Öğrenci & Program</span>
                            </button>
                        </form>

                        <!-- Student Login -->
                        <form method="POST" action="{{ route('login') }}" class="w-full">
                            @csrf
                            <input type="hidden" name="email" value="student1@ogrenci.com">
                            <input type="hidden" name="password" value="password">
                            <button type="submit" class="w-full py-3.5 px-4 bg-emerald-600 text-white rounded-2xl font-bold hover:bg-emerald-700 transition flex flex-col items-center justify-center gap-0.5 shadow-md">
                                <span class="text-xs">🎓 Demo Öğrenci</span>
                                <span class="text-[9px] text-emerald-200 font-normal">Soru & Sınav Takibi</span>
                            </button>
                        </form>
                    </div>
                    <p class="text-[11px] text-slate-500 text-center italic mt-2">
                        💡 Butonlara tıklayarak doğrudan demo panellerine erişebilirsiniz. Şifreler otomatik doldurulur.
                    </p>
                </div>
            </div>

            <!-- Right: Beautiful Abstract Interface Mockup -->
            <div class="w-full lg:w-[500px] flex items-center justify-center relative">
                <div class="absolute inset-0 bg-indigo-400 rounded-full blur-3xl opacity-10 -z-10"></div>
                <div class="bg-gradient-to-tr from-indigo-100 to-emerald-50 border border-slate-200 rounded-3xl p-6 shadow-xl w-full space-y-4">
                    <!-- Fake Card 1 -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-indigo-50 rounded-xl text-indigo-600">📈</div>
                            <div>
                                <span class="text-xs text-slate-400 block font-bold">Öğrenci Gelişimi</span>
                                <span class="text-sm font-extrabold text-slate-900">Son TYT: 98.75 Net</span>
                            </div>
                        </div>
                        <span class="text-xs text-green-600 font-black bg-green-50 px-2 py-0.5 rounded">+4.2 Net</span>
                    </div>

                    <!-- Fake Card 2 -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="text-xs font-extrabold text-slate-800">Günlük Soru Hedefi</span>
                            <span class="text-xs text-indigo-600 font-bold">340 / 400 Soru</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-indigo-500 h-full rounded-full" style="width: 85%;"></div>
                        </div>
                    </div>

                    <!-- Fake Card 3 -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-emerald-50 rounded-xl text-emerald-600">📅</div>
                            <div>
                                <span class="text-xs text-slate-400 block font-bold">Haftalık Program</span>
                                <span class="text-xs font-semibold text-slate-600">Pazartesi: Matematik & Türkçe</span>
                            </div>
                        </div>
                        <span class="text-[10px] text-slate-400">Aktif</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Nedir Section (separation/ne işe yarar) -->
        <section id="nedir" class="space-y-12">
            <div class="text-center space-y-4 max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">RehberKoçum Ne İşe Yarar?</h2>
                <p class="text-slate-600">RehberKoçum, eğitim koçları ile sınava hazırlanan öğrenciler arasındaki iletişimi dijitalleştiren ve hızlandıran akıllı bir takip platformudur.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm card-hover transition-all">
                    <div class="text-3xl mb-4">🎯</div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Hassas Takip Yolu</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Öğrencinizin hangi dersten, hangi konuyu ve alt konuyu tamamladığını basamak basamak görün, eksik noktaları tespit edin.</p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm card-hover transition-all">
                    <div class="text-3xl mb-4">⚡</div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Hızlı Program Yapımı</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Saatlik ya da serbest çalışma hedefleri koyarak dakikalar içinde haftalık çalışma programı oluşturun ve öğrenciye atayın.</p>
                </div>
                <!-- Card 3 -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm card-hover transition-all">
                    <div class="text-3xl mb-4">📊</div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">TYT / AYT Analizleri</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Deneme sınav sonuçlarını net, doğru ve yanlış sayılarıyla takip edin. Gelişimi sekmeli grafiklerle anında analiz edin.</p>
                </div>
                <!-- Card 4 -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm card-hover transition-all">
                    <div class="text-3xl mb-4">📄</div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">PDF Raporlama</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Tek tıklamayla tüm deneme gelişim verilerini ve ders ortalamalarını barındıran Türkçe karakter uyumlu PDF çıktısını alın.</p>
                </div>
            </div>
        </section>

        <!-- Neden RehberKoçum Var Section (Neden Varız?) -->
        <section id="neden-var" class="bg-indigo-900 text-white rounded-3xl p-8 md:p-12 shadow-xl relative overflow-hidden flex flex-col md:flex-row items-center gap-8 justify-between">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_120%,rgba(99,102,241,0.3),transparent_70%)]"></div>
            <div class="space-y-4 max-w-2xl z-10">
                <span class="text-xs font-bold tracking-widest text-indigo-300 uppercase">Biz Neden Varız?</span>
                <h2 class="text-3xl md:text-4xl font-extrabold leading-tight">Geleneksel, Dağınık Takip Sistemlerine Son Vermek İçin</h2>
                <p class="text-sm md:text-base text-indigo-100/90 leading-relaxed">
                    Sınava hazırlık süreci karmaşık ve streslidir. Koçların ve kurumların öğrencileri WhatsApp mesajları, Excel dosyaları veya fiziksel ajandalar üzerinden takip etmesi büyük zaman kaybına ve kritik bilgilerin kaçmasına yol açar. RehberKoçum, her şeyi tek bir bulut tabanlı merkezde birleştirerek koçların işini otomatikleştirir, öğrencilere ise verilerle desteklenmiş bir yol haritası sunar.
                </p>
            </div>
            <div class="z-10 flex-shrink-0 bg-indigo-800/80 backdrop-blur border border-indigo-700 p-6 rounded-2xl space-y-3 w-full md:w-80">
                <h4 class="text-sm font-bold text-indigo-200 uppercase tracking-wider">Geliştirme Amacımız</h4>
                <ul class="text-xs space-y-2 text-indigo-100">
                    <li class="flex items-center gap-2">🟢 Kağıt/Excel dağınıklığını önlemek</li>
                    <li class="flex items-center gap-2">🟢 Öğrenciyi verilerle motive etmek</li>
                    <li class="flex items-center gap-2">🟢 Koçların raporlama süresini azaltmak</li>
                    <li class="flex items-center gap-2">🟢 Net hedeflerle başarı oranını artırmak</li>
                </ul>
            </div>
        </section>

        <!-- Detaylı Özellikler (Core Features) -->
        <section id="ozellikler" class="space-y-12">
            <div class="text-center space-y-4 max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">Güçlü Eğitim Altyapısı</h2>
                <p class="text-slate-600">Hem koçun hem öğrencinin ihtiyaç duyduğu tüm araçlar en premium tasarımla bir arada.</p>
            </div>

            <div class="space-y-6">
                <!-- Feature 1 -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col md:flex-row items-center gap-8">
                    <div class="p-4 bg-indigo-50 rounded-2xl text-4xl text-indigo-600 flex-shrink-0">📚</div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-slate-900">Konu & Müfredat Ağacı</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Sistemdeki tüm alanlar (Sayısal, Sözel, Eşit Ağırlık, Dil) altında dersler, konular ve alt konular olarak hiyerarşik yapıdadır. Öğrenci tamamladığı alt konuları işaretlediğinde koç bunu anlık olarak kendi panelinde görür.
                        </p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col md:flex-row items-center gap-8">
                    <div class="p-4 bg-emerald-50 rounded-2xl text-4xl text-emerald-600 flex-shrink-0">🗓️</div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-slate-900">Gelişmiş Program Sihirbazı</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Koçlar, öğrencilerine haftalık çalışma planları oluştururken saatli (09:00 - 10:30 gibi) veya serbest hedefli görevler oluşturabilir. Öğrenci gün içinde tamamladığı görevleri işaretledikçe koç gelişim oranını anlık olarak izleyebilir.
                        </p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col md:flex-row items-center gap-8">
                    <div class="p-4 bg-purple-50 rounded-2xl text-4xl text-purple-600 flex-shrink-0">📊</div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-slate-900">TYT/AYT Gelişim Grafikleri ve Karşılaştırma</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Gelişmiş sekmeli yapı sayesinde TYT ve AYT sınavları tamamen ayrıştırılır. En iyi netler, genel ders ortalamaları ve zaman içindeki gelişim grafikleri tek ekrandan izlenir. İki farklı deneme ders bazında yan yana karşılaştırılabilir.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Subscription Packages (Paketler ayrı ve detaylı tasarım) -->
        <section id="paketler" class="space-y-12">
            <div class="text-center space-y-4 max-w-3xl mx-auto">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Bütçenize Uygun Seçenekler</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">Abonelik Paketleri</h2>
                <p class="text-slate-600">İhtiyacınıza uygun paketi seçin, öğrencilerinizi akıllı sistemle takip etmeye hemen başlayın.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Package 1: Başlangıç -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between space-y-6 card-hover transition-all">
                    <div class="space-y-3">
                        <span class="text-xs font-bold text-slate-400 uppercase">Başlangıç</span>
                        <h3 class="text-3xl font-black text-slate-900">₺199<span class="text-xs font-medium text-slate-400">/ay</span></h3>
                        <p class="text-xs text-slate-500">Bireysel ve küçük ölçekli koçluk yapanlar için ideal.</p>
                        <hr class="border-slate-100">
                        <ul class="text-xs space-y-2.5 text-slate-600">
                            <li>✔️ <strong>10 Öğrenciye Kadar</strong> Kayıt</li>
                            <li>✔️ Hiyerarşik Ders & Konu Takibi</li>
                            <li>✔️ Haftalık Program Hazırlama</li>
                            <li>✔️ Soru Çözüm Analizi</li>
                            <li>❌ Detaylı PDF Raporlama</li>
                        </ul>
                    </div>
                    <button class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-xs font-bold transition">Paketi Seç</button>
                </div>

                <!-- Package 2: Standart -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between space-y-6 card-hover transition-all">
                    <div class="space-y-3">
                        <span class="text-xs font-bold text-slate-400 uppercase">Standart</span>
                        <h3 class="text-3xl font-black text-slate-900">₺399<span class="text-xs font-medium text-slate-400">/ay</span></h3>
                        <p class="text-xs text-slate-500">Genişleyen öğrenci portföyleri için en dengeli paket.</p>
                        <hr class="border-slate-100">
                        <ul class="text-xs space-y-2.5 text-slate-600">
                            <li>✔️ <strong>25 Öğrenciye Kadar</strong> Kayıt</li>
                            <li>✔️ Hiyerarşik Ders & Konu Takibi</li>
                            <li>✔️ Haftalık Program Hazırlama</li>
                            <li>✔️ Soru Çözüm Analizi</li>
                            <li>✔️ Deneme Sonuçları & Analiz</li>
                            <li>✔️ PDF Rapor İndirme</li>
                        </ul>
                    </div>
                    <button class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-xs font-bold transition">Paketi Seç</button>
                </div>

                <!-- Package 3: Premium -->
                <div class="bg-white rounded-3xl border border-indigo-200 shadow-md p-6 flex flex-col justify-between space-y-6 card-hover transition-all relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-indigo-600 text-white text-[9px] font-bold px-3 py-1 rounded-bl-xl uppercase tracking-wider">Popüler</div>
                    <div class="space-y-3">
                        <span class="text-xs font-bold text-indigo-500 uppercase">Premium</span>
                        <h3 class="text-3xl font-black text-slate-900">₺699<span class="text-xs font-medium text-slate-400">/ay</span></h3>
                        <p class="text-xs text-slate-500">Profesyonel koçlar ve butik kurs merkezleri için tam güç.</p>
                        <hr class="border-slate-100">
                        <ul class="text-xs space-y-2.5 text-slate-600">
                            <li>✔️ <strong>50 Öğrenciye Kadar</strong> Kayıt</li>
                            <li>✔️ Tüm Standart Özellikler</li>
                            <li>✔️ Akıllı Gelişim Grafikleri</li>
                            <li>✔️ Sınav Karşılaştırma Modülü</li>
                            <li>✔️ PDF Rapor Çıktıları</li>
                            <li>✔️ 7/24 Teknik Destek</li>
                        </ul>
                    </div>
                    <button class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-indigo-100">Paketi Seç</button>
                </div>

                <!-- Package 4: Sınırsız -->
                <div class="bg-slate-900 text-white rounded-3xl border border-slate-800 p-6 flex flex-col justify-between space-y-6 card-hover transition-all relative overflow-hidden">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_0%,rgba(16,185,129,0.15),transparent_60%)]"></div>
                    <div class="space-y-3 z-10">
                        <span class="text-xs font-bold text-emerald-400 uppercase">Kurumsal / Sınırsız</span>
                        <h3 class="text-3xl font-black text-white">₺999<span class="text-xs font-medium text-slate-500">/ay</span></h3>
                        <p class="text-xs text-slate-400">Kurumlar ve sınırsız büyüme hedefleyenler için sınırsız seçenek.</p>
                        <hr class="border-slate-800">
                        <ul class="text-xs space-y-2.5 text-slate-300">
                            <li>✔️ <strong>Sınırsız Öğrenci</strong> Kaydı</li>
                            <li>✔️ Tüm Sistem Özellikleri Aktif</li>
                            <li>✔️ Öncelikli Altyapı ve Sunucu</li>
                            <li>✔️ Kurumsal Raporlama Şablonu</li>
                            <li>✔️ Özel Müşteri Temsilcisi</li>
                        </ul>
                    </div>
                    <button class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition z-10">İletişime Geç</button>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer Section -->
    <footer class="w-full border-t border-slate-200 bg-white py-12 mt-16 text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 justify-between">
            <div class="space-y-3">
                <span class="text-lg font-black tracking-tight text-slate-900">
                    rehber<span class="text-indigo-600">koçum</span>
                </span>
                <p class="text-slate-500 max-w-xs leading-relaxed">
                    Eğitim koçluğu sürecini akıllı yazılım çözümleriyle kolaylaştırıp öğrencilerinizi başarıya taşıyoruz.
                </p>
            </div>
            
            <div class="space-y-3">
                <h4 class="text-slate-800 font-bold uppercase tracking-wider text-[10px]">Hızlı Bağlantılar</h4>
                <ul class="space-y-2 text-slate-500">
                    <li><a href="#nedir" class="hover:text-indigo-600 transition">Nedir?</a></li>
                    <li><a href="#neden-var" class="hover:text-indigo-600 transition">Neden RehberKoçum?</a></li>
                    <li><a href="#ozellikler" class="hover:text-indigo-600 transition">Özellikler</a></li>
                    <li><a href="#paketler" class="hover:text-indigo-600 transition">Abonelik Paketleri</a></li>
                </ul>
            </div>

            <div class="space-y-3">
                <p>&copy; 2026 rehberkoçum. Tüm hakları saklıdır.</p>
                <p>Bulut tabanlı öğrenci takip platformu. Özel kullanım lisansı.</p>
            </div>
        </div>
    </footer>

</body>
</html>
