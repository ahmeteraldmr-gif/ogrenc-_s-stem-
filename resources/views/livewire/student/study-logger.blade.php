<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Çalışma Takibi</h2>
            <p class="text-sm text-gray-600 mt-1">Günlük çalışmalarınızı kaydedin</p>
        </div>
        <button wire:click="openModal" class="btn-primary">
            + Çalışma Ekle
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($studyLogs as $log)
            <div class="card hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-2">
                        @if($log->resource_type === 'video')
                            <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @elseif($log->resource_type === 'book')
                            <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        @else
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        @endif
                        <span class="text-xs font-medium text-gray-500 uppercase">{{ $log->resource_type ?? 'Genel' }}</span>
                    </div>
                    <span class="text-xs text-gray-500">{{ $log->study_date->format('d.m.Y') }}</span>
                </div>

                <div class="mb-3">
                    @if($log->topic)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            {{ $log->topic->name }}
                        </span>
                    @endif
                    @if($log->subTopic)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 ml-1">
                            {{ $log->subTopic->name }}
                        </span>
                    @endif
                </div>

                @if($log->resource_title)
                    <h4 class="font-medium text-gray-900 mb-2">{{ $log->resource_title }}</h4>
                @endif

                @if($log->description)
                    <p class="text-sm text-gray-600 mb-3">{{ $log->description }}</p>
                @endif

                <div class="flex justify-end pt-3 border-t border-gray-200">
                    <button 
                        wire:click="delete({{ $log->id }})"
                        onclick="return confirm('Bu kaydı silmek istediğinize emin misiniz?')"
                        class="text-red-600 hover:text-red-900 text-sm"
                    >
                        Sil
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full card text-center text-gray-500 py-8">
                Henüz çalışma kaydı bulunmamaktadır.
            </div>
        @endforelse
    </div>

    @if($studyLogs->hasPages())
        <div class="card">
            {{ $studyLogs->links() }}
        </div>
    @endif

    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModal">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white" wire:click.stop>
                <div class="flex items-center justify-between pb-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">Çalışma Kaydı Ekle</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tarih *</label>
                        <input type="date" wire:model="study_date" class="input-field">
                        @error('study_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kaynak Türü</label>
                        <select wire:model="resource_type" class="input-field">
                            <option value="video">Video</option>
                            <option value="book">Kitap</option>
                            <option value="notes">Notlar</option>
                            <option value="other">Diğer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kaynak Başlığı</label>
                        <input type="text" wire:model="resource_title" class="input-field" placeholder="Örn: Khan Academy Türev Videosu">
                        @error('resource_title') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konu</label>
                        <select wire:model="topic_id" class="input-field">
                            <option value="">Seçiniz</option>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alt Konu</label>
                        <select wire:model="sub_topic_id" class="input-field">
                            <option value="">Seçiniz</option>
                            @foreach($subTopics as $subTopic)
                                <option value="{{ $subTopic->id }}">{{ $subTopic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                        <textarea wire:model="description" class="input-field" rows="3" placeholder="Ne öğrendiniz, nasıl çalıştınız?"></textarea>
                        @error('description') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                        <button type="button" wire:click="closeModal" class="btn-secondary">İptal</button>
                        <button type="submit" class="btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
