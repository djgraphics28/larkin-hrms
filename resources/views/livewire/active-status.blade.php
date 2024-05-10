<div>
    <div class="form-group">
        {{-- <div class="form-check form-switch">
            <input class="form-check-input" wire:model.lazy="is_active" type="checkbox" role="switch"
                @if ($is_active) checked @endif>
        </div> --}}
        <div class="custom-control custom-switch">
            <input @if ($is_active) checked @endif type="checkbox" role="switch" class="custom-control-input" wire:model.lazy="is_active"
                id="status">
            <label class="custom-control-label" for="status"></label>
        </div>
    </div>
</div>
