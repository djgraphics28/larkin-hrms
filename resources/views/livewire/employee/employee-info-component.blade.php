<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Employee Info @if ($label != 'all')
                            ({{ ucfirst($label) }})
                        @endif
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a
                                href="{{ route('employee.index', $label) }}">{{ ucfirst($label) }} Employees</a></li>
                        <li class="breadcrumb-item active">Info</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle w-150"
                                    src="{{ $image == '' ? ($gender == 'Male' ? asset('assets/images/male.png') : asset('assets/images/female.png')) : $image }}"
                                    alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">{{ $fullName }}</h3>
                            <h5 class="text-center">{{ $age }} years old</h5>

                            <p class="text-muted text-center">{{ $position }}</p>
                            <button class="btn btn-primary btn-block"><b>Change Photo</b></button>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">About Me</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fas fa-book mr-1"></i> Education</strong>

                            <p class="text-muted">
                                B.S. in Computer Science from the University of Tennessee at Knoxville
                            </p>

                            <hr>

                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                            <p class="text-muted">Malibu, California</p>

                            <hr>

                            <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                            <p class="text-muted">
                                <span class="tag tag-danger">UI Design</span>
                                <span class="tag tag-success">Coding</span>
                                <span class="tag tag-info">Javascript</span>
                                <span class="tag tag-warning">PHP</span>
                                <span class="tag tag-primary">Node.js</span>
                            </p>

                            <hr>

                            <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam
                                fermentum enim neque.</p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#basic" data-toggle="tab">Basic
                                        Info</a></li>

                                <li class="nav-item"><a class="nav-link" href="#work_info" data-toggle="tab">Work Info</a>
                                <li class="nav-item"><a class="nav-link" href="#salary_history" data-toggle="tab">Salary
                                        History</a>
                                </li>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#bank_details" data-toggle="tab">Bank
                                        Details</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#documents"
                                        data-toggle="tab">Documents</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#leaves" data-toggle="tab">Leaves</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#attendance_logs"
                                        data-toggle="tab">Attendance Logs</a>
                                </li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="basic">
                                    @livewire('employee.partials.basic-info-component', ['id' => $id])
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="work_info">
                                    @livewire('employee.partials.work-info-component', ['id' => $id])

                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="salary_history">
                                    @livewire('employee.partials.salary-history-component', ['id' => $id])
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="bank_details">
                                    <form class="form-horizontal" wire:ignore>
                                        <div class="form-group row">
                                            <label for="account_name" class="col-sm-2 col-form-label">Account
                                                Name</label>
                                            <div class="col-sm-10">
                                                <input wire:model="account_name" type="text" class="form-control"
                                                    id="account_name" placeholder="Account Name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="account_number" class="col-sm-2 col-form-label">Account
                                                Number</label>
                                            <div class="col-sm-10">
                                                <input wire:model="account_number" type="text"
                                                    class="form-control" id="account_number"
                                                    placeholder="00000000000">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="bank_name" class="col-sm-2 col-form-label">Bank Name</label>
                                            <div class="col-sm-10">
                                                <input wire:model="bank_name" type="text" class="form-control"
                                                    id="bank_name" placeholder="Bank Name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="bsb_code" class="col-sm-2 col-form-label">BSB Code</label>
                                            <div class="col-sm-10">
                                                <input wire:model="bsb_code" type="text" class="form-control"
                                                    id="bsb_code" placeholder="BSB Code">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary float-right">Save
                                                    Changes</button>
                                                <button type="submit" class="btn btn-success float-right mr-2">ADD
                                                    NEW BANK</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="documents">
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="leaves">
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="attendance_logs">
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
</div>
