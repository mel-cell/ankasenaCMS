<div class="flex flex-col gap-2">
    <div class="flex items-center justify-between">
        <h2 class="text-sm font-semibold text-gray-950 dark:text-white">Analitik pemilu penuh</h2>
        <a href="{{ $url }}" target="_blank" rel="noopener" class="text-sm font-semibold text-primary-600 transition hover:underline">
            Buka tab penuh ↗
        </a>
    </div>
    <iframe src="{{ $url }}" title="Analitik pemilu" loading="lazy"
            style="width:100%;height:860px;border:1px solid rgba(0,0,0,.1);border-radius:14px;background:transparent"></iframe>
</div>
