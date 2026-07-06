<div class="space-y-6">
    <!-- Filtreler -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-700 mb-2 block">Görünüm</label>
                <select wire:model.live="groupBy" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="course">Derse Göre Grupla</option>
                    <option value="coach">Koça Göre Grupla</option>
                    <option value="none">Hepsi</option>
                </select>
            </div>
            @if($courses->count() > 0)
                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Ders Filtrele</label>
                    <select wire:model.live="filterCourse" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Tüm Dersler</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>

    <!-- Kaynaklar -->
    @if($assignments->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <p class="text-gray-600 text-lg mb-2">Henüz kaynak atanmadı</p>
            <p class="text-gray-500 text-sm">Koçunuz size kaynak atadığında burada görünecek</p>
        </div>
    @else
        @if($groupBy === 'none')
            <!-- Gruplandırılmamış Görünüm -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($assignments as $assignment)
                    <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $assignment->resource->name }}</h3>
                        
                        @if($assignment->resource->publisher)
                            <p class="text-sm text-gray-600 mb-2">{{ $assignment->resource->publisher }}</p>
                        @endif

                        @if($assignment->resource->description)
                            <p class="text-sm text-gray-500 mb-3">{{ Str::limit($assignment->resource->description, 80) }}</p>
                        @endif

                        <div class="space-y-2">
                            @if($assignment->course)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $assignment->course->name }}</span>
                                </div>
                            @endif

                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-sm text-gray-700">{{ $assignment->coach->name }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-xs text-gray-500">{{ $assignment->assigned_at->format('d.m.Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Gruplandırılmış Görünüm -->
            <div class="space-y-6">
                @foreach($groupedAssignments as $groupId => $groupAssignments)
                    @php
                        $groupName = $groupBy === 'course' 
                            ? ($groupAssignments->first()->course?->name ?? 'Ders Belirtilmemiş')
                            : $groupAssignments->first()->coach->name;
                        
                        $bgColors = [
                            'bg-blue-50 border-blue-200',
                            'bg-green-50 border-green-200',
                            'bg-purple-50 border-purple-200',
                            'bg-orange-50 border-orange-200',
                            'bg-pink-50 border-pink-200',
                        ];
                        $colorIndex = $groupId ? $groupId % count($bgColors) : 0;
                        $bgColor = $bgColors[$colorIndex];
                    @endphp

                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 {{ $bgColor }} border-b">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $groupName }}</h3>
                            <p class="text-sm text-gray-600">{{ $groupAssignments->count() }} kaynak</p>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($groupAssignments as $assignment)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                        <h4 class="font-medium text-gray-900 mb-1">{{ $assignment->resource->name }}</h4>
                                        
                                        @if($assignment->resource->publisher)
                                            <p class="text-sm text-gray-600 mb-2">{{ $assignment->resource->publisher }}</p>
                                        @endif

                                        @if($assignment->resource->description)
                                            <p class="text-xs text-gray-500 mb-2">{{ Str::limit($assignment->resource->description, 60) }}</p>
                                        @endif

                                        <div class="flex items-center justify-between mt-3 pt-3 border-t">
                                            @if($groupBy !== 'coach')
                                                <span class="text-xs text-gray-500">{{ $assignment->coach->name }}</span>
                                            @endif
                                            @if($groupBy !== 'course' && $assignment->course)
                                                <span class="text-xs text-gray-500">{{ $assignment->course->name }}</span>
                                            @endif
                                            <span class="text-xs text-gray-400">{{ $assignment->assigned_at->format('d.m.Y') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>

