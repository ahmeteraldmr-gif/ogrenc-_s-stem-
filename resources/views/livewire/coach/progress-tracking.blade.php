<div>
    <div class="mb-6">
        <a href="{{ route('coach.students') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            {{ __('messages.back_to_students') }}
        </a>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Öğrenci Bilgisi ve İstatistikler -->
    <div class="mb-6 bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $student->name }}
                </h2>
                <p class="text-gray-600">{{ $student->email }}</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-blue-600">{{ $completionPercentage }}%</div>
                <div class="text-sm text-gray-600">Tamamlama Oranı</div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-200">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-800">{{ $totalAssignments }}</div>
                <div class="text-sm text-gray-600">Toplam Konu</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $completedCount }}</div>
                <div class="text-sm text-gray-600">Tamamlanan</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $totalAssignments - $completedCount }}</div>
                <div class="text-sm text-gray-600">Devam Eden</div>
            </div>
        </div>

        <!-- İlerleme Çubuğu -->
        <div class="mt-4">
            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-500" 
                     style="width: {{ $completionPercentage }}%"></div>
            </div>
        </div>
    </div>

    <!-- Filtre -->
    @if($courses->count() > 0)
        <div class="mb-6 bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center gap-4">
                <label class="text-gray-700 font-medium">Ders Filtrele:</label>
                <select wire:model.live="selectedCourseId" class="flex-1 border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Tüm Dersler</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    <!-- Konular ve İlerleme -->
    @if($assignments->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-gray-600 text-lg">Henüz konu atanmamış</p>
            <p class="text-gray-500 mt-2">Bu öğrenciye konu atamak için 
                <a href="{{ route('coach.assign', $studentId) }}" class="text-blue-600 hover:text-blue-800 underline">
                    buraya tıklayın
                </a>
            </p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($assignments as $fieldName => $fieldAssignments)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <!-- Alan Başlığı -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $fieldName }}</h3>
                        @php
                            $fieldTotal = $fieldAssignments->count();
                            $fieldCompleted = $fieldAssignments->filter(fn($a) => $a->progress && $a->progress->is_completed)->count();
                            $fieldPercentage = $fieldTotal > 0 ? round(($fieldCompleted / $fieldTotal) * 100, 1) : 0;
                        @endphp
                        <div class="flex items-center gap-3 mt-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $fieldPercentage }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600 font-medium">{{ $fieldPercentage }}%</span>
                        </div>
                    </div>

                    <div class="p-4">
                        @php
                            $groupedByCourse = $fieldAssignments->groupBy('course.name');
                        @endphp

                        @foreach($groupedByCourse as $courseName => $courseAssignments)
                            <div class="mb-4 border-l-4 border-blue-400 pl-4">
                                <!-- Ders Başlığı -->
                                <div class="mb-3">
                                    <h4 class="font-semibold text-gray-800 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                        </svg>
                                        {{ $courseName }}
                                    </h4>
                                    @php
                                        $courseTotal = $courseAssignments->count();
                                        $courseCompleted = $courseAssignments->filter(fn($a) => $a->progress && $a->progress->is_completed)->count();
                                        $coursePercentage = $courseTotal > 0 ? round(($courseCompleted / $courseTotal) * 100, 1) : 0;
                                    @endphp
                                    <div class="flex items-center gap-2 mt-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $coursePercentage }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $courseCompleted }}/{{ $courseTotal }}</span>
                                    </div>
                                </div>

                                @php
                                    $groupedByTopic = $courseAssignments->groupBy('topic.name');
                                @endphp

                                @foreach($groupedByTopic as $topicName => $topicAssignments)
                                    <div class="mb-3 bg-gray-50 rounded-lg overflow-hidden">
                                        <!-- Konu Başlığı -->
                                        <button 
                                            wire:click="toggleTopic({{ $topicAssignments->first()->topic->id }})"
                                            class="w-full flex items-center justify-between p-3 hover:bg-gray-100 transition"
                                        >
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 transition-transform {{ in_array($topicAssignments->first()->topic->id, $expandedTopics) ? 'rotate-90' : '' }}" 
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                                <span class="font-medium text-gray-700">{{ $topicName }}</span>
                                                @php
                                                    $topicCompleted = $topicAssignments->filter(fn($a) => $a->progress && $a->progress->is_completed)->count();
                                                    $topicTotal = $topicAssignments->count();
                                                @endphp
                                                <span class="text-sm text-gray-500">({{ $topicCompleted }}/{{ $topicTotal }})</span>
                                            </div>
                                            @if($topicCompleted === $topicTotal)
                                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                        </button>

                                        <!-- Alt Konular -->
                                        @if(in_array($topicAssignments->first()->topic->id, $expandedTopics))
                                            <div class="px-3 pb-3 space-y-1">
                                                @foreach($topicAssignments as $assignment)
                                                    <div class="flex items-center justify-between p-2 hover:bg-white rounded transition">
                                                        <div class="flex items-center gap-2">
                                                            <input 
                                                                type="checkbox" 
                                                                wire:click="toggleProgress({{ $assignment->id }})"
                                                                {{ $assignment->progress && $assignment->progress->is_completed ? 'checked' : '' }}
                                                                class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer"
                                                            >
                                                            <span class="text-gray-700 {{ $assignment->progress && $assignment->progress->is_completed ? 'line-through text-gray-400' : '' }}">
                                                                {{ $assignment->subTopic->name }}
                                                            </span>
                                                        </div>
                                                        @if($assignment->progress && $assignment->progress->completed_at)
                                                            <span class="text-xs text-gray-400">
                                                                {{ $assignment->progress->completed_at->format('d.m.Y') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
