<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Öğrenci Yönetimi</h2>
            <p class="text-sm text-gray-600 mt-1">
                @if($studentLimit)
                    {{ $currentCount }} / {{ $studentLimit }} öğrenci
                @else
                    {{ $currentCount }} öğrenci (Sınırsız)
                @endif
            </p>
        </div>
        <button wire:click="openModal" class="btn-primary">
            + Yeni Öğrenci Ekle
        </button>
    </div>

    <!-- Flash Messages -->
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

    <!-- Search -->
    <div class="card">
        <input 
            type="text" 
            wire:model.live.debounce.300ms="search" 
            placeholder="Öğrenci adı veya e-posta ile ara..."
            class="input-field"
        >
    </div>

    <!-- Students Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($students as $student)
            <div class="card hover:shadow-lg transition-all hover:border-blue-300 border border-transparent cursor-pointer" onclick="window.location='{{ route('coach.student.detail', $student->id) }}'">
                <!-- Student Header -->
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex-shrink-0 h-16 w-16">
                        <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xl font-bold shadow-lg">
                            {{ substr($student->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-gray-900 truncate hover:text-blue-600 transition">{{ $student->name }}</h3>
                        <p class="text-sm text-gray-500 truncate">{{ $student->email }}</p>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-blue-50 rounded-lg p-3">
                        <div class="text-2xl font-bold text-blue-600">{{ $student->question_logs_count }}</div>
                        <div class="text-xs text-gray-600">Soru Kaydı</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3">
                        <div class="text-2xl font-bold text-green-600">{{ $student->exam_results_count }}</div>
                        <div class="text-xs text-gray-600">Deneme</div>
                    </div>
                </div>

                <!-- Info -->
                <div class="space-y-2 mb-4 text-sm">
                    @if($student->phone)
                        <div class="flex items-center text-gray-600">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $student->phone }}
                        </div>
                    @endif
                    <div class="flex items-center text-gray-600">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $student->created_at->format('d.m.Y') }} tarihinden beri
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $student->is_active ? 'Aktif' : 'Pasif' }}
                        </span>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <a href="{{ route('coach.assign', $student->id) }}" 
                           onclick="event.stopPropagation()"
                           class="px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 text-center">
                            📚 Ders Ata
                        </a>
                        <a href="{{ route('coach.progress', $student->id) }}" 
                           onclick="event.stopPropagation()"
                           class="px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 text-center">
                            📊 İlerleme
                        </a>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-2">
                        <button wire:click="edit({{ $student->id }})" onclick="event.stopPropagation()" class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                            Düzenle
                        </button>
                        <button 
                            wire:click="delete({{ $student->id }})"
                            onclick="event.stopPropagation(); return confirm('Bu öğrenciyi listenizden çıkarmak istediğinize emin misiniz?')"
                            class="text-red-600 hover:text-red-900 text-sm font-medium"
                        >
                            Sil
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full card text-center text-gray-500 py-8">
                Henüz öğrenci eklenmemiş. "Yeni Öğrenci Ekle" butonuna tıklayarak başlayın.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
        <div class="card">
            {{ $students->links() }}
        </div>
    @endif

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModal">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white" wire:click.stop>
                <div class="flex items-center justify-between pb-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">
                        {{ $editMode ? 'Öğrenci Düzenle' : 'Yeni Öğrenci Ekle' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            İsim Soyisim *
                        </label>
                        <input 
                            type="text" 
                            wire:model="name" 
                            class="input-field"
                            placeholder="Ali Yılmaz"
                        >
                        @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            E-posta *
                        </label>
                        <input 
                            type="email" 
                            wire:model="email" 
                            class="input-field"
                            placeholder="ornek@email.com"
                        >
                        @error('email') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Şifre {{ $editMode ? '(Boş bırakılırsa değişmez)' : '*' }}
                        </label>
                        <input 
                            type="password" 
                            wire:model="password" 
                            class="input-field"
                            placeholder="••••••••"
                        >
                        @error('password') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Telefon
                        </label>
                        <input 
                            type="text" 
                            wire:model="phone" 
                            class="input-field"
                            placeholder="0555 555 55 55"
                        >
                        @error('phone') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                wire:model="is_active" 
                                class="h-4 w-4 text-accent-blue focus:ring-accent-blue border-gray-300 rounded"
                            >
                            <span class="ml-2 text-sm text-gray-700">Aktif</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                        <button type="button" wire:click="closeModal" class="btn-secondary">
                            İptal
                        </button>
                        <button type="submit" class="btn-primary">
                            {{ $editMode ? 'Güncelle' : 'Kaydet' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
