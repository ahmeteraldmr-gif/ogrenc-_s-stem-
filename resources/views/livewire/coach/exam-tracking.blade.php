<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Deneme Sonuçları</h2>
        <p class="text-sm text-gray-600 mt-1">Öğrencilerinizin deneme sınav sonuçlarını görüntüleyin ve analiz edin</p>
    </div>

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Öğrenci Seçimi ve Deneme Türleri -->
    <div class="card bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex-1 min-w-[200px] flex items-end gap-3">
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Öğrenci Seçin</label>
                    <select wire:model.live="selectedStudent" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <option value="">Tüm Öğrenciler</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if($selectedStudent)
                    <div style="margin-bottom: 2px;">
                        <a href="{{ route('coach.student.exam-report.pdf', ['student' => $selectedStudent]) }}" class="btn-secondary flex items-center gap-1.5" style="text-decoration: none; display: inline-flex; align-items: center; white-space: nowrap; height: 42px;" target="_blank">
                            <span>📄 PDF Raporu</span>
                        </a>
                    </div>
                @endif
            </div>
            
            @if($selectedStudent && count($studentExamTypes) > 0)
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Girdiği Deneme Türleri</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($studentExamTypes as $examType)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                📝 {{ $examType }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @elseif($selectedStudent && count($studentExamTypes) === 0)
                <div class="flex-1">
                    <p class="text-sm text-gray-600 italic">Bu öğrencinin henüz deneme kaydı bulunmamaktadır.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Tab Selector -->
    <div class="flex border-b border-gray-200 space-x-2">
        <button wire:click="selectTab('TYT')" class="px-6 py-3 font-bold text-sm rounded-t-lg transition-all {{ $activeTab === 'TYT' ? 'bg-white text-blue-600 border-t-2 border-blue-500 border-l border-r border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
            📝 TYT Analizi
        </button>
        <button wire:click="selectTab('AYT')" class="px-6 py-3 font-bold text-sm rounded-t-lg transition-all {{ $activeTab === 'AYT' ? 'bg-white text-purple-600 border-t-2 border-purple-500 border-l border-r border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
            📝 AYT Analizi
        </button>
        <button wire:click="selectTab('Tümü')" class="px-6 py-3 font-bold text-sm rounded-t-lg transition-all {{ $activeTab === 'Tümü' ? 'bg-white text-gray-700 border-t-2 border-gray-500 border-l border-r border-gray-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
            📊 Tümü / Genel
        </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Toplam Deneme -->
        <div class="card bg-blue-50 border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['total_exams'] }}</div>
                    <div class="text-sm text-gray-600 mt-1">Toplam {{ $activeTab === 'Tümü' ? 'Deneme' : $activeTab . ' Sınavı' }}</div>
                </div>
                <div class="rounded-full p-3" style="background-color: rgba(37, 99, 235, 0.15);">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="stroke-width: 2.5px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Ortalama Net -->
        <div class="card bg-green-50 border border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-green-600">{{ number_format($stats['avg_net'] ?? 0, 2) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Ortalama Toplam Net</div>
                </div>
                <div class="rounded-full p-3" style="background-color: rgba(34, 197, 94, 0.15);">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="stroke-width: 2.5px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- En Yüksek Net -->
        <div class="card bg-purple-50 border border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['best_net'] ?? 0, 2) }}</div>
                    <div class="text-sm text-gray-600 mt-1">En Yüksek Toplam Net</div>
                </div>
                <div class="rounded-full p-3" style="background-color: rgba(168, 85, 247, 0.15);">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="stroke-width: 2.5px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- En Düşük Net -->
        <div class="card bg-red-50 border border-red-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-red-600">{{ number_format($stats['worst_net'] ?? 0, 2) }}</div>
                    <div class="text-sm text-gray-600 mt-1">En Düşük Toplam Net</div>
                </div>
                <div class="rounded-full p-3" style="background-color: rgba(239, 68, 68, 0.15);">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="stroke-width: 2.5px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gelişim İstatistikleri -->
    @if($selectedStudent && $stats['total_exams'] > 1)
        <div class="card bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Gelişim Analizi</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg p-4 border border-indigo-200">
                    <div class="text-sm text-gray-600 mb-1">İlk Yarı Ortalama</div>
                    <div class="text-2xl font-bold text-indigo-600">{{ number_format($stats['first_half_avg'] ?? 0, 2) }}</div>
                </div>
                <div class="bg-white rounded-lg p-4 border border-indigo-200">
                    <div class="text-sm text-gray-600 mb-1">İkinci Yarı Ortalama</div>
                    <div class="text-2xl font-bold text-indigo-600">{{ number_format($stats['second_half_avg'] ?? 0, 2) }}</div>
                </div>
                <div class="bg-white rounded-lg p-4 border border-indigo-200">
                    <div class="text-sm text-gray-600 mb-1">Gelişim</div>
                    <div class="text-2xl font-bold {{ ($stats['improvement'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ ($stats['improvement'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($stats['improvement'] ?? 0, 2) }}
                        <span class="text-sm">({{ ($stats['improvement_percentage'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($stats['improvement_percentage'] ?? 0, 1) }}%)</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Filters -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtreler</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alan</label>
                <select wire:model.live="selectedField" class="input-field">
                    <option value="">Tüm Alanlar</option>
                    @foreach($fields as $field)
                        <option value="{{ $field->id }}">{{ $field->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sınav Tipi</label>
                <select wire:model.live="selectedExamType" class="input-field">
                    <option value="">Tüm Tipler</option>
                    @foreach($examTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Başlangıç</label>
                <input type="date" wire:model="dateFrom" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bitiş</label>
                <input type="date" wire:model="dateTo" class="input-field">
            </div>
        </div>
        <div class="flex gap-2 mt-4">
            <button wire:click="applyFilters" class="btn-primary">Filtrele</button>
            <button wire:click="resetFilters" class="btn-secondary">Temizle</button>
        </div>
    </div>

    <!-- Charts -->
    @if($stats['total_exams'] > 0)
        <div class="space-y-6">
            @if($selectedStudent)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <strong>📊 Grafikler:</strong> Seçilen öğrencinin deneme sonuçları grafik olarak gösterilmektedir.
                    </p>
                </div>
            @endif
            
            <!-- Net Gelişimi Grafiği -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    📈 Net Gelişimi - Zaman İçinde Değişim
                    @if($selectedStudent)
                        <span class="text-sm font-normal text-gray-600">(Seçilen Öğrenci)</span>
                    @endif
                </h3>
                <p class="text-sm text-gray-600 mb-4">
                    Öğrencinin deneme sonuçlarındaki net skor gelişimi. TYT ve AYT denemeleri ayrı renklerle gösterilmektedir.
                </p>
                <div style="height: 400px;">
                    <canvas id="netProgressChart"></canvas>
                </div>
            </div>
            
            <!-- Ders Bazlı Gelişim Grafiği (Önemli!) -->
            @if($selectedStudent)
                <div class="card bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-300">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        🎯 Ders Bazlı Gelişim - Deneme Karşılaştırması
                        <span class="text-sm font-normal text-gray-600">(Seçilen Öğrenci)</span>
                    </h3>
                    <p class="text-sm text-gray-700 mb-4">
                        İstediğiniz iki denemeyi seçerek karşılaştırın. Örneğin 5. TYT denemesi ile 6. TYT denemesi gibi.
                        Öğrencinin hangi derslerde ne kadar gelişim gösterdiğini görün.
                    </p>
                    
                    <!-- Deneme Seçim Alanı -->
                    <div class="bg-white rounded-lg p-4 mb-4 border border-green-200">
                        @php
                            $hasExams = isset($availableExams) && is_array($availableExams) && count($availableExams) > 0;
                        @endphp
                        @if($hasExams)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        📊 İlk Deneme (Karşılaştırılacak)
                                    </label>
                                    <select wire:model.live="selectedFirstExam" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                                        <option value="">Otomatik: İlk Deneme</option>
                                        @foreach($availableExams as $exam)
                                            <option value="{{ $exam['id'] }}" {{ $selectedFirstExam == $exam['id'] ? 'selected' : '' }}>
                                                {{ $exam['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        📈 İkinci Deneme (Karşılaştırılacak)
                                    </label>
                                    <select wire:model.live="selectedSecondExam" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                                        <option value="">Otomatik: Son Deneme</option>
                                        @foreach($availableSecondExams as $exam)
                                            <option value="{{ $exam['id'] }}" {{ $selectedSecondExam == $exam['id'] ? 'selected' : '' }}>
                                                {{ $exam['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if($selectedFirstExam && $selectedSecondExam)
                                <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p class="text-sm text-blue-800">
                                        <strong>ℹ️ Bilgi:</strong> Seçilen denemeler karşılaştırılıyor. 
                                        Her iki denemede de aynı ders için sonuç varsa gösterilecektir.
                                    </p>
                                </div>
                            @endif
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <p class="text-sm text-yellow-800">
                                    <strong>⚠️ Uyarı:</strong> Bu öğrenci için henüz deneme sonucu bulunmamaktadır. 
                                    Öğrenci deneme sonuçlarını girdikçe burada görünecektir.
                                </p>
                            </div>
                        @endif
                    </div>
                    
                    @if(isset($chartData['courseDevelopment']) && count($chartData['courseDevelopment']['labels']) > 0)
                        <div class="mb-2 text-sm text-gray-600 bg-blue-50 p-2 rounded">
                            <strong>📊 Grafik Verisi:</strong> {{ count($chartData['courseDevelopment']['labels']) }} ders için karşılaştırma yapılıyor.
                            @if($selectedFirstExam && $selectedSecondExam)
                                <span class="text-green-700">✓ Denemeler seçildi</span>
                            @else
                                <span class="text-yellow-700">⚠ Otomatik mod (ilk/son deneme)</span>
                            @endif
                        </div>
                        <div style="height: 400px;" wire:ignore>
                            <canvas id="courseDevelopmentChart"></canvas>
                        </div>
                        <div class="mt-4">
                            <div class="mb-3 text-sm text-gray-700">
                                <strong>Karşılaştırma:</strong> 
                                <span class="text-green-700">{{ $chartData['courseDevelopment']['firstExamLabel'] }}</span> 
                                vs 
                                <span class="text-blue-700">{{ $chartData['courseDevelopment']['secondExamLabel'] }}</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($chartData['courseDevelopment']['labels'] as $index => $courseName)
                                    @php
                                        $firstNet = $chartData['courseDevelopment']['firstExam'][$index];
                                        $secondNet = $chartData['courseDevelopment']['secondExam'][$index];
                                        $improvement = $chartData['courseDevelopment']['improvement'][$index];
                                        $isPositive = $improvement >= 0;
                                    @endphp
                                    <div class="bg-white rounded-lg p-3 border {{ $isPositive ? 'border-green-300' : 'border-red-300' }}">
                                        <div class="font-semibold text-gray-900 mb-2">{{ $courseName }}</div>
                                        <div class="text-sm text-gray-600">
                                            <div class="flex justify-between mb-1">
                                                <span>{{ $chartData['courseDevelopment']['firstExamLabel'] }}:</span>
                                                <span class="font-medium">{{ number_format($firstNet, 2) }} Net</span>
                                            </div>
                                            <div class="flex justify-between mb-1">
                                                <span>{{ $chartData['courseDevelopment']['secondExamLabel'] }}:</span>
                                                <span class="font-medium">{{ number_format($secondNet, 2) }} Net</span>
                                            </div>
                                            <div class="flex justify-between pt-2 border-t border-gray-200">
                                                <span class="font-semibold">Gelişim:</span>
                                                <span class="font-bold {{ $isPositive ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $isPositive ? '+' : '' }}{{ number_format($improvement, 2) }} Net
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                            <p class="text-gray-600 mb-2 text-center">
                                <strong>📊 Grafik:</strong> Karşılaştırma için deneme seçin veya otomatik olarak ilk ve son deneme karşılaştırılacaktır.
                            </p>
                            @if($selectedFirstExam && $selectedSecondExam && isset($chartData['courseDevelopment']['debug']))
                                <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <p class="text-sm text-yellow-800 mb-2">
                                        <strong>⚠️ Uyarı:</strong> Seçilen denemelerde aynı ders için sonuç bulunamadı. 
                                        Her iki denemede de aynı ders için sonuç olması gerekiyor.
                                    </p>
                                    <div class="text-xs text-gray-700 mt-2">
                                        <div class="mb-1">
                                            <strong>İlk Denemede Bulunan Dersler:</strong> 
                                            {{ implode(', ', array_unique($chartData['courseDevelopment']['debug']['firstExamCourses'] ?? [])) ?: 'Yok' }}
                                        </div>
                                        <div class="mb-1">
                                            <strong>İkinci Denemede Bulunan Dersler:</strong> 
                                            {{ implode(', ', array_unique($chartData['courseDevelopment']['debug']['secondExamCourses'] ?? [])) ?: 'Yok' }}
                                        </div>
                                        <div>
                                            <strong>Ortak Dersler:</strong> 
                                            {{ implode(', ', $chartData['courseDevelopment']['debug']['commonCourses'] ?? []) ?: 'Yok' }}
                                        </div>
                                    </div>
                                </div>
                            @elseif($selectedFirstExam && $selectedSecondExam)
                                <p class="text-sm text-yellow-700 mt-2 text-center">
                                    <strong>⚠️ Uyarı:</strong> Seçilen denemelerde aynı ders için sonuç bulunamadı. 
                                    Her iki denemede de aynı ders için sonuç olması gerekiyor.
                                </p>
                            @else
                                <p class="text-sm text-gray-500 mt-2 text-center">
                                    Denemeleri seçtikten sonra grafik burada görünecektir.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            @endif
            
            <!-- İkinci Satır Grafikler -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        📊 Ders Bazlı Ortalama Performans
                        @if($selectedStudent)
                            <span class="text-sm font-normal text-gray-600">(Seçilen Öğrenci)</span>
                        @endif
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Derslere göre ortalama net skorları
                    </p>
                    <div style="height: 350px;">
                        <canvas id="coursePerformanceChart"></canvas>
                    </div>
                </div>
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        📅 Aylık Ortalama Net Skorları
                        @if($selectedStudent)
                            <span class="text-sm font-normal text-gray-600">(Seçilen Öğrenci)</span>
                        @endif
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Aylara göre ortalama net skor gelişimi
                    </p>
                    <div style="height: 350px;">
                        <canvas id="monthlyAverageChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Üçüncü Satır Grafikler -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        🎯 Deneme Türü Dağılımı
                        @if($selectedStudent)
                            <span class="text-sm font-normal text-gray-600">(Seçilen Öğrenci)</span>
                        @endif
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Girdiği deneme türlerinin dağılımı
                    </p>
                    <div class="flex justify-center" style="height: 350px;">
                        <canvas id="examTypeDistributionChart"></canvas>
                    </div>
                </div>
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        📚 Alan Dağılımı
                        @if($selectedStudent)
                            <span class="text-sm font-normal text-gray-600">(Seçilen Öğrenci)</span>
                        @endif
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Denemelerin alanlara göre dağılımı
                    </p>
                    <div class="flex justify-center" style="height: 350px;">
                        <canvas id="fieldDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function initCharts() {
                // Destroy existing charts if they exist
                if (window.examCharts) {
                    Object.values(window.examCharts).forEach(chart => {
                        if (chart && typeof chart.destroy === 'function') {
                            chart.destroy();
                        }
                    });
                }
                window.examCharts = {};
                
                // Wait a bit for DOM to be ready
                setTimeout(function() {
                    if (window.initExamCharts) {
                        window.initExamCharts(@json($chartData));
                    }
                }, 100);
            }
            
            // DOM ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCharts);
            } else {
                initCharts();
            }
            
            // Livewire event listeners
            document.addEventListener('livewire:init', function() {
                Livewire.hook('morph.updated', ({ el, component }) => {
                    setTimeout(initCharts, 500);
                });
            });
            
            // Fallback for Livewire updates
            document.addEventListener('livewire:update', function() {
                setTimeout(initCharts, 500);
            });
            
            // Listen for Livewire component updates
            Livewire.hook('morph.updated', ({ el, component }) => {
                setTimeout(initCharts, 500);
            });
        </script>
    @else
        <div class="card bg-yellow-50 border border-yellow-200">
            <div class="text-center py-8">
                <svg class="w-16 h-16 mx-auto mb-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="text-gray-600 text-lg">Henüz deneme sonucu bulunmamaktadır.</p>
                <p class="text-gray-500 text-sm mt-2">Öğrenciler deneme sonuçlarını girdikçe burada görünecektir.</p>
            </div>
        </div>
    @endif

    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">📝 Deneme Sınav Listesi</h3>
        <p class="text-xs text-gray-500 mb-3">Satırlara tıklayarak ders bazlı doğru/yanlış ve net detaylarını görüntüleyebilirsiniz.</p>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Öğrenci</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Sınav Adı</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tür</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Doğru</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Yanlış</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Boş</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Toplam Net</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tarih</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Detay</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($examResults as $result)
                        @php
                            $examDateObj = is_string($result->exam_date) ? \Carbon\Carbon::parse($result->exam_date) : $result->exam_date;
                            $examKey = $result->student_id . '|' . $result->exam_name . '|' . $examDateObj->format('Y-m-d');
                            $isExpanded = $expandedExamKey === $examKey;
                        @endphp
                        <tr class="hover:bg-blue-50/40 cursor-pointer transition-colors {{ $isExpanded ? 'bg-blue-50/20' : '' }}" wire:click="toggleExam('{{ $examKey }}')">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $result->student->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                {{ $result->exam_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $result->exam_type ?: 'Genel' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                {{ $result->total_correct }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                {{ $result->total_wrong }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $result->total_blank }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">
                                {{ number_format($result->total_net, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $examDateObj->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <button class="text-blue-600 hover:text-blue-900 transition-transform {{ $isExpanded ? 'rotate-180' : '' }}" style="display: inline-block;">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @if($isExpanded)
                            <tr class="bg-gray-50/50">
                                <td colspan="9" class="px-6 py-4 border-t border-b border-gray-150">
                                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden p-4">
                                        <h4 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-1.5">
                                            📊 Sınav Ders Detayları: <span class="text-blue-600">{{ $result->exam_name }}</span>
                                        </h4>
                                        <table class="min-w-full divide-y divide-gray-150 text-left">
                                            <thead>
                                                <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                                                    <th class="px-4 py-2">Ders Adı</th>
                                                    <th class="px-4 py-2">Doğru</th>
                                                    <th class="px-4 py-2">Yanlış</th>
                                                    <th class="px-4 py-2">Boş</th>
                                                    <th class="px-4 py-2">Net</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100 text-sm">
                                                @foreach($this->getExamDetails($result->student_id, $result->exam_name, $examDateObj) as $detail)
                                                    <tr>
                                                        <td class="px-4 py-2.5 font-medium text-gray-800">{{ $detail->course?->name ?? 'Genel' }}</td>
                                                        <td class="px-4 py-2.5 text-green-600 font-semibold">{{ $detail->correct_answers }}</td>
                                                        <td class="px-4 py-2.5 text-red-600 font-semibold">{{ $detail->wrong_answers }}</td>
                                                        <td class="px-4 py-2.5 text-gray-500">{{ $detail->blank_answers }}</td>
                                                        <td class="px-4 py-2.5 text-blue-600 font-bold">{{ number_format($detail->net_score, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <div class="text-center">
                                    <svg class="h-12 w-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <span class="block text-gray-600 font-semibold">Henüz deneme sonucu bulunmamaktadır.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ $examResults->links() }}
        </div>
    </div>
</div>
