<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ ucfirst($label) }} Employees</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ ucfirst($label) }} Employees</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">

                    <div class="row">
                        <div class="col-md-1">
                            <div class="form-group">
                                <select class="form-control form-control" wire:model.live="perPage">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control form-control" placeholder="Search term here"
                                    wire:model.live.debounce.500="search">
                            </div>
                        </div>

                        @if ($label == 'all')
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select class="form-control" wire:model.live="sortByLabel">
                                        <option value="">All Labels</option>
                                        <option value="National">National</option>
                                        <option value="Expatriate">Expatriate</option>
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control" wire:model.live="sortByDepartment">
                                    <option value="">All Departments</option>
                                    @foreach ($departments as $data)
                                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control" wire:model.live="sortByDesignation">
                                    <option value="">All Positions</option>
                                    @foreach ($designations as $data)
                                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control" wire:model.live="sortByEmployeeStatus">
                                    <option value="">All Status</option>
                                    @foreach ($employeeStatuses as $data)
                                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">

                            <div class="btn-group float-right" role="group" aria-label="Groups">
                                <button wire:click="openImportModal()" type="button"
                                    class="btn btn-warning btn-md mr-2"><i class="fa fa-upload" aria-hidden="true"></i>
                                    Import</button>
                                <button wire:click="export()" type="button" class="btn btn-success btn-md mr-2"><i
                                        class="fa fa-file-excel" aria-hidden="true"></i> Export</button>
                                <button type="button" class="btn btn-danger btn-md mr-2"><i class="fa fa-file-pdf"
                                        aria-hidden="true"></i> PDF</button>
                                <a wire:navigate href="{{ route('employee.create', $label) }}" type="button"
                                    class="btn btn-primary btn-md mr-2"><i class="fa fa-plus" aria-hidden="true"></i>
                                    Add New</a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            {{ $records->links() }}
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-condensed table-sm table-hover">
                                <thead class="table-info">
                                    <tr>
                                        <th width="3%" class="text-start"><input width="3%" type="checkbox"
                                                wire:model.live="selectAll"></th>
                                        <th width="7%"class="text-center">IMAGE</th>
                                        <th width="5%"class="text-center">EMP NO</th>
                                        <th class="text-start">EMPLOYEE DETAILS</th>
                                        <th class="text-center">POSITION</th>
                                        <th class="text-center">WORKSHIFT</th>
                                        <th class="text-center">DEPARTMENT</th>
                                        <th class="text-center">STATUS</th>
                                        <th class="text-center">DEFAULT PAY</th>
                                        <th class="text-start">NOTES/REMARKS</th>
                                        <th class="text-center">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="11" class="text-center align-items-center">
                                            <div wire:loading
                                                wire:target="search,sortByEmployeeStatus,sortByDesignation,sortByLabel,sortByDepartment,perPage">
                                                <livewire:table-loader />
                                            </div>
                                        </td>
                                    </tr>
                                    @forelse ($records as $data)
                                        <tr wire:key="search-{{ $data->id }}">
                                            <td width="3%"
                                                class="text-start align-middle {{ $data->is_discontinued ? 'bg-danger' : '' }}">
                                                <input type="checkbox" wire:model.prevent="selectedRows"
                                                    value="{{ $data->id }}"><strong>{{ $data->is_discontinued ? ' D' : '' }}</strong>
                                            </td>

                                            <td class="text-center align-middle p-0"><img class="w-50 h-50"
                                                    src="{{ $data->gender == 'Male' ? asset('assets/images/male.png') : asset('assets/images/female.png') }}"
                                                    alt="Profile Picture"></td>
                                            <td class="text-center align-middle"><a wire:navigate
                                                    href="{{ route('employee.info', ['label' => $label, 'id' => $data->id]) }}"
                                                    href="javascript:void(0)">{{ $data->employee_number }}</a></td>
                                            <td class="text-start align-middle"><strong>{{ $data->first_name }}
                                                    {{ $data->last_name }}</strong>
                                                <br><small>Email: {{ $data->email }}</small>
                                                <br><small>Contact: {{ $data->phone }}</small>
                                            </td>
                                            <td class="text-center align-middle">{{ $data->designation->name }}</td>
                                            <td class="text-center align-middle">
                                                {{ $data->workshift->title }} <br>
                                                <small>{{ \Carbon\Carbon::parse($data->workshift->start)->format('h:i A') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($data->workshift->end)->format('h:i A') }}</small>
                                            </td>
                                            <td class="text-center align-middle">{{ $data->department->name }}</td>
                                            <td class="text-center align-middle">{{ $data->employee_status->name }}
                                            </td>
                                            <td class="text-center align-middle">
                                                @if ($data->default_pay_method == 'cash')
                                                    <span class="badge badge-success">
                                                        <i class="fa fa-money"></i>
                                                        {{ ucfirst($data->default_pay_method) }}
                                                    </span>
                                                @elseif($data->default_pay_method == 'bank')
                                                    <span class="badge badge-warning">
                                                        <i class="fa fa-bank"></i>
                                                        {{ ucfirst($data->default_pay_method) }}
                                                    </span>
                                                @else
                                                    {{ ucfirst($data->default_pay_method) }}
                                                @endif
                                            </td>
                                            <td width="15%" class="text-start align-middle">
                                                <ul>
                                                    @forelse ($data->employee_notes as $emp)
                                                        <li><small>{{ $emp->notes }}</small></li>
                                                    @empty
                                                    @endforelse
                                                </ul>
                                            </td>
                                            <td width="10%" class="text-center align-middle">
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-default btn-sm">Action</button>
                                                    <button type="button"
                                                        class="btn btn-default btn-sm dropdown-toggle dropdown-icon"
                                                        data-toggle="dropdown">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">
                                                        <a title="Show Employee Info" wire:navigate
                                                            class="dropdown-item"
                                                            href="{{ route('employee.info', ['label' => $label, 'id' => $data->id]) }}"><i
                                                                class="fa fa-edit"></i> Edit</a>
                                                        <a title="Delete this Employee"
                                                            wire:click="alertConfirm({{ $data->id }})"
                                                            class="dropdown-item" href="#"><i
                                                                class="fa fa-trash"></i> Delete</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="fa fa-download"></i> Final Pay</a>
                                                        <a class="dropdown-item" href="#"><i
                                                            class="fa fa-print"></i>Print Summary of
                                                            Earnings</a>
                                                        <a class="dropdown-item" href="#"><i
                                                            class="fa fa-print"></i>Print Contract</a>
                                                        <a class="dropdown-item" href="#"><i
                                                            class="fa fa-print"></i>Print
                                                            Attendance</a>
                                                    </div>
                                                </div>


                                                {{-- <div class="btn-group">
                                                    <div class="dropdown">
                                                        <button class="btn btn-default dropdown-toggle" type="button"
                                                            id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu"
                                                            aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" href="#">Print Summary of
                                                                Earnings</a>
                                                            <a class="dropdown-item" href="#">Print Contract</a>
                                                            <a class="dropdown-item" href="#">Print
                                                                Attendance</a>
                                                        </div>
                                                    </div>
                                                    <a title="Show Employee Info" wire:navigate
                                                        href="{{ route('employee.info', ['label' => $label, 'id' => $data->id]) }}"
                                                        class="dropdown-item text-warning"
                                                        href="javascript:void(0)"><i class="fa fa-edit"
                                                            aria-hidden="true"></i></a>
                                                    <a title="Delete this Employee"
                                                        wire:click="alertConfirm({{ $data->id }})"
                                                        class="dropdown-item text-danger" href="javascript:void(0)"><i
                                                            class="fa fa-trash" aria-hidden="true"></i></a>
                                                </div> --}}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11">
                                                <livewire:no-data-found />
                                            </td>

                                        </tr>
                                    @endforelse
                                <tfoot class="table-info">
                                    <tr>
                                        <th width="3%" class="text-start"><input width="3%" type="checkbox"
                                                wire:model.live="selectAll"></th>
                                        <th width="7%"class="text-center">IMAGE</th>
                                        <th width="5%"class="text-center">EMP NO</th>
                                        <th class="text-start">EMPLOYEE DETAILS</th>
                                        <th class="text-center">POSITION</th>
                                        <th class="text-center">WORKSHIFT</th>
                                        <th class="text-center">DEPARTMENT</th>
                                        <th class="text-center">STATUS</th>
                                        <th class="text-center">DEFAULT PAY</th>
                                        <th class="text-start">NOTES/REMARKS</th>
                                        <th class="text-center">ACTIONS</th>
                                    </tr>
                                </tfoot>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $records->links() }}
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">{{ $modalTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="submit()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Designation Name</label>
                            <input wire:model="name" type="text"
                                class="form-control form-control-lg @error('name') is-invalid @enderror">

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        @if ($updateMode == true)
                            <button wire:click.prevent="update()" class="btn btn-success">Update</button>
                        @else
                            <button wire:click.prevent="submit(false)" class="btn btn-primary">Save</button>
                            <button wire:click.prevent="submit(true)" class="btn btn-info">Save & Create New</button>
                        @endif
                    </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Employees</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="file" wire:model="file" id="filepond">
                        @error('file')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" wire:click.prevent="import">Import</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('show-add-modal', () => {
            $('#addModal').modal('show');
        });

        window.addEventListener('hide-add-modal', () => {
            $('#addModal').modal('hide');
        });

        window.addEventListener('show-import-modal', () => {
            $('#importModal').modal('show');
        });

        window.addEventListener('hide-import-modal', () => {
            $('#importModal').modal('hide');
        });
    </script>
@endpush
