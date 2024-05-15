<div>
    <div class="form-group d-flex justify-content-center">
        <div class="form-check form-switch">
            <input class="form-check-input" wire:model.lazy="is_active" type="checkbox" role="switch"
                @if ($is_active) checked @endif>
        </div>
    </div>
</div>
