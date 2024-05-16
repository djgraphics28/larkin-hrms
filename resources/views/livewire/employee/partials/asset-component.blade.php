<div>
    @forelse ($assets as $item)
        <div class="info-box col-md-3" style="cursor: pointer">
            <span class="info-box-icon bg-info w-10"><i class="fa fa-microchip"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Code: {{ $item->asset_code }}</span>
                <span class="info-box-number">Name: {{ $item->name }}</span>
                <span class="info-box-text">Category: {{ $item->asset_type->name }}</span>
                <span class="info-box-text">Status: @php
                    switch ($item->is_working) {
                        case 'yes':
                            $statusLabel = 'Working';
                            $badgeClass = 'badge-success';
                            break;
                        case 'no':
                            $statusLabel = 'Not Working';
                            $badgeClass = 'badge-danger';
                            break;
                        case 'maintenance':
                            $statusLabel = 'Maintenance';
                            $badgeClass = 'badge-warning';
                            break;
                        default:
                            $statusLabel = 'Unknown';
                            $badgeClass = 'badge-secondary';
                    }
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span></span>
                <span class="info-box-text">Date Received: {{ $item->date_received }}</span>
            </div>
        </div>
    @empty
        <livewire:no-data-found />
    @endforelse

</div>
