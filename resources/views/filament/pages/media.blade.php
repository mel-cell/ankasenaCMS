<x-filament-panels::page>
    <div class="flex flex-wrap items-end gap-3 mb-4">
        <div class="grow max-w-md">
            <input type="search" wire:model.live.debounce.400ms="filters.q" placeholder="Cari nama berkas…"
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm">
        </div>
        <select wire:model.live="filters.tipe" class="rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm">
            <option value="semua">Semua tipe</option>
            <option value="gambar">Gambar</option>
            <option value="lain">Bukan gambar</option>
        </select>
        <div class="ms-auto">
            <label class="fi-btn fi-btn-size-md fi-btn-color-primary fi-btn-icon-left cursor-pointer gap-2 inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-semibold">
                <x-filament::icon icon="heroicon-o-arrow-up-tray" class="w-4 h-4"/>
                Unggah berkas
                <input type="file" wire:model="file" class="hidden" dusk="media-upload">
            </label>
            <p wire:loading wire:target="file" class="text-sm text-gray-500 mt-1">Mengunggah…</p>
            @error('file') <p class="text-sm text-danger-500 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <p class="text-sm text-gray-500 mb-3">{{ count($this->files()) }} berkas</p>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
        @forelse ($this->files() as $f)
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden flex flex-col">
                <a href="{{ $f['url'] }}" target="_blank" rel="noopener" class="block aspect-4/3 bg-gray-100 dark:bg-gray-800">
                    @if (in_array($f['ext'], ['png','jpg','jpeg','gif','webp','svg','avif']))
                        <img src="{{ $f['url'] }}" alt="{{ basename($f['path']) }}" loading="lazy" class="w-full h-full object-cover">
                    @else
                        <span class="w-full h-full grid place-items-center text-2xl font-bold uppercase text-gray-400">{{ $f['ext'] ?: '?' }}</span>
                    @endif
                </a>
                <div class="p-2.5 flex flex-col gap-1.5 grow">
                    <p class="text-xs font-medium truncate" title="{{ basename($f['path']) }}">{{ basename($f['path']) }}</p>
                    <p class="text-[11px] text-gray-500">{{ number_format($f['size'] / 1024, 0) }} KB · {{ date('d/m/Y H:i', $f['modified']) }}</p>
                    <div class="mt-auto flex gap-1.5">
                        <button type="button" onclick="navigator.clipboard.writeText(this.dataset.url).then(() => this.textContent = '✓ Tersalin')" data-url="{{ $f['url'] }}"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 dark:border-gray-700 px-2 py-1 text-xs font-medium">
                            <x-filament::icon icon="heroicon-o-clipboard-document" class="w-3.5 h-3.5"/> URL
                        </button>
                        <button type="button" wire:click="hapus('{{ $f['path'] }}')" wire:confirm="Hapus {{ basename($f['path']) }}?"
                                class="fi-btn fi-btn-size-sm fi-btn-color-danger inline-flex items-center rounded-lg border border-danger-200 dark:border-danger-800 px-2 py-1 text-xs font-medium text-danger-600 dark:text-danger-400">
                            <x-filament::icon icon="heroicon-o-trash" class="w-3.5 h-3.5"/>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-sm text-gray-500 py-10 text-center">Belum ada berkas. Unggah yang pertama.</div>
        @endforelse
    </div>
</x-filament-panels::page>
