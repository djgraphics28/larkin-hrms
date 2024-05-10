<div>
    @forelse ($assets as $item)
        <div class="info-box col-md-3">
            <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">{{ $item->asset_code }}</span>
                <span class="info-box-number">{{ $item->name }}</span>
                <span class="info-box-text">{{ $item->asset_type->name }}</span>
            </div>
        </div>
    @empty
        <livewire:no-data-found />
    @endforelse

</div>
