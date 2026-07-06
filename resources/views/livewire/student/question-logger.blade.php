<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Soru Takibi</h2>
            <p class="text-sm text-gray-600 mt-1">Günlük çözdüğünüz soruları kaydedin</p>
        </div>
        <button wire:click="openModal" class="btn-primary">
            + Soru Ekle
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="card bg-blue-50">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_questions'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Toplam Soru</div>
        </div>
        <div class="card bg-green-50">
            <div class="text-3xl font-bold text-green-600">{{ $stats['total_correct'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Doğru</div>
        </div>
        <div class="card bg-red-50">
            <div class="text-3xl font-bold text-red-600">{{ $stats['total_wrong'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Yanlış</div>
        </div>
        <div class="card bg-gray-50">
            <div class="text-3xl font-bold text-gray-600">{{ $stats['total_blank'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Boş</div>
        </div>
        <div class="card bg-purple-50">
            <div class="text-3xl font-bold text-purple-600">%{{ $stats['success_rate'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Başarı Oranı</div>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tarih</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Konu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alt Konu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Toplam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doğru</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Yanlış</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Boş</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İşlem</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($questionLogs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->log_date->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->course?->name ?? 'Genel' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $log->topic?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $log->subTopic?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">
                                {{ $log->total_questions }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                {{ $log->correct_answers }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                {{ $log->wrong_answers }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->blank_answers }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button 
                                    wire:click="delete({{ $log->id }})"
                                    onclick="return confirm('Bu kaydı silmek istediğinize emin misiniz?')"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Sil
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                Henüz soru kaydı bulunmamaktadır.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ $questionLogs->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModal">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white" wire:click.stop>
                <div class="flex items-center justify-between pb-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">Soru Kaydı Ekle</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tarih *</label>
                        <input type="date" wire:model="log_date" class="input-field">
                        @error('log_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ders</label>
                        <select wire:model.live="course_id" class="input-field">
                            <option value="">Ders Seçin</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                        @error('course_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    @if($course_id && count($topics) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Konu</label>
                            <select wire:model.live="topic_id" class="input-field">
                                <option value="">Konu Seçin</option>
                                @foreach($topics as $topic)
                                    <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                @endforeach
                            </select>
                            @error('topic_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    @if($topic_id && count($subTopics) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alt Konu</label>
                            <select wire:model="sub_topic_id" class="input-field">
                                <option value="">Alt Konu Seçin</option>
                                @foreach($subTopics as $subTopic)
                                    <option value="{{ $subTopic->id }}">{{ $subTopic->name }}</option>
                                @endforeach
                            </select>
                            @error('sub_topic_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Toplam Soru *</label>
                        <input type="number" wire:model.live="total_questions" class="input-field" placeholder="100" min="1">
                        @error('total_questions') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Doğru *</label>
                            <input type="number" wire:model.live="correct_answers" class="input-field" placeholder="50" min="0">
                            @error('correct_answers') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Yanlış *</label>
                            <input type="number" wire:model.live="wrong_answers" class="input-field" placeholder="20" min="0">
                            @error('wrong_answers') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Boş (Otomatik)</label>
                        <input type="number" wire:model="blank_answers" class="input-field bg-gray-100" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notlar</label>
                        <textarea wire:model="notes" class="input-field" rows="3" placeholder="İsteğe bağlı notlar..."></textarea>
                        @error('notes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
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
