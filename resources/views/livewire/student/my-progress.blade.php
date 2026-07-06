<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">İlerleme Durumum</h2>
        <p class="text-sm text-gray-600 mt-1">Genel performansınızı ve son çalışmalarınızı görüntüleyin</p>
    </div>

    <!-- Overall Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="card bg-gradient-to-br from-blue-50 to-blue-100">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_questions'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Toplam Soru</div>
        </div>
        <div class="card bg-gradient-to-br from-green-50 to-green-100">
            <div class="text-3xl font-bold text-green-600">{{ $stats['total_correct'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Doğru Cevap</div>
        </div>
        <div class="card bg-gradient-to-br from-purple-50 to-purple-100">
            <div class="text-3xl font-bold text-purple-600">{{ $stats['total_exams'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Deneme</div>
        </div>
        <div class="card bg-gradient-to-br from-orange-50 to-orange-100">
            <div class="text-3xl font-bold text-orange-600">{{ number_format($stats['avg_net'], 2) }}</div>
            <div class="text-sm text-gray-600 mt-1">Ortalama Net</div>
        </div>
        <div class="card bg-gradient-to-br from-pink-50 to-pink-100">
            <div class="text-3xl font-bold text-pink-600">{{ $stats['study_days'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Çalışma Günü</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Questions -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Son Soru Kayıtları</h3>
            <div class="space-y-3">
                @forelse($recentQuestions as $log)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $log->course?->name ?? 'Genel' }}
                            </div>
                            <div class="text-xs text-gray-500">{{ $log->log_date->format('d.m.Y') }}</div>
                        </div>
                        <div class="flex items-center space-x-2 text-sm">
                            <span class="text-green-600">✓ {{ $log->correct_answers }}</span>
                            <span class="text-red-600">✗ {{ $log->wrong_answers }}</span>
                            <span class="text-gray-500">○ {{ $log->blank_answers }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 text-sm py-4">Henüz kayıt yok</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Exams -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Son Deneme Sonuçları</h3>
            <div class="space-y-3">
                @forelse($recentExams as $exam)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $exam->exam_name }}</div>
                            <div class="text-xs text-gray-500">{{ $exam->exam_date->format('d.m.Y') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-blue-600">{{ number_format($exam->net_score, 2) }}</div>
                            <div class="text-xs text-gray-500">Net</div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 text-sm py-4">Henüz deneme yok</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
