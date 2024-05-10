<div>
    <div class="row">
        <div class="form-group">
            <select wire:model.live="businessSelect" class="form-control" title="Select Business to be Active">
                @foreach ($businesses as $data)
                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="d-flex justify-content-center items-align-center">
            <div class="overlay-wrapper mt-10 mb-10" wire:loading wire:target="businessSelect">
                <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
                    <div class="text-bold pt-2">Loading...</div>
                </div>
            </div>
        </div>
    </div>
</div>
