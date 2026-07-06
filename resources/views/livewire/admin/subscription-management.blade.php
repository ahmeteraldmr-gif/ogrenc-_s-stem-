<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Koç Erişim Yetkileri</h2>
            <p class="text-sm text-gray-600 mt-1">Koçların sisteme erişim izinlerini ve bitiş tarihlerini yönetin</p>
        </div>
    </div>

    <!-- Kurum Abonelik Bilgilendirme Kartı -->
    @php
        $mySub = auth()->user()->subscription;
    @endphp
    @if($mySub)
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-3">
                <div class="p-2.5 bg-blue-600 text-white rounded-lg shadow-sm">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-blue-950">Kurum Lisans Bilgisi</h3>
                    <p class="text-xs text-blue-800 font-medium">Sistem kullanım lisansınız <strong>{{ $mySub->end_date->format('d.m.Y') }}</strong> tarihine kadar geçerlidir.</p>
                </div>
            </div>
            @php
                $remainingDays = (int) now()->diffInDays($mySub->end_date, false);
            @endphp
            @if($remainingDays <= 15)
                <span class="px-3.5 py-1.5 bg-orange-600 text-white text-xs font-bold rounded-full shadow-sm {{ $remainingDays <= 5 ? 'animate-pulse' : '' }}">
                    Son {{ max(0, $remainingDays) }} gün! Yenileme için sistem yöneticinizle iletişime geçin.
                </span>
            @else
                <span class="px-3.5 py-1.5 bg-green-600 text-white text-xs font-bold rounded-full shadow-sm">
                    Sistem Aktif ({{ $remainingDays }} gün kaldı)
                </span>
            @endif
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="card border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-600 text-white rounded-xl p-3 shadow-sm">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <dt class="text-sm font-bold text-gray-700">Toplam Tanımlı Koç</dt>
                    <dd class="text-2xl font-extrabold text-blue-600 mt-1">{{ $stats['total'] }}</dd>
                </div>
            </div>
        </div>

        <div class="card border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-600 text-white rounded-xl p-3 shadow-sm">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <dt class="text-sm font-bold text-gray-700">Aktif Abonelik</dt>
                    <dd class="text-2xl font-extrabold text-green-600 mt-1">{{ $stats['active'] }}</dd>
                </div>
            </div>
        </div>

        <div class="card border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-600 text-white rounded-xl p-3 shadow-sm">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <dt class="text-sm font-bold text-gray-700">Deneme Sürümü</dt>
                    <dd class="text-2xl font-extrabold text-purple-600 mt-1">{{ $stats['trial'] }}</dd>
                </div>
            </div>
        </div>

        <div class="card border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-orange-600 text-white rounded-xl p-3 shadow-sm">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <dt class="text-sm font-bold text-gray-700">Yakında Bitecek</dt>
                    <dd class="text-2xl font-extrabold text-orange-600 mt-1">{{ $stats['expiring_soon'] }}</dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Koç adı veya e-posta ile ara..."
                    class="input-field"
                >
            </div>
            <div>
                <select wire:model.live="filterPlan" class="input-field">
                    <option value="">Tüm Paketler</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select wire:model.live="filterStatus" class="input-field">
                    <option value="">Tüm Durumlar</option>
                    <option value="1">Aktif</option>
                    <option value="0">Pasif</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Subscriptions Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Koç
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Erişim Yetkisi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Başlangıç
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Bitiş
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Durum
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            İşlemler
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subscriptions as $subscription)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-accent-blue flex items-center justify-center text-white font-medium">
                                            {{ substr($subscription->user->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $subscription->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $subscription->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $subscription->plan->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $subscription->start_date->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $subscription->end_date->format('d.m.Y') }}</div>
                                @if($subscription->is_active && $subscription->end_date->diffInDays(now()) <= 7)
                                    <span class="text-xs text-orange-600">{{ $subscription->end_date->diffInDays(now()) }} gün kaldı</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $subscription->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $subscription->is_active ? 'Aktif' : 'Pasif' }}
                                    </span>
                                    @if($subscription->is_trial)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Deneme
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="editSubscription({{ $subscription->id }})" class="text-blue-600 hover:text-blue-900">
                                    Düzenle
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Abonelik bulunmamaktadır.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $subscriptions->links() }}
        </div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center" wire:click="closeModal">
            <div class="relative mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white" wire:click.stop>
                <div class="flex items-center justify-between pb-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">Abonelik Düzenle</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveSubscription" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Paket *</label>
                        <select wire:model="subscription_plan_id" class="input-field">
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </select>
                        @error('subscription_plan_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bitiş Tarihi *</label>
                        <input type="date" wire:model="end_date" class="input-field">
                        @error('end_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
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
                        @error('is_active') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
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
