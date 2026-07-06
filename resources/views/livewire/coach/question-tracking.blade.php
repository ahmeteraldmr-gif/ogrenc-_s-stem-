<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Soru Çözüm Raporları</h2>
            <p class="text-sm text-gray-600 mt-1">Öğrencilerinizin soru çözüm kayıtlarını görüntüleyin ve konu bazlı gelişimlerini takip edin</p>
        </div>
    </div>

    <!-- İstatistikler -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="card bg-blue-50 border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-blue-600">{{ number_format($stats['total_questions'] ?? 0) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Toplam Soru</div>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="card bg-green-50 border border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-green-600">{{ number_format($stats['total_correct'] ?? 0) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Doğru</div>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="card bg-red-50 border border-red-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-red-600">{{ number_format($stats['total_wrong'] ?? 0) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Yanlış</div>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="card bg-gray-50 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-gray-600">{{ number_format($stats['total_blank'] ?? 0) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Boş</div>
                </div>
                <div class="p-3 bg-gray-100 rounded-full">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="card bg-purple-50 border border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['success_rate'] ?? 0, 1) }}%</div>
                    <div class="text-sm text-gray-600 mt-1">Başarı Oranı</div>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtreler</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Öğrenci</label>
                <select wire:model.live="selectedStudent" class="input-field">
                    <option value="">Tüm Öğrenciler</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ders</label>
                <select wire:model.live="selectedCourse" class="input-field">
                    <option value="">Tüm Dersler</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Konu</label>
                <select wire:model.live="selectedTopic" class="input-field">
                    <option value="">Tüm Konular</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alt Konu</label>
                <select wire:model.live="selectedSubTopic" class="input-field">
                    <option value="">Tüm Alt Konular</option>
                    @foreach($subTopics as $subTopic)
                        <option value="{{ $subTopic->id }}">{{ $subTopic->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Başlangıç</label>
                <input type="date" wire:model.live="dateFrom" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bitiş</label>
                <input type="date" wire:model.live="dateTo" class="input-field">
            </div>
        </div>
        <div class="flex gap-2 mt-4">
            <button wire:click="applyFilters" class="btn-primary">Filtrele</button>
            <button wire:click="resetFilters" class="btn-secondary">Temizle</button>
        </div>
    </div>

    <!-- Grafikler -->
    @if($stats['total_questions'] > 0)
        <div class="space-y-6">
            @if($selectedStudent)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <strong>📊 Grafikler:</strong> Seçilen öğrencinin soru çözüm verileri grafik olarak gösterilmektedir.
                    </p>
                </div>
            @endif
            
            <!-- Günlük Soru Çözüm Grafiği -->
            @if(isset($chartData['dailyProgress']) && count($chartData['dailyProgress']['labels']) > 0)
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        📈 Günlük Soru Çözüm Gelişimi
                        @if($selectedStudent)
                            <span class="text-sm font-normal text-gray-600">(Seçilen Öğrenci)</span>
                        @endif
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Zaman içinde günlük soru çözüm sayıları ve başarı oranı
                    </p>
                    <div style="height: 400px;">
                        <canvas id="dailyProgressChart"></canvas>
                    </div>
                </div>
            @endif
            
            <!-- Konu Bazlı Gelişim -->
            @if(isset($chartData['topicProgress']) && count($chartData['topicProgress']['labels']) > 0)
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        🎯 Konu Bazlı Gelişim - İlk vs Son Başarı Oranı
                        @if($selectedStudent)
                            <span class="text-sm font-normal text-gray-600">(Seçilen Öğrenci)</span>
                        @endif
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Her konu için ilk ve son kayıttaki başarı oranı karşılaştırması
                    </p>
                    <div style="height: 400px;">
                        <canvas id="topicProgressChart"></canvas>
                    </div>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($chartData['topicProgress']['labels'] as $index => $topicName)
                            @php
                                $firstRate = $chartData['topicProgress']['firstSuccessRate'][$index];
                                $lastRate = $chartData['topicProgress']['lastSuccessRate'][$index];
                                $improvement = $chartData['topicProgress']['improvement'][$index];
                                $isPositive = $improvement >= 0;
                            @endphp
                            <div class="bg-white rounded-lg p-3 border {{ $isPositive ? 'border-green-300' : 'border-red-300' }}">
                                <div class="font-semibold text-gray-900 mb-2">{{ $topicName }}</div>
                                <div class="text-sm text-gray-600">
                                    <div class="flex justify-between mb-1">
                                        <span>İlk Başarı:</span>
                                        <span class="font-medium">{{ number_format($firstRate, 1) }}%</span>
                                    </div>
                                    <div class="flex justify-between mb-1">
                                        <span>Son Başarı:</span>
                                        <span class="font-medium">{{ number_format($lastRate, 1) }}%</span>
                                    </div>
                                    <div class="flex justify-between pt-2 border-t border-gray-200">
                                        <span class="font-semibold">Gelişim:</span>
                                        <span class="font-bold {{ $isPositive ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $isPositive ? '+' : '' }}{{ number_format($improvement, 1) }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- İkinci Satır Grafikler -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if(isset($chartData['coursePerformance']) && count($chartData['coursePerformance']['labels']) > 0)
                    <div class="card">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            📊 Ders Bazlı Performans
                            @if($selectedStudent)
                                <span class="text-sm font-normal text-gray-600">(Seçilen Öğrenci)</span>
                            @endif
                        </h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Derslere göre toplam soru sayısı ve başarı oranları
                        </p>
                        <div style="height: 350px;">
                            <canvas id="coursePerformanceChart"></canvas>
                        </div>
                    </div>
                @endif
                
                @if(isset($chartData['monthlyAverage']) && count($chartData['monthlyAverage']['labels']) > 0)
                    <div class="card">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            📅 Aylık Ortalama Başarı Oranı
                            @if($selectedStudent)
                                <span class="text-sm font-normal text-gray-600">(Seçilen Öğrenci)</span>
                            @endif
                        </h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Aylara göre ortalama başarı oranı gelişimi
                        </p>
                        <div style="height: 350px;">
                            <canvas id="monthlyAverageChart"></canvas>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <script>
            function initQuestionCharts() {
                // Destroy existing charts
                if (window.questionCharts) {
                    Object.values(window.questionCharts).forEach(chart => {
                        if (chart && typeof chart.destroy === 'function') {
                            chart.destroy();
                        }
                    });
                }
                window.questionCharts = {};
                
                setTimeout(function() {
                    const chartData = @json($chartData);
                    
                    // Daily Progress Chart
                    const dailyCtx = document.getElementById('dailyProgressChart');
                    if (dailyCtx && chartData.dailyProgress && chartData.dailyProgress.labels.length > 0) {
                        window.questionCharts.dailyProgress = new Chart(dailyCtx, {
                            type: 'line',
                            data: {
                                labels: chartData.dailyProgress.labels,
                                datasets: [
                                    {
                                        label: 'Toplam Soru',
                                        data: chartData.dailyProgress.totalQuestions,
                                        borderColor: 'rgb(59, 130, 246)',
                                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                        tension: 0.4,
                                        fill: true,
                                        yAxisID: 'y',
                                    },
                                    {
                                        label: 'Doğru Cevaplar',
                                        data: chartData.dailyProgress.correctAnswers,
                                        borderColor: 'rgb(16, 185, 129)',
                                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                        tension: 0.4,
                                        fill: true,
                                        yAxisID: 'y',
                                    },
                                    {
                                        label: 'Başarı Oranı (%)',
                                        data: chartData.dailyProgress.successRate,
                                        borderColor: 'rgb(245, 158, 11)',
                                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                                        tension: 0.4,
                                        fill: false,
                                        yAxisID: 'y1',
                                        borderDash: [5, 5],
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    mode: 'index',
                                    intersect: false,
                                },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        padding: 12,
                                    }
                                },
                                scales: {
                                    y: {
                                        type: 'linear',
                                        display: true,
                                        position: 'left',
                                        beginAtZero: true,
                                    },
                                    y1: {
                                        type: 'linear',
                                        display: true,
                                        position: 'right',
                                        beginAtZero: true,
                                        max: 100,
                                        grid: {
                                            drawOnChartArea: false,
                                        },
                                    },
                                }
                            }
                        });
                    }
                    
                    // Topic Progress Chart
                    const topicCtx = document.getElementById('topicProgressChart');
                    if (topicCtx && chartData.topicProgress && chartData.topicProgress.labels.length > 0) {
                        window.questionCharts.topicProgress = new Chart(topicCtx, {
                            type: 'bar',
                            data: {
                                labels: chartData.topicProgress.labels,
                                datasets: [
                                    {
                                        label: 'İlk Başarı Oranı (%)',
                                        data: chartData.topicProgress.firstSuccessRate,
                                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                                        borderColor: 'rgb(239, 68, 68)',
                                        borderWidth: 2,
                                    },
                                    {
                                        label: 'Son Başarı Oranı (%)',
                                        data: chartData.topicProgress.lastSuccessRate,
                                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                                        borderColor: 'rgb(16, 185, 129)',
                                        borderWidth: 2,
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        padding: 12,
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100,
                                        ticks: {
                                            callback: function(value) {
                                                return value + '%';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                    
                    // Course Performance Chart
                    const courseCtx = document.getElementById('coursePerformanceChart');
                    if (courseCtx && chartData.coursePerformance && chartData.coursePerformance.labels.length > 0) {
                        window.questionCharts.coursePerformance = new Chart(courseCtx, {
                            type: 'bar',
                            data: {
                                labels: chartData.coursePerformance.labels,
                                datasets: [
                                    {
                                        label: 'Toplam Soru',
                                        data: chartData.coursePerformance.totalQuestions,
                                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                                        borderColor: 'rgb(59, 130, 246)',
                                        borderWidth: 2,
                                        yAxisID: 'y',
                                    },
                                    {
                                        label: 'Başarı Oranı (%)',
                                        data: chartData.coursePerformance.successRate,
                                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                                        borderColor: 'rgb(16, 185, 129)',
                                        borderWidth: 2,
                                        type: 'line',
                                        yAxisID: 'y1',
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        padding: 12,
                                    }
                                },
                                scales: {
                                    y: {
                                        type: 'linear',
                                        display: true,
                                        position: 'left',
                                        beginAtZero: true,
                                    },
                                    y1: {
                                        type: 'linear',
                                        display: true,
                                        position: 'right',
                                        beginAtZero: true,
                                        max: 100,
                                        grid: {
                                            drawOnChartArea: false,
                                        },
                                        ticks: {
                                            callback: function(value) {
                                                return value + '%';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                    
                    // Monthly Average Chart
                    const monthlyCtx = document.getElementById('monthlyAverageChart');
                    if (monthlyCtx && chartData.monthlyAverage && chartData.monthlyAverage.labels.length > 0) {
                        window.questionCharts.monthlyAverage = new Chart(monthlyCtx, {
                            type: 'bar',
                            data: {
                                labels: chartData.monthlyAverage.labels,
                                datasets: [{
                                    label: 'Başarı Oranı (%)',
                                    data: chartData.monthlyAverage.successRate,
                                    backgroundColor: 'rgba(139, 92, 246, 0.8)',
                                    borderColor: 'rgb(139, 92, 246)',
                                    borderWidth: 2,
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        padding: 12,
                                        callbacks: {
                                            label: function(context) {
                                                return 'Başarı Oranı: ' + context.parsed.y.toFixed(1) + '%';
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100,
                                        ticks: {
                                            callback: function(value) {
                                                return value + '%';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                }, 100);
            }
            
            // DOM ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initQuestionCharts);
            } else {
                initQuestionCharts();
            }
            
            // Livewire event listeners
            document.addEventListener('livewire:init', function() {
                Livewire.hook('morph.updated', ({ el, component }) => {
                    setTimeout(initQuestionCharts, 500);
                });
            });
            
            document.addEventListener('livewire:update', function() {
                setTimeout(initQuestionCharts, 500);
            });
        </script>
    @endif

    <!-- Soru Kayıtları Tablosu -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Soru Kayıtları</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Öğrenci</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Konu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Toplam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doğru</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Yanlış</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Boş</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Başarı %</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tarih</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($questionLogs as $log)
                        @php
                            $successRate = $log->total_questions > 0 
                                ? round(($log->correct_answers / $log->total_questions) * 100, 1)
                                : 0;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $log->student->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->course?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->topic?->name ?? '-' }}
                                @if($log->subTopic)
                                    <span class="text-xs text-gray-400">/ {{ $log->subTopic->name }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->total_questions }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                                {{ $log->correct_answers }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                                {{ $log->wrong_answers }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->blank_answers }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold 
                                {{ $successRate >= 70 ? 'text-green-600' : ($successRate >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $successRate }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->log_date->format('d.m.Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                Henüz soru kaydı bulunmamaktadır.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ $questionLogs->links() }}
        </div>
    </div>
</div>
