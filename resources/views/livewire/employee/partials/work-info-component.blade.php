<div>
    <form class="form-horizontal" wire:submit.prevent="update">
        <div class="form-group row">
            <label for="employee_number" class="col-sm-2 col-form-label">Employee Number</label>
            <div class="col-sm-10">
                <input wire:model="employee_number" type="text"
                    class="form-control @error('employee_number') is-invalid @enderror" id="employee_number"
                    placeholder="Employee Number">
                @error('employee_number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="label" class="col-sm-2 col-form-label">Employee Label</label>
            <div class="col-sm-10">
                <select wire:model="label" id="label" class="form-control @error('label') is-invalid @enderror">
                    <option value="">Select Label</option>
                    <option value="National">National</option>
                    <option value="Expatriate">Expatriate</option>
                </select>
                @error('label')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="employee_status" class="col-sm-2 col-form-label">Employee Status</label>
            <div class="col-sm-10">
                <select wire:model="employee_status" id="employee_status"
                    class="form-control @error('employee_status') is-invalid @enderror">
                    <option value="">Select Employee Status</option>
                    @foreach ($employeeStatuses as $employee_status)
                        <option value="{{ $employee_status->id }}">{{ $employee_status->name }} </option>
                    @endforeach
                </select>
                @error('employee_status')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="department" class="col-sm-2 col-form-label">Department</label>
            <div class="col-sm-10">
                <select wire:model="department" id="department"
                    class="form-control @error('department') is-invalid @enderror">
                    <option value="">Select Department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('department')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="workshift" class="col-sm-2 col-form-label">Workshift</label>
            <div class="col-sm-10">
                <select wire:model="workshift" id="workshift"
                    class="form-control @error('workshift') is-invalid @enderror">
                    <option value="">Select Workshift</option>
                    @foreach ($workshifts as $workshift)
                        <option value="{{ $workshift->id }}">{{ $workshift->title }} |
                            {{ \Carbon\Carbon::parse($workshift->start)->format('h:i A') }}
                            -
                            {{ \Carbon\Carbon::parse($workshift->end)->format('h:i A') }}</option>
                    @endforeach
                </select>
                @error('workshift')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="joining_date" class="col-sm-2 col-form-label">Joining Date</label>
            <div class="col-sm-10">
                <input wire:model="joining_date" type="date"
                    class="form-control @error('joining_date') is-invalid @enderror" id="joining_date">
            </div>
            @error('joining_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group row">
            <label for="end_date" class="col-sm-2 col-form-label">End Date <small>(optional)</small></label>
            <div class="col-sm-10">
                <input wire:model="end_date" type="date" class="form-control @error('end_date') is-invalid @enderror"
                    id="end_date">
                @error('end_date')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="deployment_date_home_country" class="col-sm-2 col-form-label">Deployment Date Home
                Country <small>(optional)</small></label>
            <div class="col-sm-10">
                <input wire:model="deployment_date_home_country" type="date" class="form-control"
                    id="deployment_date_home_country">
            </div>
        </div>
        <div class="form-group row">
            <label for="nasfund_number" class="col-sm-2 col-form-label">Nasfund Number <small>(optional)</small></label>
            <div class="col-sm-10">
                <input wire:model="nasfund_number" type="text"
                    class="form-control @error('nasfund_number') is-invalid @enderror" id="nasfund_number">
                @error('nasfund_number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

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
