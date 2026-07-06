<div>
    <div class="mb-6">
        <a href="{{ route('coach.students') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Öğrencilere Dön
        </a>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Öğrenci Profil Kartı -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 h-20 w-20">
                    <div
                        class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        {{ substr($student->name, 0, 1) }}
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $student->name }}</h1>
                    <div class="space-y-1 text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $student->email }}
                        </div>
                        @if($student->phone)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $student->phone }}
                            </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Kayıt: {{ $student->created_at->format('d.m.Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('coach.assign', $student->id) }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Ders Ata
                </a>
            </div>
        </div>
    </div>

    <!-- İstatistik Kartları -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <!-- Konu İlerlemesi -->
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm text-gray-600">Konu İlerlemesi</div>
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $completionPercentage }}%</div>
            <div class="text-xs text-gray-500 mt-1">{{ $completedCount }}/{{ $totalAssignments }} konu</div>
            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $completionPercentage }}%">
                </div>
            </div>
        </div>

        <!-- Çözülen Sorular -->
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm text-gray-600">Toplam Soru</div>
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ number_format($totalQuestions) }}</div>
            <div class="text-xs text-gray-500 mt-1">{{ number_format($correctAnswers) }} doğru</div>
        </div>

        <!-- Deneme Sayısı -->
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm text-gray-600">Deneme Sayısı</div>
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $totalExams }}</div>
            <div class="text-xs text-gray-500 mt-1">Girilen deneme</div>
        </div>

        <!-- Ortalama Net -->
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm text-gray-600">Ortalama Net</div>
                <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $avgNet ? number_format($avgNet, 1) : '0' }}</div>
            <div class="text-xs text-gray-500 mt-1">Net puan</div>
        </div>
    </div>

    <!-- Tab Navigasyonu -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button wire:click="$set('activeTab', 'progress')"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'progress' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Konu Takibi
            </button>
            <button wire:click="$set('activeTab', 'analysis')"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'analysis' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Akıllı Başarı Analizi
            </button>
        </nav>
    </div>

    @if($activeTab === 'progress')
        <!-- Konu Takibi Sekmesi -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Konu Takibi ve İlerleme</h2>

            @if($assignments->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <p class="text-gray-600 text-lg mb-2">Henüz konu atanmamış</p>
                    <p class="text-gray-500 mb-4">Bu öğrenciye konu atamak için "Ders Ata" butonuna tıklayın</p>
                    <a href="{{ route('coach.assign', $student->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Ders Ata
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($assignments as $fieldName => $fieldAssignments)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <!-- Alan Başlığı -->
                            <button wire:click="toggleField('{{ $fieldName }}')"
                                class="w-full bg-gradient-to-r from-blue-50 to-indigo-50 p-4 flex items-center justify-between hover:from-blue-100 hover:to-indigo-100 transition">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 transition-transform {{ in_array($fieldName, $expandedFields) ? 'rotate-90' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $fieldName }}</h3>
                                    @php
                                        $fieldTotal = $fieldAssignments->count();
                                        $fieldCompleted = $fieldAssignments->filter(fn($a) => $a->progress && $a->progress->is_completed)->count();
                                        $fieldPercentage = $fieldTotal > 0 ? round(($fieldCompleted / $fieldTotal) * 100, 1) : 0;
                                    @endphp
                                    <span class="text-sm text-gray-600">
                                        {{ $fieldCompleted }}/{{ $fieldTotal }} tamamlandı
                                    </span>
                                </div>
                                <span class="text-sm font-medium text-blue-600">{{ $fieldPercentage }}%</span>
                            </button>

                            <!-- Dersler -->
                            @if(in_array($fieldName, $expandedFields))
                                <div class="p-4 bg-gray-50">
                                    @php
                                        $groupedByCourse = $fieldAssignments->groupBy('course.name');
                                    @endphp

                                    @foreach($groupedByCourse as $courseName => $courseAssignments)
                                        <div class="mb-4 bg-white rounded-lg border border-gray-200 overflow-hidden">
                                            <!-- Ders Başlığı -->
                                            <button wire:click="toggleCourse({{ $courseAssignments->first()->course->id }})"
                                                class="w-full p-3 flex items-center justify-between hover:bg-gray-50 transition">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 transition-transform {{ in_array($courseAssignments->first()->course->id, $expandedCourses) ? 'rotate-90' : '' }}"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                                    </svg>
                                                    <span class="font-medium text-gray-800">{{ $courseName }}</span>
                                                    @php
                                                        $courseTotal = $courseAssignments->count();
                                                        $courseCompleted = $courseAssignments->filter(fn($a) => $a->progress && $a->progress->is_completed)->count();
                                                    @endphp
                                                    <span class="text-sm text-gray-500">({{ $courseCompleted }}/{{ $courseTotal }})</span>
                                                </div>
                                            </button>

                                            <!-- Konular -->
                                            @if(in_array($courseAssignments->first()->course->id, $expandedCourses))
                                                <div class="px-4 pb-4 space-y-2">
                                                    @php
                                                        $groupedByTopic = $courseAssignments->groupBy('topic.name');
                                                    @endphp

                                                    @foreach($groupedByTopic as $topicName => $topicAssignments)
                                                        <div class="bg-gray-50 rounded-lg overflow-hidden border border-gray-200">
                                                            <button wire:click="toggleTopic({{ $topicAssignments->first()->topic->id }})"
                                                                class="w-full p-3 flex items-center justify-between hover:bg-gray-100 transition">
                                                                <div class="flex items-center gap-2">
                                                                    <svg class="w-3 h-3 transition-transform {{ in_array($topicAssignments->first()->topic->id, $expandedTopics) ? 'rotate-90' : '' }}"
                                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M9 5l7 7-7 7" />
                                                                    </svg>
                                                                    <span class="font-medium text-gray-700">📖 {{ $topicName }}</span>
                                                                    @php
                                                                        $topicCompleted = $topicAssignments->filter(fn($a) => $a->progress && $a->progress->is_completed)->count();
                                                                        $topicTotal = $topicAssignments->count();
                                                                    @endphp
                                                                    <span
                                                                        class="text-xs text-gray-500">({{ $topicCompleted }}/{{ $topicTotal }})</span>
                                                                </div>
                                                                @if($topicCompleted === $topicTotal)
                                                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                @endif
                                                            </button>

                                                            <!-- Alt Konular -->
                                                            @if(in_array($topicAssignments->first()->topic->id, $expandedTopics))
                                                                <div class="px-4 pb-4 space-y-2">
                                                                    @foreach($topicAssignments as $assignment)
                                                                        <div class="bg-white rounded-lg border border-gray-200 p-3">
                                                                            <div class="flex items-start justify-between gap-2">
                                                                                <div class="flex items-start gap-2 flex-1">
                                                                                    <input type="checkbox"
                                                                                        wire:click="toggleProgress({{ $assignment->id }})" {{ $assignment->progress && $assignment->progress->is_completed ? 'checked' : '' }}
                                                                                        class="mt-1 w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                                                                    <div class="flex-1">
                                                                                        <span
                                                                                            class="font-medium text-gray-700 {{ $assignment->progress && $assignment->progress->is_completed ? 'line-through text-gray-400' : '' }}">
                                                                                            {{ $assignment->subTopic->name }}
                                                                                        </span>
                                                                                        @if($assignment->progress && $assignment->progress->completed_at)
                                                                                            <span class="text-xs text-gray-400 ml-2">
                                                                                                ✓ {{ $assignment->progress->completed_at->format('d.m.Y') }}
                                                                                            </span>
                                                                                        @endif

                                                                                        <!-- Not Görüntüleme/Düzenleme -->
                                                                                        @if($editingNoteFor === $assignment->id)
                                                                                            <div class="mt-2">
                                                                                                <textarea wire:model="noteText" rows="3"
                                                                                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                                                                    placeholder="Konu hakkında not ekleyin..."></textarea>
                                                                                                <div class="flex gap-2 mt-2">
                                                                                                    <button wire:click="saveNote({{ $assignment->id }})"
                                                                                                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                                                                                        Kaydet
                                                                                                    </button>
                                                                                                    <button wire:click="cancelNote"
                                                                                                        class="px-3 py-1 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-gray-300">
                                                                                                        İptal
                                                                                                    </button>
                                                                                                </div>
                                                                                            </div>
                                                                                        @else
                                                                                            @if($assignment->progress && $assignment->progress->notes)
                                                                                                <div
                                                                                                    class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-sm text-gray-700">
                                                                                                    <div class="flex items-start justify-between gap-2">
                                                                                                        <div class="flex-1">
                                                                                                            <div class="font-medium text-yellow-800 mb-1">📝 Not:
                                                                                                            </div>
                                                                                                            {{ $assignment->progress->notes }}
                                                                                                        </div>
                                                                                                        <button wire:click="startEditingNote({{ $assignment->id }})"
                                                                                                            class="text-blue-600 hover:text-blue-800 text-xs">
                                                                                                            Düzenle
                                                                                                        </button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endif
                                                                                    </div>
                                                                                </div>

                                                                                @if(!$editingNoteFor || $editingNoteFor !== $assignment->id)
                                                                                    <button wire:click="startEditingNote({{ $assignment->id }})"
                                                                                        class="text-gray-400 hover:text-blue-600 transition" title="Not ekle">
                                                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                                            viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                stroke-width="2"
                                                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                                        </svg>
                                                                                    </button>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        <!-- Analiz Sekmesi -->
        <div class="space-y-6">
            <!-- Üst Filtreleme Barı -->
            <div
                class="bg-white border border-gray-100 rounded-lg p-4 shadow-sm flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span class="text-sm font-bold text-gray-700 uppercase tracking-tight">Analiz Filtreleri:</span>
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Sınav Alanı:</label>
                        <select wire:model.live="analysisFieldId"
                            class="text-sm border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 py-1.5 pr-10">
                            <option value="">Tüm Alanlar</option>
                            @foreach($analysisFields as $field)
                                <option value="{{ $field->id }}">{{ $field->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Ders Seçimi:</label>
                        <select wire:model.live="analysisCourseId"
                            class="text-sm border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 py-1.5 pr-10"
                            {{ empty($analysisFieldId) ? 'disabled' : '' }}>
                            <option value="">Tüm Dersler</option>
                            @foreach($analysisCourses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if($analysisFieldId || $analysisCourseId)
                        <button wire:click="$set('analysisFieldId', ''); $set('analysisCourseId', '');"
                            class="text-xs font-bold text-red-600 hover:text-red-700 px-2 py-1 bg-red-50 rounded-md">
                            Filtreleri Temizle
                        </button>
                    @endif

                    <a href="{{ route('coach.student.exam-report.pdf', ['student' => $studentId]) }}" class="btn-secondary flex items-center gap-1.5" style="text-decoration: none; display: inline-flex; align-items: center; white-space: nowrap; height: 32px; padding: 4px 12px; font-size: 13px; font-weight: bold; background-color: #f1f5f9;" target="_blank">
                        <span>📄 PDF Raporu İndir</span>
                    </a>
                </div>
            </div>

            <!-- Genel Bakış ve Veri Kaynağı Açıklaması -->
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h4 class="text-sm font-bold text-blue-900 leading-none mb-1">Analiz Veri Kaynağı Bilgilendirmesi</h4>
                    <p class="text-sm text-blue-800">
                        Aşağıdaki veriler öğrencinin sistem üzerinden girdiği **çalışma kayıtlarından (çözülen soru sayıları
                        ve doğruluk oranları)** üretilmektedir.
                        Nokta atışı müdahale için en güncel ve gerçekçi çalışma verileri baz alınmıştır.
                    </p>
                </div>
            </div>

            <!-- Alan Bazlı Deneme Performansı (ÖS) -->
            @if($examStats->count() > 0)
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Alan Bazlı Deneme Ortalamaları
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($examStats as $stat)
                            <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                                <div class="text-xs font-bold text-purple-600 uppercase tracking-wider mb-1">
                                    {{ $stat->field->name }}</div>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-black text-purple-900">{{ number_format($stat->avg_net, 1) }}</span>
                                    <span class="text-xs text-purple-600">Net Ort.</span>
                                </div>
                                <div class="text-[10px] text-purple-500 mt-1">{{ $stat->exam_count }} deneme verisi</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Kritik Konular Özeti -->
            @if($analysis['critical']->count() > 0)
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-red-700 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Öncelikli Müdahale Gerektiren Konular
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($analysis['critical'] as $stat)
                            <div class="bg-white border-l-4 border-red-600 rounded shadow-sm p-4 hover:shadow-md transition">
                                <div class="flex items-center justify-between mb-1">
                                    <span
                                        class="text-[10px] font-bold text-blue-600 px-1.5 py-0.5 bg-blue-50 rounded uppercase">{{ $stat->field_name }}</span>
                                    <span class="text-[10px] font-semibold text-gray-400 uppercase">{{ $stat->course->name }}</span>
                                </div>
                                <div class="font-bold text-gray-900 mb-2">{{ $stat->topic->name }}</div>
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-black text-red-600">%{{ $stat->success_rate }}</span>
                                    <span class="text-xs text-gray-500">{{ $stat->total }} soru</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Tüm Konular Performans Haritası -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Tüm Konu Performansı ve Gelişim Haritası
                </h3>

                <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alan / Ders /
                                    Konu</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Toplam</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Doğru</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Başarı</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Gelişim</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($analysis['all'] as $stat)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2 mb-0.5">
                                            <span
                                                class="text-[9px] px-1 bg-blue-100 text-blue-700 font-bold rounded">{{ $stat->field_name }}</span>
                                            <span
                                                class="text-[10px] text-gray-500 font-semibold uppercase">{{ $stat->course->name }}</span>
                                        </div>
                                        <div class="text-sm font-bold text-gray-900">{{ $stat->topic->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-600 font-medium">{{ $stat->total }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-green-600 font-bold">{{ $stat->correct }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-3">
                                            <div class="w-16 bg-gray-100 rounded-full h-1.5 hidden sm:block">
                                                <div class="h-1.5 rounded-full {{ $stat->success_rate < 60 ? 'bg-red-500' : ($stat->success_rate < 80 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                                    style="width: {{ $stat->success_rate }}%"></div>
                                            </div>
                                            <span
                                                class="text-sm font-black {{ $stat->success_rate < 60 ? 'text-red-600' : ($stat->success_rate < 80 ? 'text-yellow-600' : 'text-green-600') }}">
                                                %{{ round($stat->success_rate) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($stat->trend === 'up')
                                            <div class="flex flex-col items-center text-green-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                </svg>
                                                <span class="text-[8px] font-bold uppercase">Yükseliyor</span>
                                            </div>
                                        @elseif($stat->trend === 'down')
                                            <div class="flex flex-col items-center text-red-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6" />
                                                </svg>
                                                <span class="text-[8px] font-bold uppercase">Düşüyor</span>
                                            </div>
                                        @elseif($stat->trend === 'stable')
                                            <span class="text-gray-400 text-[10px] font-bold uppercase">Dengeli</span>
                                        @else
                                            <span class="text-blue-400 text-[10px] italic font-medium">Yeni</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>