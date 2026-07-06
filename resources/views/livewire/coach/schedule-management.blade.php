<div class="space-y-6">
    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tab Navigation -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button 
                    wire:click="$set('activeTab', 'schedules')"
                    class="px-6 py-4 text-sm font-medium border-b-2 transition {{ $activeTab === 'schedules' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Öğrenci Programları
                    </div>
                </button>
                <button 
                    wire:click="$set('activeTab', 'templates')"
                    class="px-6 py-4 text-sm font-medium border-b-2 transition {{ $activeTab === 'templates' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Şablonlar
                    </div>
                </button>
            </nav>
        </div>
    </div>

    <!-- Filtreleme -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="{{ $activeTab === 'templates' ? 'Şablon adı ile ara...' : 'Program adı veya öğrenci adı ile ara...' }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            @if($activeTab === 'schedules')
                <div>
                    <select wire:model.live="selectedStudent" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Tüm Öğrenciler</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>

    <!-- Liste -->
    @if($items->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-600 text-lg mb-4">
                @if($activeTab === 'templates')
                    Henüz şablon oluşturulmadı
                @else
                    Henüz program oluşturulmadı
                @endif
            </p>
            <a href="{{ route('coach.schedules.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ $activeTab === 'templates' ? 'İlk Şablonu Oluştur' : 'İlk Programı Oluştur' }}
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4">
            @foreach($items as $item)
                <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h3>
                                
                                @if($activeTab === 'templates')
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full">Şablon</span>
                                @else
                                    @if($item->is_active)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Aktif</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">Pasif</span>
                                    @endif
                                @endif
                            </div>
                            
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                @if($activeTab === 'schedules' && $item->student)
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $item->student->name }}
                                    </div>
                                @endif
                                <div class="flex items-center gap-1">
                                    @if($item->schedule_type === 'timed')
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-blue-600 font-medium">Saatli</span>
                                    @else
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span class="text-green-600 font-medium">Saatsiz</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    {{ $item->items->count() }} görev
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $item->created_at->format('d.m.Y') }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($activeTab === 'templates')
                                <button 
                                    wire:click="openAssignModal({{ $item->id }})"
                                    class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                    Öğrenciye Ata
                                </button>
                            @endif
                            
                            <a href="{{ route('coach.schedules.edit', $item->id) }}" 
                               class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                Düzenle
                            </a>
                            
                            @if($activeTab === 'schedules')
                                <button 
                                    wire:click="toggleActive({{ $item->id }})"
                                    class="px-3 py-1.5 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-gray-300">
                                    {{ $item->is_active ? 'Pasif Et' : 'Aktif Et' }}
                                </button>
                            @endif
                            
                            <button 
                                wire:click="deleteSchedule({{ $item->id }})"
                                onclick="return confirm('{{ $activeTab === 'templates' ? 'Bu şablonu' : 'Bu programı' }} silmek istediğinizden emin misiniz?')"
                                class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">
                                Sil
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
            <div class="bg-white rounded-lg shadow-sm p-4">
                {{ $items->links() }}
            </div>
        @endif
    @endif

    <!-- Şablonu Öğrenciye Atama Modalı -->
    @if($showAssignModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeAssignModal">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white" wire:click.stop>
                <div class="flex items-center justify-between pb-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">Şablonu Öğrenciye Ata</h3>
                    <button wire:click="closeAssignModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Öğrenci Seçin *</label>
                        <select wire:model="assignToStudentId" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Öğrenci Seçin</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                        @error('assignToStudentId') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-sm text-blue-800">
                            <strong>Not:</strong> Şablon görevleri seçilen öğrenciye kopyalanacaktır.
                        </p>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                        <button type="button" wire:click="closeAssignModal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            İptal
                        </button>
                        <button wire:click="assignTemplateToStudent" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Ata
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
