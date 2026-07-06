<div>
    @if (session()->has('message'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if(!$schedule)
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-600 text-lg">Henüz bir program atanmadı</p>
            <p class="text-gray-500 mt-2">Koçunuz size bir program atadığında burada görünecek</p>
        </div>
    @else
        <!-- Program Geçerlilik Tarihi Bilgisi -->
        @if($scheduleStartDate && $scheduleEndDate)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-blue-900">Program Geçerlilik Tarihi</p>
                        <p class="text-sm text-blue-700">
                            {{ \Carbon\Carbon::parse($scheduleStartDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($scheduleEndDate)->format('d F Y') }}
                        </p>
                        @if(!$isWithinScheduleDates)
                            <p class="text-xs text-orange-600 mt-1">⚠️ Bu program şu anda geçerli değil</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Hafta Seçici -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <button 
                    wire:click="previousWeek"
                    class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <div class="text-center">
                    <h2 class="text-lg font-semibold text-gray-900">
                        {{ $weekStart->format('d.m.Y') }} - {{ $weekEnd->format('d.m.Y') }}
                    </h2>
                    @if($isCurrentWeek)
                        <p class="text-sm text-blue-600 font-medium">Bu Hafta</p>
                    @endif
                </div>

                <button 
                    wire:click="nextWeek"
                    class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            @if(!$isCurrentWeek)
                <div class="mt-4 text-center">
                    <button 
                        wire:click="thisWeek"
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                        Bu Haftaya Dön
                    </button>
                </div>
            @endif

            <!-- İlerleme İstatistikleri -->
            <div class="mt-6">
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <div class="text-2xl font-bold text-gray-900">{{ $totalItems }}</div>
                        <div class="text-sm text-gray-600">Toplam Görev</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="text-2xl font-bold text-green-700">{{ $completedItems }}</div>
                        <div class="text-sm text-green-600">Tamamlanan</div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                        <div class="text-2xl font-bold text-yellow-700">{{ $inProgressItems }}</div>
                        <div class="text-sm text-yellow-600">Devam Eden</div>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                        <div class="text-2xl font-bold text-red-700">{{ $notStartedItems }}</div>
                        <div class="text-sm text-red-600">Başlanmamış</div>
                    </div>
                </div>

                <!-- Multi-color Progress Bar -->
                <div class="w-full h-4 bg-gray-200 rounded-full overflow-hidden flex">
                    @if($completionPercentage > 0)
                        <div class="bg-green-500 h-full transition-all" style="width: {{ $completionPercentage }}%" title="Tamamlanan: {{ $completionPercentage }}%"></div>
                    @endif
                    @if($inProgressPercentage > 0)
                        <div class="bg-yellow-500 h-full transition-all" style="width: {{ $inProgressPercentage }}%" title="Devam Eden: {{ $inProgressPercentage }}%"></div>
                    @endif
                    @if($notStartedPercentage > 0)
                        <div class="bg-red-300 h-full transition-all" style="width: {{ $notStartedPercentage }}%" title="Başlanmamış: {{ $notStartedPercentage }}%"></div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Günlük Görevler -->
        <div class="space-y-4">
            @php
                $days = [
                    1 => 'Pazartesi',
                    2 => 'Salı',
                    3 => 'Çarşamba',
                    4 => 'Perşembe',
                    5 => 'Cuma',
                    6 => 'Cumartesi',
                    7 => 'Pazar',
                ];
            @endphp

            @foreach($days as $dayNum => $dayName)
                @php
                    $dayItems = $itemsByDay->get($dayNum, collect());
                    $dayDate = $weekStart->copy()->addDays($dayNum - 1);
                    $isToday = $dayDate->isToday();
                @endphp

                <div class="bg-white rounded-lg shadow-sm overflow-hidden {{ $isToday ? 'ring-2 ring-blue-500' : '' }}">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $dayName }}</h3>
                            <p class="text-sm text-gray-600">{{ $dayDate->format('d.m.Y') }}</p>
                            @if($isToday)
                                <span class="inline-block mt-1 px-2 py-0.5 bg-blue-600 text-white text-xs font-medium rounded-full">
                                    Bugün
                                </span>
                            @endif
                        </div>
                        @if($dayItems->count() > 0)
                            @php
                                $dayCompleted = $dayItems->filter(function($item) use ($progress) {
                                    return isset($progress[$item->id]) && $progress[$item->id]->status === 'completed';
                                })->count();
                            @endphp
                            <span class="text-sm font-medium text-gray-700">
                                {{ $dayCompleted }}/{{ $dayItems->count() }} tamamlandı
                            </span>
                        @endif
                    </div>

                    <div class="p-4">
                        @if($dayItems->isEmpty())
                            <p class="text-center text-gray-400 py-4 text-sm">Bu gün için görev yok</p>
                        @else
                            <div class="space-y-3">
                                @foreach($dayItems as $item)
                                    @php
                                        $itemProgress = $progress->get($item->id);
                                        $status = $itemProgress ? $itemProgress->status : 'not_started';
                                        $bgColor = match($status) {
                                            'completed' => 'bg-green-50 border-green-200',
                                            'in_progress' => 'bg-yellow-50 border-yellow-200',
                                            default => 'bg-white'
                                        };
                                    @endphp

                                    <div class="border border-gray-200 rounded-lg p-4 {{ $bgColor }}">
                                        <div class="flex items-start gap-3">
                                            <!-- Status Selector -->
                                            <select 
                                                wire:change="updateStatus({{ $item->id }}, $event.target.value)"
                                                class="mt-1 text-sm border border-gray-300 rounded-lg px-2 py-1 focus:ring-2 focus:ring-blue-500"
                                            >
                                                <option value="not_started" {{ $status === 'not_started' ? 'selected' : '' }}>⚪ Başlanmadı</option>
                                                <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>🟡 Devam Ediyor</option>
                                                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>🟢 Tamamlandı</option>
                                            </select>
                                            
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    @if($item->time_slot)
                                                        <span class="text-sm font-semibold text-blue-600">{{ $item->time_slot }}</span>
                                                    @endif
                                                    @if($item->course)
                                                        <span class="text-sm font-semibold text-gray-800 {{ $status === 'completed' ? 'line-through' : '' }}">
                                                            {{ $item->course->name }}
                                                        </span>
                                                    @endif
                                                </div>

                                                @if($item->topic)
                                                    <div class="text-sm text-gray-700 {{ $status === 'completed' ? 'line-through' : '' }}">
                                                        📖 {{ $item->topic->name }}
                                                    </div>
                                                @endif

                                                @if($item->subTopic)
                                                    <div class="text-xs text-gray-600 {{ $status === 'completed' ? 'line-through' : '' }}">
                                                        • {{ $item->subTopic->name }}
                                                    </div>
                                                @endif

                                                @if($item->question_count > 0)
                                                    <div class="text-sm text-gray-600 mt-1 {{ $status === 'completed' ? 'line-through' : '' }}">
                                                        🎯 Hedef: {{ $item->question_count }} soru
                                                    </div>
                                                @endif

                                                @if($item->description)
                                                    <div class="text-sm text-gray-600 mt-1 italic {{ $status === 'completed' ? 'line-through' : '' }}">
                                                        {{ $item->description }}
                                                    </div>
                                                @endif

                                                <!-- Öğrenci Notu -->
                                                @if($editingNoteFor === $item->id)
                                                    <div class="mt-3">
                                                        <textarea 
                                                            wire:model="noteText"
                                                            rows="3"
                                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                            placeholder="Notunuzu buraya yazın..."
                                                        ></textarea>
                                                        <div class="flex gap-2 mt-2">
                                                            <button 
                                                                wire:click="saveNote({{ $item->id }})"
                                                                class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                                                Kaydet
                                                            </button>
                                                            <button 
                                                                wire:click="cancelNote"
                                                                class="px-3 py-1 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-gray-300">
                                                                İptal
                                                            </button>
                                                        </div>
                                                    </div>
                                                @else
                                                    @if($itemProgress && $itemProgress->student_notes)
                                                        <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-sm">
                                                            <div class="flex items-start justify-between gap-2">
                                                                <div class="flex-1">
                                                                    <div class="font-medium text-yellow-800 mb-1">📝 Notum:</div>
                                                                    <div class="text-gray-700">{{ $itemProgress->student_notes }}</div>
                                                                </div>
                                                                <button 
                                                                    wire:click="startEditingNote({{ $item->id }})"
                                                                    class="text-blue-600 hover:text-blue-800 text-xs">
                                                                    Düzenle
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <button 
                                                        wire:click="startEditingNote({{ $item->id }})"
                                                        class="mt-2 text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        {{ $itemProgress && $itemProgress->student_notes ? 'Notu Düzenle' : 'Not Ekle' }}
                                                    </button>
                                                @endif

                                                @if($status === 'completed' && $itemProgress && $itemProgress->completed_at)
                                                    <div class="text-xs text-green-600 mt-2">
                                                        ✓ {{ $itemProgress->completed_at->format('d.m.Y H:i') }} tarihinde tamamlandı
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>


