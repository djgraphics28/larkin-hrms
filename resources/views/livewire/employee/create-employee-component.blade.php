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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
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
            <form wire:submit.prevent="submit">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h4>Employee Basic Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="first_name">First Name</label>
                                        <input wire:model="first_name" type="text" class="form-control"
                                            id="first_name" placeholder="First name">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="middle_name">Middle Name</label>
                                        <input wire:model="middle_name" type="text" class="form-control"
                                            id="middle_name" placeholder="Middle name">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="last_name">Last Name</label>
                                        <input wire:model="last_name" type="text" class="form-control" id="last_name"
                                            placeholder="Last name">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="ext_name">Extension Name</label>
                                        <input wire:model="ext_name" type="text" class="form-control" id="last_name"
                                            placeholder="Extension name">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="gender">Gender</label>
                                        <select wire:model="gender" class="form-control" name="gender" id="gender">
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="marital_status">Marital Status</label>
                                        <select wire:model="marital_status" class="form-control" name="marital_status"
                                            id="marital_status">
                                            <option value="">Select Marital Status</option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Divorced">Divorced</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="birth_date">Birth Date</label>
                                        <input wire:model="birth_date" type="date" class="form-control"
                                            id="birth_date">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="phone">Phone Number</label>
                                        <input wire:model="phone" type="text" class="form-control" id="phone"
                                            placeholder="+058109">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="email">Email Address</label>
                                        <input wire:model="email" type="email" class="form-control" id="email"
                                            placeholder="email@mail.com">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="address">Address</label>
                                        <input wire:model="address" type="text" class="form-control" id="address"
                                            placeholder="#123 address png">
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
                                        <label for="employee_number">Employee Number</label>
                                        <input wire:model="employee_number" type="text" class="form-control"
                                            id="employee_number">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="joining_date">Joining Date</label>
                                        <input wire:model="joining_date" type="date" class="form-control"
                                            id="joining_date">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="end_date">End Date</label>
                                        <input wire:model="end_date" type="date" class="form-control"
                                            id="end_date">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="deployment_date_home_country">Deployment Date Home Country</label>
                                        <input wire:model="deployment_date_home_country" type="date"
                                            class="form-control" id="deployment_date_home_country">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="workshift_id">Workshift</label>
                                        <select wire:model="workshift_id" id="workshift_id" class="form-control">
                                            <option value="">Select Department</option>
                                            @foreach ($workshifts as $data)
                                                <option value="{{ $data->id }}">{{ $data->title }} -
                                                    {{ $data->number_of_hours }} Hours / Day</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($label == 'all')
                                        <div class="form-group col-md-3">
                                            <label for="label">Employee Label</label>
                                            <select wire:model="label" name="" id="label"
                                                class="form-control">
                                                <option value="">Select Label</option>
                                                <option value="National">National</option>
                                                <option value="Expatriate">Expatriate</option>
                                            </select>
                                        </div>
                                    @endif

                                    <div class="form-group col-md-3">
                                        <label for="designation_id">Position/Designation</label>
                                        <select wire:model="designation_id" id="designation_id" class="form-control">
                                            <option value="">Select Position/Designation</option>
                                            @foreach ($designations as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="employee_status_id">Employee Status</label>
                                        <select wire:model="employee_status_id" id="employee_status_id"
                                            class="form-control">
                                            <option value="">Select Employee Status</option>
                                            @foreach ($employeeStatuses as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="department_id">Department</label>
                                        <select wire:model="department_id" id="department_id" class="form-control">
                                            <option value="">Select Department</option>
                                            @foreach ($departments as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="salary_rate">Salary Rate / Hour</label>
                                        <input wire:model="salary_rate" type="text" class="form-control"
                                            id="salary_rate">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="nasfund_number">NASFUND Number</label>
                                        <input wire:model="nasfund_number" type="text" class="form-control"
                                            id="nasfund_number">
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
                                            <label for="passport_number">Passport Number</label>
                                            <input wire:model="passport_number" type="text" class="form-control"
                                                id="employee_number">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="passport_expiry">Passport Expiry Date</label>
                                            <input wire:model="passport_expiry" type="date" class="form-control"
                                                id="joining_date">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="work_permit_number">Work Permit Number</label>
                                            <input wire:model="work_permit_number" type="text"
                                                class="form-control" id="work_permit_number">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="work_permit_expiry">Work Permit Expiry Date</label>
                                            <input wire:model="work_permit_expiry" type="date"
                                                class="form-control" id="work_permit_expiry">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="visa_number">Visa Number</label>
                                            <input wire:model="visa_number" type="text" class="form-control"
                                                id="visa_number">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="visa_expiry">Visa Expiry Date</label>
                                            <input wire:model="visa_expiry" type="date" class="form-control"
                                                id="visa_expiry">
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
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary float-right">SAVE NEW EMPLOYEE</button>
                                <a href="{{ route('employee.index', $label) }}"
                                    class="btn btn-default float-right mr-2">CANCEL</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div><!-- /.container-fluid -->
    </section>
</div>
