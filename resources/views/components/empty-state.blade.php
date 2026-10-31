{{--
    Tampilan saat data kosong: ilustrasi kecil, judul, pesan ramah, dan tombol aksi (opsional).
    Pemakaian: <x-empty-state illustration="favorit" title="..." message="..." :action="route(...)" action-label="..." />
--}}
@props(['illustration' => 'kosong', 'title', 'message' => null, 'action' => null, 'actionLabel' => null, 'compact' => false])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center text-center ' . ($compact ? 'py-8' : 'py-12 px-4')]) }}>
    <img src="{{ asset('images/illustrations/empty-' . $illustration . '.svg') }}" alt="" class="{{ $compact ? 'h-24' : 'h-36' }} w-auto mb-5" loading="lazy">
    <p class="text-base font-semibold text-ink">{{ $title }}</p>
    @if ($message)
        <p class="mt-1.5 max-w-md text-sm text-muted">{{ $message }}</p>
    @endif
    {{ $slot }}
    @if ($action)
        <a href="{{ $action }}" class="btn-primary mt-5">{{ $actionLabel }}</a>
    @endif
</div>
