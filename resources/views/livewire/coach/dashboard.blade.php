<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">Coach Dashboard</h1>
        <div class="flex gap-2">
            <a href="{{ route('coach.students') }}" class="btn-primary">
                + Öğrenci Yönetimi
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="card bg-blue-50 border-blue-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-600 rounded-lg p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="ml-5">
                    <dt class="text-sm font-medium text-gray-500 truncate">Toplam Öğrenci</dt>
                    <dd class="text-2xl font-bold text-gray-900">{{ $students->count() }}</dd>
                </div>
            </div>
        </div>

        <div class="card bg-red-50 border-red-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-600 rounded-lg p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-5">
                    <dt class="text-sm font-medium text-gray-500 truncate">Kritik Uyarılar</dt>
                    <dd class="text-2xl font-bold text-gray-900">{{ $criticalAlerts->count() }}</dd>
                </div>
            </div>
        </div>
    </div>

    @if($criticalAlerts->count() > 0)
        <!-- Kritik Uyarılar Bölümü -->
        <div class="card border-red-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h3 class="text-xl font-bold text-red-700 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Kritik Uyarılar (Akıllı Başarı Analizi)
                    </h3>
                    <p class="text-sm text-gray-500 mt-1 italic">Bu veriler öğrencilerin günlük **soru çözüm kayıtlarından**
                        analiz edilmektedir.</p>
                </div>

                <!-- Filtreleme Araçları -->
                <div class="flex flex-wrap items-center gap-3 bg-white p-2 rounded-lg border border-red-100 shadow-sm">
                    <div class="flex items-center gap-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Alan:</label>
                        <select wire:model.live="selectedFieldId"
                            class="text-xs border-gray-200 rounded-md focus:ring-red-500 focus:border-red-500 py-1 pr-8">
                            <option value="">Tümü</option>
                            @foreach($fields as $field)
                                <option value="{{ $field->id }}">{{ $field->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Ders:</label>
                        <select wire:model.live="selectedCourseId"
                            class="text-xs border-gray-200 rounded-md focus:ring-red-500 focus:border-red-500 py-1 pr-8" {{ empty($selectedFieldId) ? 'disabled' : '' }}>
                            <option value="">Tümü</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($criticalAlerts as $alert)
                    @php
                        $rate = ($alert->correct / $alert->total) * 100;
                        $fieldName = $alert->course->field->name ?? 'Genel';
                    @endphp
                    <div
                        class="bg-white border-2 border-gray-100 rounded-xl p-5 shadow-sm hover:border-red-200 hover:shadow-md transition relative group overflow-hidden">
                        <div class="absolute top-0 right-0 p-2">
                            <span
                                class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-[10px] font-bold border border-blue-100">
                                {{ $fieldName }}
                            </span>
                        </div>

                        <div class="flex flex-col h-full relative z-10">
                            <div class="flex items-center gap-2 mb-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold text-xs">
                                    {{ substr($alert->student->name, 0, 1) }}
                                </div>
                                <span class="font-bold text-gray-900 truncate">{{ $alert->student->name }}</span>
                            </div>

                            <div class="mb-4">
                                <span
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $alert->course->name }}</span>
                                <h4 class="font-bold text-gray-800 leading-tight">{{ $alert->topic->name }}</h4>
                            </div>

                            <div class="mt-auto space-y-3">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Başarı Oranı:</span>
                                    <span class="font-black text-red-600">%{{ round($rate, 1) }}</span>
                                </div>

                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-red-500 h-1.5 rounded-full" style="width: {{ $rate }}%"></div>
                                </div>

                                <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                    <span class="text-[10px] text-gray-400">{{ $alert->total }} Soru / {{ $alert->correct }}
                                        Doğru</span>
                                    <a href="{{ route('coach.student.detail', $alert->student_id) }}?tab=analiz"
                                        class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                        Analiz Et
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Öğrencilerim</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İsim</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">E-posta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kayıt Tarihi</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($students as $student)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $student->created_at->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('coach.student.detail', $student->id) }}"
                                    class="text-blue-600 hover:text-blue-900">Detay</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>