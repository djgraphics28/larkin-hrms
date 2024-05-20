<div>
    <form class="form-horizontal" wire:submit.prevent="update">
        <div class="form-group row">
            <label for="passport_number" class="col-sm-2 col-form-label">Passport Number</label>
            <div class="col-sm-10">
                <input wire:model="passport_number" type="text"
                    class="form-control @error ('passport_number') is-invalid @enderror" id="passport_number"
                    placeholder="Passport Number">
                @error('passport_number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="passport_expiry" class="col-sm-2 col-form-label">Passport Expiry Date</label>
            <div class="col-sm-10">
                <input wire:model="passport_expiry" type="date" class="form-control" id="passport_expiry">
            </div>
        </div>
        <br>
        <div class="form-group row">
            <label for="work_permit_number" class="col-sm-2 col-form-label">Work Permit Number</label>
            <div class="col-sm-10">
                <input wire:model="work_permit_number" type="text"
                    class="form-control @error ('work_permit_number') is-invalid @enderror" id="work_permit_number"
                    placeholder="Work Permit Number">
                @error('work_permit_number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="work_permit_expiry" class="col-sm-2 col-form-label">Work Permit Expiry Date</label>
            <div class="col-sm-10">
                <input wire:model="work_permit_expiry" type="date" class="form-control" id="work_permit_expiry">
            </div>
        </div>
        <br>
        <div class="form-group row">
            <label for="visa_number" class="col-sm-2 col-form-label">Visa Number</label>
            <div class="col-sm-10">
                <input wire:model="visa_number" type="text"
                    class="form-control @error ('visa_number') is-invalid @enderror" id="visa_number"
                    placeholder="Visa Number">
                @error('visa_number')visa_number
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="visa_expiry" class="col-sm-2 col-form-label">Visa Expiry Date</label>
            <div class="col-sm-10">
                <input wire:model="visa_expiry" type="date" class="form-control" id="visa_expiry">
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <div class="offset-sm-2 col-sm-10">
                <button wire:click="update" class="btn btn-primary float-right" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="update">Save Changes</span>
                    <span wire:loading wire:target="update">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="visually-hidden">Loading...</span>
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
