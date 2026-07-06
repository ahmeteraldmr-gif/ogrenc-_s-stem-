<div class="space-y-6">
    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Program Bilgileri -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Program Bilgileri</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $isTemplate ? 'Şablon Adı *' : 'Program Adı *' }}
                </label>
                <input type="text" wire:model="scheduleName"
                    placeholder="{{ $isTemplate ? 'Örn: Sayısal Öğrenci Şablonu' : 'Örn: Ahmet\'in Aralık Programı' }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('scheduleName') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            @if(!$isTemplate)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Öğrenci *</label>
                    <select wire:model="studentId"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Öğrenci Seçin</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                        @endforeach
                    </select>
                    @error('studentId') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            @endif
        </div>

        <!-- Program Tipi Seçimi -->
        <div class="mt-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
            <label class="block text-sm font-semibold text-gray-900 mb-3">Program Tipi Seçimi</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label
                    class="flex items-center gap-3 p-4 bg-white rounded-lg border-2 cursor-pointer transition-all {{ $scheduleType === 'timed' ? 'border-blue-500 shadow-md' : 'border-gray-200 hover:border-blue-300' }}">
                    <input type="radio" wire:model.live="scheduleType" value="timed"
                        class="w-5 h-5 text-blue-600 focus:ring-2 focus:ring-blue-500">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-gray-900 block">Saatli Program</span>
                            <span class="text-xs text-gray-600">Görevler belirli saat dilimlerine atanır</span>
                        </div>
                    </div>
                </label>

                <label
                    class="flex items-center gap-3 p-4 bg-white rounded-lg border-2 cursor-pointer transition-all {{ $scheduleType === 'daily' ? 'border-green-500 shadow-md' : 'border-gray-200 hover:border-green-300' }}">
                    <input type="radio" wire:model.live="scheduleType" value="daily"
                        class="w-5 h-5 text-green-600 focus:ring-2 focus:ring-green-500">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-gray-900 block">Saatsiz Program</span>
                            <span class="text-xs text-gray-600">Görevler sadece gün bazında listelenir</span>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Tarih Aralığı Seçimi -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Program Geçerlilik Tarihi</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Başlangıç Tarihi</label>
                    <input type="date" wire:model="startDate"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('startDate') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Bitiş Tarihi</label>
                    <input type="date" wire:model="endDate" min="{{ $startDate }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('endDate') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>
            @if($startDate && $endDate)
                <p class="text-xs text-blue-600 mt-2">
                    📅 Program geçerlilik: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
                </p>
            @endif
        </div>

        <div class="mt-4 flex items-center gap-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" wire:model.live="isTemplate"
                    class="w-4 h-4 text-purple-600 rounded focus:ring-2 focus:ring-purple-500">
                <span class="text-sm text-gray-700 font-medium">Şablon olarak kaydet</span>
            </label>

            @if(!$isTemplate)
                <label class="flex items-center gap-2">
                    <input type="checkbox" wire:model="isActive"
                        class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Program aktif</span>
                </label>
            @endif

            <button wire:click="saveSchedule"
                class="ml-auto px-4 py-2 {{ $isTemplate ? 'bg-purple-600 hover:bg-purple-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white rounded-lg">
                {{ $isTemplate ? 'Şablonu Kaydet' : 'Programı Kaydet' }}
            </button>
        </div>

        @if($isTemplate)
            <div class="mt-4 bg-purple-50 border border-purple-200 rounded-lg p-3">
                <p class="text-sm text-purple-800">
                    <strong>💡 İpucu:</strong> Şablonlar öğrencilere atanabilir. Şablon oluşturduktan sonra Program Yönetimi
                    sayfasından istediğiniz öğrenciye atayabilirsiniz.
                </p>
            </div>
        @endif
    </div>

    @if($scheduleId)
        @if($scheduleType === 'timed')
            <!-- Saatli Haftalık Program Tablosu -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Saatli Haftalık Program</h2>
                            <p class="text-sm text-gray-600 mt-1">Hücrelere tıklayarak ders ekleyin veya dersleri
                                taşıyın/değiştirin</p>
                        </div>

                        @if($pendingSwapItemId)
                            <div class="flex-1 max-w-xl mx-4">
                                <div
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-between shadow-lg animate-pulse">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                        </svg>
                                        <span class="text-sm font-medium">Taşıma Modu Aktif: Hedef hücreyi seçin veya başka bir
                                            dersle yer değiştirin.</span>
                                    </div>
                                    <button wire:click="cancelMove" class="hover:bg-blue-700 p-1 rounded transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <button wire:click="toggleTimeColumn"
                                class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm">
                                <svg class="w-5 h-5 {{ $showTimeColumn ? 'text-green-600' : 'text-gray-400' }}" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                                <span class="text-sm font-medium {{ $showTimeColumn ? 'text-green-700' : 'text-gray-600' }}">
                                    {{ $showTimeColumn ? 'Saat Sütunu Açık' : 'Saat Sütunu Kapalı' }}
                                </span>
                            </button>
                            <button onclick="window.print()"
                                class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition shadow-sm print:hidden">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                <span class="text-sm font-medium">Yazdır</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
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
                    <table class="w-full border-collapse print-table">
                        <thead>
                            <tr class="bg-gray-50">
                                @if($showTimeColumn)
                                    <th
                                        class="sticky left-0 z-10 bg-gray-50 border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 w-28">
                                        Saat
                                    </th>
                                @endif
                                @foreach($days as $dayNum => $dayName)
                                    <th class="border border-gray-200 px-2 py-2 text-xs font-semibold text-gray-700 min-w-[140px]">
                                        {{ $dayName }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @if($showTimeColumn)
                                @foreach($timeSlots as $timeSlot)
                                    @php
                                        $isVisible = in_array($timeSlot, $visibleTimeSlots ?? []);
                                    @endphp
                                    @if($isVisible)
                                        <tr class="hover:bg-gray-50">
                                            <td
                                                class="sticky left-0 z-10 bg-white border border-gray-200 px-3 py-2 text-xs font-medium text-gray-600 whitespace-nowrap">
                                                <span class="font-semibold">{{ $timeSlot }}</span>
                                            </td>
                                            @foreach($days as $dayNum => $dayName)
                                                @php
                                                    $cellKey = $dayNum . '_' . $timeSlot;
                                                    $cellItems = $items[$cellKey] ?? [];
                                                    // İlk saat satırındaysa, saat olmayan görevleri de ekle
                                                    if ($timeSlot === $timeSlots[0]) {
                                                        $nullTimeKey = $dayNum . '_null';
                                                        if (isset($items[$nullTimeKey])) {
                                                            $cellItems = collect($cellItems)->merge($items[$nullTimeKey])->all();
                                                        }
                                                    }
                                                @endphp
                                                <td class="border border-gray-200 p-1 align-top">
                                                    <div class="space-y-1">
                                                        @if(count($cellItems) > 0)
                                                            @foreach($cellItems as $item)
                                                                <div class="bg-blue-100 border border-blue-300 rounded p-2 text-xs group relative"
                                                                    onclick="event.stopPropagation()">
                                                                    <div class="flex items-start justify-between gap-1">
                                                                        <div class="flex-1 min-w-0">
                                                                            @if($item->time_slot)
                                                                                <div class="mb-1">
                                                                                    <span
                                                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-200 text-blue-800">
                                                                                        🕐 {{ $item->time_slot }}
                                                                                    </span>
                                                                                </div>
                                                                            @endif

                                                                            @if($item->course)
                                                                                <div class="font-semibold text-blue-900 truncate">
                                                                                    {{ $item->course->name }}
                                                                                </div>
                                                                            @endif

                                                                            @if($item->topic)
                                                                                <div class="text-blue-700 truncate">
                                                                                    {{ Str::limit($item->topic->name, 25) }}
                                                                                </div>
                                                                            @endif

                                                                            @if($item->question_count > 0)
                                                                                <div class="text-blue-600 mt-1">
                                                                                    🎯 {{ $item->question_count }} soru
                                                                                </div>
                                                                            @endif

                                                                            @if($item->description)
                                                                                <div class="text-gray-600 text-[10px] mt-1">
                                                                                    {{ Str::limit($item->description, 30) }}
                                                                                </div>
                                                                            @endif
                                                                        </div>

                                                                        <div class="flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition">
                                                                            <div class="flex items-center gap-1">
                                                                                <button wire:click="moveItemUp({{ $item->id }})"
                                                                                    class="p-1 bg-white rounded hover:bg-blue-200" title="Yukarı Taşı">
                                                                                    <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            stroke-width="2" d="M5 15l7-7 7 7" />
                                                                                    </svg>
                                                                                </button>
                                                                                <button wire:click="moveItemDown({{ $item->id }})"
                                                                                    class="p-1 bg-white rounded hover:bg-blue-200" title="Aşağı Taşı">
                                                                                    <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                                                    </svg>
                                                                                </button>
                                                                            </div>
                                                                            <div class="flex items-center gap-1">
                                                                                <button
                                                                                    wire:click="{{ $pendingSwapItemId ? 'swapItems(' . $item->id . ')' : 'selectItemForMove(' . $item->id . ')' }}"
                                                                                    class="p-1 {{ $pendingSwapItemId == $item->id ? 'bg-blue-600 text-white' : 'bg-white text-blue-600' }} rounded hover:bg-blue-200"
                                                                                    title="Yerini Değiştir / Taşı">
                                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                                                    </svg>
                                                                                </button>
                                                                                <button
                                                                                    wire:click="openItemModal({{ $dayNum }}, '{{ $timeSlot }}', {{ $item->id }})"
                                                                                    class="p-1 bg-white rounded hover:bg-blue-200" title="Düzenle">
                                                                                    <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                                    </svg>
                                                                                </button>
                                                                                <button wire:click="deleteItem({{ $item->id }})"
                                                                                    onclick="return confirm('Bu görevi silmek istediğinizden emin misiniz?')"
                                                                                    class="p-1 bg-white rounded hover:bg-red-200" title="Sil">
                                                                                    <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                                    </svg>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif

                                                        <!-- Görev Ekle Butonu -->
                                                        <button
                                                            wire:click="{{ $pendingSwapItemId ? "moveToCell($dayNum, '$timeSlot')" : "openItemModal($dayNum, '$timeSlot')" }}"
                                                            onclick="event.stopPropagation()"
                                                            class="w-full mt-1 p-2 border-2 {{ $pendingSwapItemId ? 'border-blue-500 bg-blue-50 text-blue-600 animate-pulse' : 'border-dashed border-gray-300 text-gray-500' }} rounded-lg hover:border-blue-400 hover:bg-blue-50 transition-all flex items-center justify-center gap-2 text-xs hover:text-blue-600 font-medium">
                                                            @if($pendingSwapItemId)
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                                </svg>
                                                                <span>Buraya Taşı</span>
                                                            @else
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                                </svg>
                                                                <span>Görev Ekle</span>
                                                            @endif
                                                        </button>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <!-- Saat sütunu kapalıyken - tablo yapısı aynı, sadece saat sütunu yok -->
                                <tr class="hover:bg-gray-50">
                                    @foreach($days as $dayNum => $dayName)
                                        @php
                                            // O güne ait tüm görevleri topla (tüm saatlerden + saat olmayan görevler)
                                            $dayItems = collect();
                                            foreach ($timeSlots as $timeSlot) {
                                                $cellKey = $dayNum . '_' . $timeSlot;
                                                if (isset($items[$cellKey])) {
                                                    $dayItems = $dayItems->merge($items[$cellKey]);
                                                }
                                            }
                                            // Saat olmayan görevleri de ekle
                                            $nullTimeKey = $dayNum . '_null';
                                            if (isset($items[$nullTimeKey])) {
                                                $dayItems = $dayItems->merge($items[$nullTimeKey]);
                                            }
                                        @endphp
                                        <td class="border border-gray-200 p-1 align-top">
                                            <div class="space-y-1">
                                                @if($dayItems->count() > 0)
                                                    @foreach($dayItems as $item)
                                                        <div class="bg-blue-100 border border-blue-300 rounded p-2 text-xs group relative"
                                                            onclick="event.stopPropagation()">
                                                            <div class="flex items-start justify-between gap-1">
                                                                <div class="flex-1 min-w-0">
                                                                    @if($item->time_slot)
                                                                        <div class="mb-1">
                                                                            <span
                                                                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-200 text-blue-800">
                                                                                🕐 {{ $item->time_slot }}
                                                                            </span>
                                                                        </div>
                                                                    @endif

                                                                    @if($item->course)
                                                                        <div class="font-semibold text-blue-900 truncate">
                                                                            {{ $item->course->name }}
                                                                        </div>
                                                                    @endif

                                                                    @if($item->topic)
                                                                        <div class="text-blue-700 truncate">
                                                                            {{ Str::limit($item->topic->name, 25) }}
                                                                        </div>
                                                                    @endif

                                                                    @if($item->question_count > 0)
                                                                        <div class="text-blue-600 mt-1">
                                                                            🎯 {{ $item->question_count }} soru
                                                                        </div>
                                                                    @endif

                                                                    @if($item->description)
                                                                        <div class="text-gray-600 text-[10px] mt-1">
                                                                            {{ Str::limit($item->description, 30) }}
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                <div class="flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition">
                                                                    <div class="flex items-center gap-1">
                                                                        <button wire:click="moveItemUp({{ $item->id }})"
                                                                            class="p-1 bg-white rounded hover:bg-blue-200" title="Yukarı Taşı">
                                                                            <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                                    stroke-width="2" d="M5 15l7-7 7 7" />
                                                                            </svg>
                                                                        </button>
                                                                        <button wire:click="moveItemDown({{ $item->id }})"
                                                                            class="p-1 bg-white rounded hover:bg-blue-200" title="Aşağı Taşı">
                                                                            <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                                    stroke-width="2" d="M19 9l-7 7-7-7" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                    <div class="flex items-center gap-1">
                                                                        <button
                                                                            wire:click="{{ $pendingSwapItemId ? 'swapItems(' . $item->id . ')' : 'selectItemForMove(' . $item->id . ')' }}"
                                                                            class="p-1 {{ $pendingSwapItemId == $item->id ? 'bg-blue-600 text-white' : 'bg-white text-blue-600' }} rounded hover:bg-blue-200"
                                                                            title="Yerini Değiştir / Taşı">
                                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                                            </svg>
                                                                        </button>
                                                                        <button
                                                                            wire:click="openItemModal({{ $dayNum }}, '{{ $item->time_slot }}', {{ $item->id }})"
                                                                            class="p-1 bg-white rounded hover:bg-blue-200" title="Düzenle">
                                                                            <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                            </svg>
                                                                        </button>
                                                                        <button wire:click="deleteItem({{ $item->id }})"
                                                                            onclick="return confirm('Bu görevi silmek istediğinizden emin misiniz?')"
                                                                            class="p-1 bg-white rounded hover:bg-red-200" title="Sil">
                                                                            <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif

                                                <!-- Görev Ekle Butonu -->
                                                <button
                                                    wire:click="{{ $pendingSwapItemId ? "moveToCell($dayNum, 'null')" : "openItemModal($dayNum, null)" }}"
                                                    onclick="event.stopPropagation()"
                                                    class="w-full mt-1 p-2 border-2 {{ $pendingSwapItemId ? 'border-blue-500 bg-blue-50 text-blue-600 animate-pulse' : 'border-dashed border-gray-300 text-gray-500' }} rounded-lg hover:border-blue-400 hover:bg-blue-50 transition-all flex items-center justify-center gap-2 text-xs hover:text-blue-600 font-medium">
                                                    @if($pendingSwapItemId)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                        </svg>
                                                        <span>Buraya Taşı</span>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                        </svg>
                                                        <span>Görev Ekle</span>
                                                    @endif
                                                </button>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Saatsiz Günlük Program Listesi -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b">
                    <h2 class="text-lg font-semibold text-gray-900">Saatsiz Günlük Program</h2>
                    <p class="text-sm text-gray-600 mt-1">Her güne görev ekleyin (saat belirtmeden)</p>
                </div>

                <div class="divide-y divide-gray-200">
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
                            $dayItems = collect($schedule->items ?? [])->where('day_of_week', $dayNum);
                        @endphp

                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="text-base font-semibold text-gray-900">{{ $dayName }}</h3>
                                <button wire:click="openItemModal({{ $dayNum }}, null)"
                                    class="px-3 py-1 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Görev Ekle
                                </button>
                            </div>

                            @if($dayItems->count() > 0)
                                <div class="space-y-2">
                                    @foreach($dayItems as $item)
                                        <div class="bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    @if($item->time_slot)
                                                        <div class="mb-2">
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">
                                                                🕐 {{ $item->time_slot }}
                                                            </span>
                                                        </div>
                                                    @endif

                                                    @if($item->course)
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                                                                {{ $item->course->name }}
                                                            </span>
                                                            @if($item->question_count > 0)
                                                                <span class="text-xs text-gray-600">{{ $item->question_count }} soru</span>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if($item->topic)
                                                        <div class="text-xs text-gray-600 mb-1">{{ $item->topic->name }}</div>
                                                    @endif

                                                    @if($item->description)
                                                        <div class="text-sm text-gray-700">{{ $item->description }}</div>
                                                    @endif
                                                </div>

                                                <div class="flex flex-col gap-1 ml-2">
                                                    <div class="flex items-center gap-1">
                                                        <button wire:click="moveItemUp({{ $item->id }})"
                                                            class="p-1.5 bg-gray-100 rounded hover:bg-blue-100" title="Yukarı Taşı">
                                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M5 15l7-7 7 7" />
                                                            </svg>
                                                        </button>
                                                        <button wire:click="moveItemDown({{ $item->id }})"
                                                            class="p-1.5 bg-gray-100 rounded hover:bg-blue-100" title="Aşağı Taşı">
                                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 9l-7 7-7-7" />
                                                            </svg>
                                                        </button>
                                                        <button
                                                            wire:click="{{ $pendingSwapItemId ? 'swapItems(' . $item->id . ')' : 'selectItemForMove(' . $item->id . ')' }}"
                                                            class="p-1.5 {{ $pendingSwapItemId == $item->id ? 'bg-blue-600 text-white' : 'bg-gray-100 text-blue-600' }} rounded hover:bg-blue-100"
                                                            title="Yerini Değiştir / Taşı">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <button wire:click="openItemModal({{ $dayNum }}, null, {{ $item->id }})"
                                                            class="p-1.5 bg-gray-100 rounded hover:bg-blue-100" title="Düzenle">
                                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </button>
                                                        <button wire:click="deleteItem({{ $item->id }})"
                                                            onclick="return confirm('Bu görevi silmek istediğinizden emin misiniz?')"
                                                            class="p-1.5 bg-gray-100 rounded hover:bg-red-100" title="Sil">
                                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-sm text-gray-500 italic">Bu gün için henüz görev eklenmedi.</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @else
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
            Program görünümünü kullanabilmek için önce programı kaydedin.
        </div>
    @endif

    <!-- Görev Ekleme/Düzenleme Modalı -->
    @if($showItemModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeItemModal">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white" wire:click.stop>
                <div class="flex items-center justify-between pb-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">
                        {{ $editingItemId ? 'Görev Düzenle' : 'Yeni Görev Ekle' }}
                    </h3>
                    <button wire:click="closeItemModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4">
                    <div class="grid {{ $scheduleType === 'timed' ? 'grid-cols-2' : 'grid-cols-1' }} gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gün *</label>
                            <select wire:model="dayOfWeek" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                                <option value="1">Pazartesi</option>
                                <option value="2">Salı</option>
                                <option value="3">Çarşamba</option>
                                <option value="4">Perşembe</option>
                                <option value="5">Cuma</option>
                                <option value="6">Cumartesi</option>
                                <option value="7">Pazar</option>
                            </select>
                        </div>

                        @if($scheduleType === 'timed')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Saat Dilimi <span
                                        class="text-gray-400 text-xs">(Opsiyonel)</span></label>
                                <select wire:model="timeSlot" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                                    <option value="">Saat Seçin (Opsiyonel)</option>
                                    @foreach($timeSlots as $slot)
                                        <option value="{{ $slot }}">{{ $slot }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ders</label>
                        <select wire:model.live="courseId" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            <option value="">Ders Seçin</option>

                            @if(is_array($courses) && isset($courses['tyt']) && $courses['tyt']->count() > 0)
                                <optgroup label="📘 TYT Dersleri">
                                    @foreach($courses['tyt'] as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endif

                            @if(is_array($courses) && isset($courses['ayt']) && $courses['ayt']->count() > 0)
                                <optgroup label="📗 AYT Dersleri">
                                    @foreach($courses['ayt'] as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endif

                            @if(is_array($courses) && isset($courses['other']) && $courses['other']->count() > 0)
                                <optgroup label="📕 Diğer Dersler">
                                    @foreach($courses['other'] as $course)
                                        <option value="{{ $course->id }}">
                                            {{ $course->name }}{{ $course->field ? ' (' . $course->field->name . ')' : '' }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif

                            {{-- Eski format desteği (backward compatibility) --}}
                            @if(!is_array($courses))
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">
                                        {{ $course->name }}{{ $course->field ? ' (' . $course->field->name . ')' : '' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @if($courseId)
                            @php
                                // Seçilen dersi bul
                                $selectedCourse = null;
                                if (is_array($courses)) {
                                    $allCoursesList = collect($courses['tyt'] ?? [])
                                        ->merge($courses['ayt'] ?? [])
                                        ->merge($courses['other'] ?? []);
                                    $selectedCourse = $allCoursesList->firstWhere('id', $courseId);
                                } else {
                                    $selectedCourse = $courses->firstWhere('id', $courseId);
                                }
                            @endphp
                            @if($selectedCourse && $selectedCourse->field)
                                    <div
                                        class="mt-2 inline-flex items-center px-2 py-1 rounded-md text-xs font-medium 
                                                    {{ strtolower($selectedCourse->field->slug) === 'tyt' ? 'bg-blue-100 text-blue-800' :
                                (strtolower($selectedCourse->field->slug) === 'ayt' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800') }}">
                                        📚 {{ $selectedCourse->field->name }}
                                    </div>
                            @endif
                        @endif
                    </div>

                    @if($courseId && $topics->count() > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Konu</label>
                            <select wire:model.live="topicId" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                                <option value="">Konu Seçin</option>
                                @foreach($topics as $topic)
                                    <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if($topicId && $subTopics->count() > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alt Konu</label>
                            <select wire:model="subTopicId" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                                <option value="">Alt Konu Seçin</option>
                                @foreach($subTopics as $subTopic)
                                    <option value="{{ $subTopic->id }}">{{ $subTopic->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if($studentId && count($availableResources) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kaynak <span class="text-gray-400 text-xs">(Opsiyonel)</span>
                            </label>
                            <select wire:model="studentResourceId" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                                <option value="">Kaynak Seçin (Opsiyonel)</option>
                                @foreach($availableResources as $studentResource)
                                    <option value="{{ $studentResource->id }}">
                                        {{ $studentResource->resource->name }}
                                        @if($studentResource->course)
                                            - {{ $studentResource->course->name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                Öğrenciye atanmış kaynaklar listelenmektedir.
                            </p>
                        </div>
                    @elseif($studentId)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <p class="text-xs text-yellow-800">
                                <strong>ℹ️ Bilgi:</strong> Bu öğrenciye henüz kaynak atanmamış.
                                Kaynak atamak için "Kaynak Atama" sayfasını kullanabilirsiniz.
                            </p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Soru Sayısı Hedefi</label>
                        <input type="number" wire:model="questionCount" min="0"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama/Not</label>
                        <textarea wire:model="description" rows="3" placeholder="Ek notlar veya açıklamalar..."
                            class="w-full border border-gray-300 rounded-lg px-4 py-2"></textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                        <button type="button" wire:click="closeItemModal"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            İptal
                        </button>
                        <button wire:click="saveItem" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Kaydet
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Yazdırma için başlık -->
    <div class="print-header" style="display: none;">
        <h1>{{ $scheduleName ?? 'Program' }}</h1>
        <div class="print-info">
            @if($scheduleId)
                @php
                    $scheduleForPrint = \App\Models\StudySchedule::with('student')->find($scheduleId);
                @endphp
                @if($scheduleForPrint && $scheduleForPrint->student)
                    <strong>Öğrenci:</strong> {{ $scheduleForPrint->student->name }} |
                @endif
                @if($startDate && $endDate)
                    <strong>Geçerlilik:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }} |
                @endif
                <strong>Oluşturulma:</strong> {{ \Carbon\Carbon::now()->format('d.m.Y H:i') }}
            @endif
        </div>
    </div>

    <style>
        @media print {

            /* Yazdırma için gizlenecek elementler */
            .no-print,
            button,
            .print\\:hidden,
            .bg-gradient-to-r,
            .shadow-sm {
                display: none !important;
            }

            /* Sayfa ayarları */
            @page {
                margin: 1cm;
                size: A4 landscape;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: white !important;
            }

            /* Program başlığı için yazdırma */
            .print-header {
                display: block !important;
                margin-bottom: 20px;
                padding: 15px;
                border-bottom: 2px solid #000;
                background: white !important;
            }

            .print-header h1 {
                font-size: 20px;
                font-weight: bold;
                margin-bottom: 8px;
                color: #000 !important;
            }

            .print-header .print-info {
                font-size: 11px;
                color: #333 !important;
            }

            /* Ana tablo yazdırma stilleri */
            .print-table {
                width: 100% !important;
                border-collapse: collapse !important;
                page-break-inside: auto;
            }

            .print-table th,
            .print-table td {
                border: 1px solid #000 !important;
                padding: 6px !important;
                font-size: 9px !important;
                background: white !important;
                color: #000 !important;
            }

            .print-table th {
                background-color: #f3f4f6 !important;
                font-weight: bold !important;
                text-align: center !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .print-table .bg-blue-100 {
                background-color: #dbeafe !important;
                border: 1px solid #93c5fd !important;
                padding: 4px !important;
                margin-bottom: 2px !important;
                page-break-inside: avoid;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .print-table .bg-blue-100 * {
                color: #000 !important;
            }

            /* Günlük program yazdırma */
            .daily-program-print {
                page-break-inside: avoid;
                margin-bottom: 15px;
            }

            .daily-program-print h3 {
                font-size: 14px;
                font-weight: bold;
                margin-bottom: 8px;
                border-bottom: 1px solid #000;
                padding-bottom: 4px;
                color: #000 !important;
            }

            .daily-program-print .task-box {
                background-color: #dbeafe !important;
                border: 1px solid #93c5fd !important;
                padding: 6px;
                margin-bottom: 4px;
                page-break-inside: avoid;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .daily-program-print .task-box * {
                color: #000 !important;
            }

            /* Genel yazdırma stilleri */
            .bg-white,
            .bg-blue-50,
            .bg-green-50 {
                background: white !important;
            }

            /* Butonlar ve interaktif elementler gizle */
            button,
            .btn-primary,
            .btn-secondary,
            input[type="button"],
            input[type="submit"],
            a[href*="#"],
            svg {
                display: none !important;
            }

            /* Modal ve overlay gizle */
            .modal,
            .overlay,
            [x-show],
            [wire\\:click] {
                display: none !important;
            }
        }
    </style>

    <script>
        // Yazdırma öncesi hazırlık
        window.addEventListener('beforeprint', function () {
            // Yazdırma başlığını göster
            const printHeader = document.querySelector('.print-header');
            if (printHeader) {
                printHeader.style.display = 'block';
            }
        });

        window.addEventListener('afterprint', function () {
            // Yazdırma sonrası gizle
            const printHeader = document.querySelector('.print-header');
            if (printHeader) {
                printHeader.style.display = 'none';
            }
        });
    </script>
</div>