<x-layouts.coach>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.student_progress') }}</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Öğrencinin ders ve konu ilerlemesini takip edin
                </p>
            </div>

            <livewire:coach.progress-tracking :studentId="$studentId" />
        </div>
    </div>
</x-layouts.coach>
