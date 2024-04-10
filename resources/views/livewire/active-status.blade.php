<div>
    <div class="form-check form-switch">
        <input class="form-check-input" wire:model.lazy="is_active" type="checkbox" role="switch"
            @if ($is_active) checked @endif wire:ignore.self>
    </div>
</div>
