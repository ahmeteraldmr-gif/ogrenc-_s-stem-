<div class="space-y-4">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Alan & Dersler</h2>
        <p class="text-sm text-gray-600 mt-1">
            Admin tarafından eklenen tüm alanlar, dersler, konular ve alt konular
        </p>
    </div>

    @if($fields->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <p class="text-gray-600 text-lg">Henüz alan eklenmemiş</p>
        </div>
    @else
        @foreach($fields as $field)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Alan Başlığı -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 border-b border-gray-200">
                    <button 
                        wire:click="toggleField({{ $field->id }})"
                        class="w-full flex items-center justify-between hover:opacity-80 transition"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 transition-transform {{ in_array($field->id, $expandedFields) ? 'rotate-90' : '' }}" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $field->name }}</h3>
                            <span class="text-sm text-gray-600">
                                ({{ $field->courses->count() }} ders)
                            </span>
                        </div>
                    </button>
                </div>

                <!-- Dersler -->
                @if(in_array($field->id, $expandedFields))
                    <div class="p-4 space-y-3">
                        @foreach($field->courses as $course)
                            <div class="border-l-4 border-blue-400 pl-4">
                                <button 
                                    wire:click="toggleCourse({{ $course->id }})"
                                    class="w-full flex items-center justify-between hover:opacity-80 transition mb-2"
                                >
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 transition-transform {{ in_array($course->id, $expandedCourses) ? 'rotate-90' : '' }}" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                        </svg>
                                        <span class="font-medium text-gray-800">{{ $course->name }}</span>
                                        <span class="text-sm text-gray-500">
                                            ({{ $course->topics->count() }} konu)
                                        </span>
                                    </div>
                                </button>

                                <!-- Konular -->
                                @if(in_array($course->id, $expandedCourses))
                                    <div class="pl-6 space-y-2">
                                        @foreach($course->topics as $topic)
                                            <div class="bg-gray-50 rounded-lg overflow-hidden">
                                                <button 
                                                    wire:click="toggleTopic({{ $topic->id }})"
                                                    class="w-full flex items-center justify-between p-3 hover:bg-gray-100 transition"
                                                >
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-3 h-3 transition-transform {{ in_array($topic->id, $expandedTopics) ? 'rotate-90' : '' }}" 
                                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                        <span class="font-medium text-gray-700">{{ $topic->name }}</span>
                                                        <span class="text-xs text-gray-500">
                                                            ({{ $topic->subTopics->count() }} alt konu)
                                                        </span>
                                                    </div>
                                                </button>

                                                <!-- Alt Konular -->
                                                @if(in_array($topic->id, $expandedTopics))
                                                    <div class="px-6 pb-3 space-y-1">
                                                        @foreach($topic->subTopics as $subTopic)
                                                            <div class="flex items-center gap-2 py-1 text-sm text-gray-600">
                                                                <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                                                </svg>
                                                                {{ $subTopic->name }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</div>

