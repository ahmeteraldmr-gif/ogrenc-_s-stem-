<x-layouts.coach>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Program Yönetimi</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Öğrencileriniz için haftalık çalışma programları oluşturun ve yönetin
                    </p>
                </div>
                <a href="{{ route('coach.schedules.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Yeni Program Oluştur
                </a>
            </div>

            <livewire:coach.schedule-management />
        </div>
    </div>
</x-layouts.coach>
