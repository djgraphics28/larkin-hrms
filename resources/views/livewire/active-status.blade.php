<div>
    <div class="form-group">
        <div class="custom-control custom-switch">
            <input @if ($is_active) checked @endif type="checkbox" role="switch" class="custom-control-input" wire:model.lazy="is_active"
                id="status{{ $model }}">
            <label class="custom-control-label" for="status{{ $model }}"></label>
        </div>
    </div>
</div>
