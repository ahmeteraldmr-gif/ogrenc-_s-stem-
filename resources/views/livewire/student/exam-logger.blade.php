<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Deneme Takibi</h2>
            <p class="text-sm text-gray-600 mt-1">Deneme sınavı sonuçlarınızı kaydedin</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('student.exam-report.pdf') }}" class="btn-secondary flex items-center gap-1.5" style="text-decoration: none; display: inline-flex; align-items: center;" target="_blank">
                <span>📄 PDF Raporu İndir</span>
            </a>
            <button wire:click="openModal" class="btn-primary">
                + Deneme Ekle
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="card bg-blue-50">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_exams'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Toplam Deneme</div>
        </div>
        <div class="card bg-green-50">
            <div class="text-3xl font-bold text-green-600">{{ number_format($stats['avg_net'] ?? 0, 2) }}</div>
            <div class="text-sm text-gray-600 mt-1">Ortalama Net</div>
        </div>
        <div class="card bg-purple-50">
            <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['best_net'] ?? 0, 2) }}</div>
            <div class="text-sm text-gray-600 mt-1">En Yüksek Net</div>
        </div>
        <div class="card bg-red-50">
            <div class="text-3xl font-bold text-red-600">{{ number_format($stats['worst_net'] ?? 0, 2) }}</div>
            <div class="text-sm text-gray-600 mt-1">En Düşük Net</div>
        </div>
    </div>

    <!-- Field Stats -->
    @if(count($fieldStats) > 0)
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Alan Bazlı İstatistikler</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($fieldStats as $fieldName => $stats)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm font-medium text-gray-700 mb-2">{{ $fieldName }}</div>
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['avg_net'], 2) }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $stats['count'] }} deneme</div>
                        <div class="text-xs text-gray-500">En iyi: {{ number_format($stats['best_net'], 2) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Exams Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tarih</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deneme Adı</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tür</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doğru</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Yanlış</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Boş</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Net</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İşlem</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($examResults as $result)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $result->exam_date->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $result->exam_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $result->exam_type ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $result->field?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $result->course?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                {{ $result->correct_answers }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                {{ $result->wrong_answers }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $result->blank_answers }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">
                                {{ number_format($result->net_score, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button 
                                    wire:click="delete({{ $result->id }})"
                                    onclick="return confirm('Bu kaydı silmek istediğinize emin misiniz?')"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Sil
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                                Henüz deneme kaydı bulunmamaktadır.
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

    <!-- Deneme Analiz ve Karşılaştırma Bölümü -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Grafik Kartı -->
        <div class="card bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span>📈</span> Deneme Gelişim Grafiği (Son 8 Sınav)
            </h3>
            <div style="position: relative; height: 320px; width: 100%;">
                <canvas id="progressChart" wire:ignore></canvas>
            </div>
        </div>

        <!-- Karşılaştırma Kartı -->
        <div class="card bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center gap-2">
                <span>🔄</span> Sınav Karşılaştırma Analizi
            </h3>
            <p class="text-xs text-gray-500 mb-4">Kıyaslamak istediğiniz iki deneme sınavını seçin:</p>
            
            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">1. Sınav (Eski):</label>
                    <select wire:model.live="compareExamA" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Seçin...</option>
                        @foreach($uniqueExamsList as $ex)
                            <option value="{{ $ex['key'] }}">{{ $ex['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">2. Sınav (Yeni):</label>
                    <select wire:model.live="compareExamB" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Seçin...</option>
                        @foreach($uniqueExamsList as $ex)
                            <option value="{{ $ex['key'] }}">{{ $ex['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if($comparison)
                <div class="border rounded-lg overflow-hidden bg-gray-50">
                    <div class="px-3 py-2.5 bg-gray-100 border-b text-xs font-bold text-gray-800 flex justify-between">
                        <span>Ders / Sınav Karşılaştırma</span>
                        <span class="text-gray-600">Net Skorlar</span>
                    </div>
                    <table class="w-full text-xs bg-white">
                        <thead>
                            <tr class="border-b bg-gray-50 text-gray-600">
                                <th class="p-2 text-left font-semibold">Ders</th>
                                <th class="p-2 text-center font-semibold">1. Sınav</th>
                                <th class="p-2 text-center font-semibold">2. Sınav</th>
                                <th class="p-2 text-center font-semibold">Fark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($comparison['courses'] as $cId => $cData)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-2.5 font-medium text-gray-700">{{ $cData['name'] }}</td>
                                    <td class="p-2.5 text-center text-gray-600">{{ number_format($cData['net_A'], 2) }}</td>
                                    <td class="p-2.5 text-center text-gray-600">{{ number_format($cData['net_B'], 2) }}</td>
                                    <td class="p-2.5 text-center font-bold {{ $cData['net_diff'] > 0 ? 'text-green-600' : ($cData['net_diff'] < 0 ? 'text-red-600' : 'text-gray-500') }}">
                                        {{ $cData['net_diff'] > 0 ? '+' : '' }}{{ number_format($cData['net_diff'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-100 font-bold border-t border-gray-300">
                                <td class="p-2.5 text-gray-800">TOPLAM NET</td>
                                <td class="p-2.5 text-center text-gray-800">{{ number_format($comparison['total_net_A'], 2) }}</td>
                                <td class="p-2.5 text-center text-gray-800">{{ number_format($comparison['total_net_B'], 2) }}</td>
                                <td class="p-2.5 text-center {{ $comparison['total_net_diff'] > 0 ? 'text-green-600' : ($comparison['total_net_diff'] < 0 ? 'text-red-600' : 'text-gray-500') }}">
                                    {{ $comparison['total_net_diff'] > 0 ? '+' : '' }}{{ number_format($comparison['total_net_diff'], 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="border border-dashed rounded-lg p-10 text-center text-gray-400 text-sm">
                    Kıyaslamak için yukarıdan iki farklı sınav seçin.
                </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModal">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-lg bg-white" wire:click.stop>
                <div class="flex items-center justify-between pb-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">Deneme Sonucu Ekle (Çoklu Ders)</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="mt-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deneme Adı *</label>
                            <input type="text" wire:model="exam_name" class="input-field" placeholder="Örn: Özdebir TYT 1">
                            @error('exam_name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tarih *</label>
                            <input type="date" wire:model="exam_date" class="input-field">
                            @error('exam_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alan/Branş *</label>
                            <select wire:model.live="field_id" class="input-field">
                                <option value="">Alan Seçin</option>
                                @foreach($fields as $field)
                                    <option value="{{ $field->id }}">{{ $field->name }}</option>
                                @endforeach
                            </select>
                            @error('field_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sınav Tipi</label>
                            <select wire:model="exam_type" class="input-field">
                                <option value="">Tür Seçin</option>
                                @foreach($examTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('exam_type') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if($field_id && count($filteredCourses) > 0)
                        <div class="mt-6">
                            <h4 class="text-md font-semibold text-gray-900 mb-3 pb-2 border-b">Ders Sınav Sonuçları</h4>
                            <div class="overflow-x-auto border rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ders Adı</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Doğru</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Yanlış</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Boş</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Net</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @foreach($filteredCourses as $course)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                    {{ $course->name }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <input type="number" 
                                                           wire:model.live.debounce.300ms="courseResults.{{ $course->id }}.correct" 
                                                           class="input-field text-center py-1 px-2 text-sm w-20 mx-auto block" 
                                                           placeholder="0" 
                                                           min="0">
                                                    @error("courseResults.{$course->id}.correct") 
                                                        <span class="text-xs text-red-600 block mt-1 text-center">{{ $message }}</span> 
                                                    @enderror
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <input type="number" 
                                                           wire:model.live.debounce.300ms="courseResults.{{ $course->id }}.wrong" 
                                                           class="input-field text-center py-1 px-2 text-sm w-20 mx-auto block" 
                                                           placeholder="0" 
                                                           min="0">
                                                    @error("courseResults.{$course->id}.wrong") 
                                                        <span class="text-xs text-red-600 block mt-1 text-center">{{ $message }}</span> 
                                                    @enderror
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <input type="number" 
                                                           wire:model.live.debounce.300ms="courseResults.{{ $course->id }}.blank" 
                                                           class="input-field text-center py-1 px-2 text-sm w-20 mx-auto block" 
                                                           placeholder="0" 
                                                           min="0">
                                                    @error("courseResults.{$course->id}.blank") 
                                                        <span class="text-xs text-red-600 block mt-1 text-center">{{ $message }}</span> 
                                                    @enderror
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                                    <span class="text-sm font-bold {{ ($courseResults[$course->id]['net'] ?? 0) >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                                        {{ number_format($courseResults[$course->id]['net'] ?? 0, 2) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">Toplam Hesaplanan Net</td>
                                            <td colspan="3"></td>
                                            <td class="px-4 py-3 text-center whitespace-nowrap text-base font-bold text-blue-600">
                                                @php
                                                    $totalNet = 0;
                                                    foreach($courseResults as $cRes) {
                                                        $totalNet += (float)($cRes['net'] ?? 0);
                                                    }
                                                @endphp
                                                {{ number_format($totalNet, 2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center text-gray-500 mt-6">
                            Lütfen derslerin listelenmesi için bir Alan/Branş seçin.
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notlar</label>
                        <textarea wire:model="notes" class="input-field" rows="2" placeholder="İsteğe bağlı notlar..."></textarea>
                        @error('notes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                        <button type="button" wire:click="closeModal" class="btn-secondary">İptal</button>
                        <button type="submit" class="btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Scripts for Chart.js progress representation -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            initProgressChart();
        });

        document.addEventListener('DOMContentLoaded', () => {
            initProgressChart();
        });

        document.addEventListener('livewire:update', () => {
            initProgressChart();
        });

        function initProgressChart() {
            const ctx = document.getElementById('progressChart');
            if (!ctx) return;
            
            if (window.myProgressChart) {
                window.myProgressChart.destroy();
            }

            const labels = @json($chartLabels);
            const data = @json($chartNets);

            window.myProgressChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Toplam Net Skoru',
                        data: data,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.08)',
                        borderWidth: 3,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    weight: 'bold'
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 10
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            padding: 10,
                            backgroundColor: '#0f172a',
                            titleFont: {
                                size: 12,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 12
                            }
                        }
                    }
                }
            });
        }
    </script>
</div>
