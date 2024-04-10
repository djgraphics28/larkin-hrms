<div>
    <div class="row">
        <div class="form-group">
            <select class="form-control" wire:model="branch">
                @foreach ($branches as $data)
                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
