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
                        <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a wire:navigate
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
                                @if ($image)
                                    <img class="profile-user-img img-fluid img-circle w-150" src="{{ $image->temporaryUrl() }}"
                                        alt="User profile picture">
                                @else
                                    <img class="profile-user-img img-fluid img-circle w-150"
                                        src="{{ asset('assets/images/' . ($gender == 'Male' ? 'male.png' : 'female.png')) }}"
                                        alt="User profile picture">
                                @endif
                            </div>

                            <h3 class="profile-username text-center">{{ $fullName }}</h3>
                            <h5 class="text-center">{{ $age }} years old</h5>

                            <p class="text-muted text-center">{{ $position }}</p>
                            <input type="file" wire:model="image" class="form-control" title="upload image">
                            @error('image') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button wire:click="uploadImage({{ $id }})" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->
                    {{-- <div class="card card-primary">
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
                    </div> --}}
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#basic" data-toggle="tab">Basic
                                        Info</a></li>

                                <li class="nav-item"><a class="nav-link" href="#work_info" data-toggle="tab">Work
                                        Info</a>
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
                                    @livewire('employee.partials.bank-details-component', ['id' => $id])
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="documents">

                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="leaves">
                                    @livewire('employee.partials.leaves-component', ['id' => $id])
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

@push('script')
    <script>
        $(document).ready(function() {

            $("#profileImage").click(function(e) {
                $("#imageUpload").click();
            });

            function fasterPreview(uploader) {
                if (uploader.files && uploader.files[0]) {
                    $('#profileImage').attr('src',
                        window.URL.createObjectURL(uploader.files[0]));
                }
            }

            $("#imageUpload").change(function() {
                fasterPreview(this);
            });

            /* datepicker initialization */
            $('#datepicker').datepicker({
                autoclose: true,
            }); /* datepicker initialization */

        });
    </script>
@endpush
