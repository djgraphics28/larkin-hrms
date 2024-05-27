<div>
    @forelse ($records as $index => $record)
        <div class="card card-outline card-primary mb-3 p-3">
            <div class="row">
                <div class="col-md-1">
                    <div class="icheck-primary d-inline"><input id="is_active{{ $index }}" type="checkbox"
                        wire:model="records.{{ $index }}.is_active"><label for="is_active{{ $index }}"></div>
                </div>
                <div class="col-md-11">
                    <div class="form-group row">
                        <label for="account_name_{{ $index }}" class="col-sm-2 col-form-label">Account Name</label>
                        <div class="col-sm-10">
                            <input wire:model="records.{{ $index }}.account_name" type="text"
                                class="form-control" id="account_name_{{ $index }}" placeholder="Account Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="account_number_{{ $index }}" class="col-sm-2 col-form-label">Account
                            Number</label>
                        <div class="col-sm-10">
                            <input wire:model="records.{{ $index }}.account_number" type="text"
                                class="form-control" id="account_number_{{ $index }}" placeholder="00000000000">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="bank_name_{{ $index }}" class="col-sm-2 col-form-label">Bank Name</label>
                        <div class="col-sm-10">
                            <input wire:model="records.{{ $index }}.bank_name" type="text"
                                class="form-control" id="bank_name_{{ $index }}" placeholder="Bank Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="bsb_code_{{ $index }}" class="col-sm-2 col-form-label">BSB Code</label>
                        <div class="col-sm-10">
                            <input data-mask data-inputmask="&quot;mask&quot;: &quot;999-999&quot;" wire:model="records.{{ $index }}.bsb_code" type="text"
                                class="form-control" id="bsb_code_{{ $index }}" placeholder="BSB Code">
                        </div>
                    </div>
                </div>

            </div>


            @if ($index > 0)
                <button wire:click="alertConfirm({{ $index }})" type="button" class="btn btn-danger">Remove
                    Bank</button>
            @endif
        </div>
    @empty
    @endforelse
    <div class="form-group row">
        <div class="offset-sm-2 col-sm-10">
            <button wire:click="submit" class="btn btn-primary float-right" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="submit">Save Changes</span>
                <span wire:loading wire:target="submit">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span class="visually-hidden">Loading...</span>
                </span>
            </button>
            @if (count($records) == 1)
                <a wire:click="addBank" class="btn btn-success float-right mr-2">Add 2nd Bank</a>
            @endif

        </div>
    </div>
</div>

<script>
    $(function() {
        $('#bsb_code_0').on('change', function(e) {
            var data = $(this).val();
            @this.set('records.0.bsb_code', data);
        });

        $('#bsb_code_1').on('change', function(e) {
            var data = $(this).val();
            @this.set('records.0.bsb_code', data);
        });
    });
</script>
