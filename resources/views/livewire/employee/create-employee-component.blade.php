<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Employee @if ($label != 'all')
                            ({{ ucfirst($label) }})
                        @endif
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a
                                href="{{ route('employee.index', $label) }}">{{ ucfirst($label) }} Employees</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            {{-- <form wire:submit.prevent="submit"> --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h4>Employee Basic Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="first_name">First Name</label><span class="text-danger">*</span>
                                        <input wire:model="first_name" wire:change="updateAccountName" type="text"
                                            class="form-control @error('first_name') is-invalid @enderror"
                                            id="first_name" placeholder="First name">
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="middle_name">Middle Name</label>
                                        <input wire:model="middle_name" type="text" class="form-control"
                                            id="middle_name" placeholder="Middle name">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="last_name">Last Name</label><span class="text-danger">*</span>
                                        <input wire:model="last_name" wire:change="updateAccountName" type="text"
                                            class="form-control  @error('last_name') is-invalid @enderror"
                                            id="last_name" placeholder="Last name">
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="ext_name">Extension Name</label>
                                        <input wire:model="ext_name" type="text" class="form-control" id="last_name"
                                            placeholder="Extension name">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="gender">Gender</label><span class="text-danger">*</span>
                                        <select wire:model="gender"
                                            class="form-control @error('gender') is-invalid @enderror" name="gender"
                                            id="gender">
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                        @error('gender')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="marital_status">Marital Status</label><span
                                            class="text-danger">*</span>
                                        <select wire:model="marital_status"
                                            class="form-control @error('marital_status') is-invalid @enderror"
                                            name="marital_status" id="marital_status">
                                            <option value="">Select Marital Status</option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Divorced">Divorced</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                        @error('marital_status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="birth_date">Birth Date</label><span class="text-danger">*</span>
                                        <input wire:model="birth_date" type="date"
                                            class="form-control @error('birth_date') is-invalid @enderror"
                                            id="birth_date">
                                        @error('birth_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="phone">Phone Number</label>
                                        <input wire:model="phone" type="text"
                                            class="form-control @error('phone') is-invalid @enderror" id="phone"
                                            placeholder="+058109">
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="email">Email Address</label>
                                        <input wire:model="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" id="email"
                                            placeholder="email@mail.com">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="address">Address</label>
                                        <input wire:model="address" type="text" class="form-control"
                                            id="address" placeholder="#123 address png">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-outline card-warning">
                            <div class="card-header">
                                <h4>Employee Company Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="employee_number">Employee Number</label><span
                                            class="text-danger">*</span>
                                        <input disabled wire:model="employee_number" type="text"
                                            class="form-control @error('employee_number') is-invalid @enderror"
                                            id="employee_number">
                                        @error('employee_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="joining_date">Joining Date</label><span
                                            class="text-danger">*</span>
                                        <input wire:model="joining_date" type="date"
                                            class="form-control @error('joining_date') is-invalid @enderror"
                                            id="joining_date">
                                        @error('joining_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="end_date">End Date</label>
                                        <input wire:model="end_date" type="date"
                                            class="form-control @error('end_date') is-invalid @enderror"
                                            id="end_date">
                                        @error('end_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="deployment_date_home_country">Deployment Date Home Country</label>
                                        <input wire:model="deployment_date_home_country" type="date"
                                            class="form-control" id="deployment_date_home_country">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="workshift">Workshift</label><span class="text-danger">*</span>
                                        <select wire:model="workshift" id="workshift"
                                            class="form-control @error('workshift') is-invalid @enderror">
                                            <option value="">Select Department</option>
                                            @foreach ($workshifts as $data)
                                                <option value="{{ $data->id }}">{{ $data->title }} -
                                                    {{ $data->number_of_hours }} Hours / Day</option>
                                            @endforeach
                                        </select>
                                        @error('workshift')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    @if ($label == 'all')
                                        <div class="form-group col-md-3">
                                            <label for="label">Employee Label</label><span
                                                class="text-danger">*</span>
                                            <select wire:model="label" id="label"
                                                class="form-control @error('label') is-invalid @enderror">
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
                                    @endif

                                    <div class="form-group col-md-3">
                                        <label for="designation">Position/Designation</label><span
                                            class="text-danger">*</span>
                                        <select wire:model="designation" id="designation"
                                            class="form-control @error('designation') is-invalid @enderror">
                                            <option value="">Select Position/Designation</option>
                                            @foreach ($designations as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('designation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="employee_status">Employee Status</label><span
                                            class="text-danger">*</span>
                                        <select wire:model="employee_status" id="employee_status"
                                            class="form-control @error('employee_status') is-invalid @enderror">
                                            <option value="">Select Employee Status</option>
                                            @foreach ($employeeStatuses as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('employee_status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="department">Department</label><span class="text-danger">*</span>
                                        <select wire:model="department" id="department"
                                            class="form-control @error('department') is-invalid @enderror">
                                            <option value="">Select Department</option>
                                            @foreach ($departments as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('department')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="salary_rate">Salary Rate / Hour</label><span
                                            class="text-danger">*</span>
                                        <input wire:model="salary_rate" type="text"
                                            class="form-control @error('salary_rate') is-invalid @enderror"
                                            id="salary_rate">
                                        @error('salary_rate')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="nasfund_number">NASFUND Number</label>
                                        <input wire:model="nasfund_number" type="text"
                                            class="form-control @error('nasfund_number') is-invalid @enderror"
                                            id="nasfund_number">
                                        @error('nasfund_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
                @if ($label != 'national')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-outline card-warning">
                                <div class="card-header">
                                    <h4>Work Visa, Permit & Passport Details</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="passport_number">Passport Number</label><span
                                                class="text-danger">*</span>
                                            <input wire:model="passport_number" type="text"
                                                class="form-control @error('passport_number') is-invalid @enderror"
                                                id="passport_number">
                                            @error('passport_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="passport_expiry">Passport Expiry Date</label><span
                                                class="text-danger">*</span>
                                            <input wire:model="passport_expiry" type="date"
                                                class="form-control @error('passport_expiry') is-invalid @enderror"
                                                id="passport_expiry">
                                            @error('passport_expiry')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="work_permit_number">Work Permit Number</label><span
                                                class="text-danger">*</span>
                                            <input wire:model="work_permit_number" type="text"
                                                class="form-control @error('work_permit_number') is-invalid @enderror"
                                                id="work_permit_number">
                                            @error('work_permit_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="work_permit_expiry">Work Permit Expiry Date</label><span
                                                class="text-danger">*</span>
                                            <input wire:model="work_permit_expiry" type="date"
                                                class="form-control @error('work_permit_expiry') is-invalid @enderror"
                                                id="work_permit_expiry">
                                            @error('work_permit_expiry')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="visa_number">Visa Number</label><span
                                                class="text-danger">*</span>
                                            <input wire:model="visa_number" type="text"
                                                class="form-control @error('visa_number') is-invalid @enderror"
                                                id="visa_number">
                                            @error('visa_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="visa_expiry">Visa Expiry Date</label><span
                                                class="text-danger">*</span>
                                            <input wire:model="visa_expiry" type="date"
                                                class="form-control @error('visa_expiry') is-invalid @enderror"
                                                id="visa_expiry">
                                            @error('visa_expiry')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                @endif

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="custom-control custom-switch">
                                    <input {{ $bankSelected ? 'checked' : '' }} type="checkbox"
                                        class="custom-control-input" id="bankSelected"
                                        wire:model.live="bankSelected">
                                    <label class="custom-control-label" for="bankSelected">Bank Details</label>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="account_name">Account Name</label>
                                        <input {{ !$bankSelected ? 'disabled' : '' }} wire:model="account_name" type="text"
                                            class="form-control"
                                            id="account_name">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="account_number">Account Number</label>
                                        <input {{ !$bankSelected ? 'disabled' : '' }} wire:model="account_number" type="text"
                                            class="form-control"
                                            id="account_number">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="bank_name">Bank Name</label>
                                        <input {{ !$bankSelected ? 'disabled' : '' }} wire:model="bank_name" type="text"
                                            class="form-control"
                                            id="bank_name">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="bsb_code">BSB Code</label>
                                        <input {{ !$bankSelected ? 'disabled' : '' }} wire:model="bsb_code" type="text"
                                            class="form-control"
                                            id="bsb_code">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <button wire:click="submit(1)" class="btn btn-primary mr-2" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="submit(1)">SAVE</span>
                                    <span wire:loading wire:target="submit(1)">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        <span class="visually-hidden">Loading...</span>
                                    </span>
                                </button>

                                <button wire:click="submit(2)" class="btn btn-info mr-2" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="submit(2)">SAVE & CREATE NEW</span>
                                    <span wire:loading wire:target="submit(2)">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        <span class="visually-hidden">Loading...</span>
                                    </span>
                                </button>
                                <a href="{{ route('employee.index', $label) }}"
                                    class="btn btn-default float-right mr-2">CANCEL</a>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- </form> --}}
        </div><!-- /.container-fluid -->
    </section>
</div>
