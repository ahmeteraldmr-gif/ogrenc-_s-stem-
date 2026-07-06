<div class="space-y-6">
    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Üst Bar -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex flex-col md:flex-row items-center gap-4">
            <div class="flex-1 w-full">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Kaynak adı ile ara..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <select wire:model.live="filterType"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all">Tüm Kaynaklar</option>
                    <option value="admin">Admin Kaynakları</option>
                    <option value="my">Kendi Kaynaklarım</option>
                </select>

                <select wire:model.live="selectedField"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tüm Alanlar</option>
                    @foreach($fields as $field)
                        <option value="{{ $field->id }}">{{ $field->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="selectedCourse"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    @if(!$selectedField) disabled @endif>
                    <option value="">Tüm Dersler</option>
                    @foreach($filterCourses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>

                <button wire:click="openModal()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Kaynak Ekle
                </button>
            </div>
        </div>
    </div>

    <!-- Kaynak Kartları -->
    @if($resources->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <p class="text-gray-600 text-lg mb-4">Henüz kaynak bulunamadı</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($resources as $resource)
                <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $resource->name }}</h3>
                            <div class="text-xs font-medium">
                                <span class="text-blue-600">{{ $resource->field?->name ?? 'Belirlenmedi' }}</span>
                                <span class="text-gray-400 mx-1">/</span>
                                <span class="text-gray-600">{{ $resource->course?->name ?? 'Belirlenmedi' }}</span>
                            </div>
                        </div>
                        @if($resource->is_admin_resource)
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full">Admin</span>
                        @endif
                    </div>

                    @if($resource->description)
                        <p class="text-sm text-gray-500 mb-4">{{ Str::limit($resource->description, 80) }}</p>
                    @endif

                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <span>{{ $resource->createdBy->name }}</span>
                        <span>{{ $resource->studentResources->count() }} öğrenci</span>
                    </div>

                    @if(!$resource->is_admin_resource && $resource->created_by_user_id === auth()->id())
                        <div class="flex items-center gap-2 pt-4 border-t">
                            <button wire:click="openModal({{ $resource->id }})"
                                class="flex-1 px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                Düzenle
                            </button>
                            <button wire:click="delete({{ $resource->id }})"
                                onclick="return confirm('Bu kaynağı silmek istediğinizden emin misiniz?')"
                                class="flex-1 px-3 py-1.5 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">
                                Sil
                            </button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($resources->hasPages())
            <div class="bg-white rounded-lg shadow-sm p-4">
                {{ $resources->links() }}
            </div>
        @endif
    @endif

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModal">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white" wire:click.stop>
                <div class="flex items-center justify-between pb-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">
                        {{ $editingId ? 'Kaynak Düzenle' : 'Yeni Kaynak Ekle' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alan Seçimi *</label>
                            <select wire:model.live="field_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Alan Seçin</option>
                                @foreach($fields as $field)
                                    <option value="{{ $field->id }}">{{ $field->name }}</option>
                                @endforeach
                            </select>
                            @error('field_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ders Seçimi *</label>
                            <select wire:model="course_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                @if(!$field_id) disabled @endif>
                                <option value="">Ders Seçin</option>
                                @foreach($modalCourses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            </select>
                            @error('course_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kaynak Adı *</label>
                        <input type="text" wire:model="name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Örn: Matematik Soru Bankası">
                        @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                        <textarea wire:model="description" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Kaynak hakkında ek bilgiler..."></textarea>
                        @error('description') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            İptal
                        </button>
                        <button wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            {{ $editingId ? 'Güncelle' : 'Kaydet' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>