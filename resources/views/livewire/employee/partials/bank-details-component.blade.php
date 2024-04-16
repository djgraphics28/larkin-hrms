<div>
    <form class="form-horizontal" wire:submit.prevent="submit">
        @forelse ($records as $index => $record)
            <div class="card card-outline card-primary mb-3 p-3">
                <div class="form-group row">
                    <label for="account_name_{{ $index }}" class="col-sm-2 col-form-label">Account Name</label>
                    <div class="col-sm-10">
                        <input wire:model="records.{{ $index }}.account_name" type="text" class="form-control"
                            id="account_name_{{ $index }}" placeholder="Account Name">
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
                        <input wire:model="records.{{ $index }}.bank_name" type="text" class="form-control"
                            id="bank_name_{{ $index }}" placeholder="Bank Name">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="bsb_code_{{ $index }}" class="col-sm-2 col-form-label">BSB Code</label>
                    <div class="col-sm-10">
                        <input wire:model="records.{{ $index }}.bsb_code" type="text" class="form-control"
                            id="bsb_code_{{ $index }}" placeholder="BSB Code">
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
                <button type="submit" class="btn btn-primary float-right">Save Changes</button>
                @if (count($records) == 1)
                    <a wire:click="addBank" class="btn btn-success float-right mr-2">Add 2nd Bank</a>
                @endif

            </div>
        </div>
    </form>
</div>
