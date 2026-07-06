<div>
    <!-- İstatistikler -->
    <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Atanan Derslerim</h2>
                <p class="text-sm text-gray-600 mt-1">Koçunuz tarafından size atanan tüm dersler ve konular</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-blue-600">{{ $completionPercentage }}%</div>
                <div class="text-sm text-gray-600">Tamamlama</div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mt-4 pt-4 border-t border-blue-200">
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

    <!-- Atanan Dersler -->
    @if($assignments->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <p class="text-gray-600 text-lg">Henüz size ders atanmamış</p>
            <p class="text-gray-500 mt-2">Koçunuz size ders atadığında burada görebileceksiniz</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($assignments as $fieldName => $fieldAssignments)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <!-- Alan Başlığı -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 border-b border-gray-200">
                        <button 
                            wire:click="toggleField('{{ $fieldName }}')"
                            class="w-full flex items-center justify-between hover:opacity-80 transition"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 transition-transform {{ in_array($fieldName, $expandedFields) ? 'rotate-90' : '' }}" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
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
                    </div>

                    <!-- Dersler -->
                    @if(in_array($fieldName, $expandedFields))
                        <div class="p-4">
                            @php
                                $groupedByCourse = $fieldAssignments->groupBy('course.name');
                            @endphp

                            @foreach($groupedByCourse as $courseName => $courseAssignments)
                                <div class="mb-4 border-l-4 border-blue-400 pl-4">
                                    <!-- Ders Başlığı -->
                                    <button 
                                        wire:click="toggleCourse({{ $courseAssignments->first()->course->id }})"
                                        class="w-full flex items-center justify-between hover:opacity-80 transition mb-2"
                                    >
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 transition-transform {{ in_array($courseAssignments->first()->course->id, $expandedCourses) ? 'rotate-90' : '' }}" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                            </svg>
                                            <span class="font-medium text-gray-800">{{ $courseName }}</span>
                                            @php
                                                $courseTotal = $courseAssignments->count();
                                                $courseCompleted = $courseAssignments->filter(fn($a) => $a->progress && $a->progress->is_completed)->count();
                                            @endphp
                                            <span class="text-sm text-gray-500">
                                                ({{ $courseCompleted }}/{{ $courseTotal }})
                                            </span>
                                        </div>
                                    </button>

                                    <!-- Konular -->
                                    @if(in_array($courseAssignments->first()->course->id, $expandedCourses))
                                        <div class="pl-6 space-y-2">
                                            @php
                                                $groupedByTopic = $courseAssignments->groupBy('topic.name');
                                            @endphp

                                            @foreach($groupedByTopic as $topicName => $topicAssignments)
                                                <div class="bg-gray-50 rounded-lg overflow-hidden">
                                                    <button 
                                                        wire:click="toggleTopic({{ $topicAssignments->first()->topic->id }})"
                                                        class="w-full flex items-center justify-between p-3 hover:bg-gray-100 transition"
                                                    >
                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-3 h-3 transition-transform {{ in_array($topicAssignments->first()->topic->id, $expandedTopics) ? 'rotate-90' : '' }}" 
                                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                            </svg>
                                                            <span class="font-medium text-gray-700">{{ $topicName }}</span>
                                                            @php
                                                                $topicCompleted = $topicAssignments->filter(fn($a) => $a->progress && $a->progress->is_completed)->count();
                                                                $topicTotal = $topicAssignments->count();
                                                            @endphp
                                                            <span class="text-xs text-gray-500">
                                                                ({{ $topicCompleted }}/{{ $topicTotal }})
                                                            </span>
                                                        </div>
                                                        @if($topicCompleted === $topicTotal)
                                                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @endif
                                                    </button>

                                                    <!-- Alt Konular -->
                                                    @if(in_array($topicAssignments->first()->topic->id, $expandedTopics))
                                                        <div class="px-6 pb-3 space-y-1">
                                                            @foreach($topicAssignments as $assignment)
                                                                <div class="flex items-center justify-between p-2 hover:bg-white rounded transition">
                                                                    <div class="flex items-center gap-2">
                                                                        @if($assignment->progress && $assignment->progress->is_completed)
                                                                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                            </svg>
                                                                        @else
                                                                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                            </svg>
                                                                        @endif
                                                                        <span class="text-sm {{ $assignment->progress && $assignment->progress->is_completed ? 'text-gray-400 line-through' : 'text-gray-700' }}">
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

