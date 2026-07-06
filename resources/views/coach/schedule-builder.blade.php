<x-layouts.coach>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('coach.schedules') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Programlara Dön
                </a>
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ isset($scheduleId) ? 'Program Düzenle' : 'Yeni Program Oluştur' }}
                </h1>
                <p class="mt-1 text-sm text-gray-600">
                    Haftalık ders programı hazırlayın
                </p>
            </div>

            <livewire:coach.schedule-builder :scheduleId="$scheduleId ?? null" />
        </div>
    </div>
</x-layouts.coach>
