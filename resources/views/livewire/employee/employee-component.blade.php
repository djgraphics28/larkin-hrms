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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Employee Lists</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                    title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <div class="btn-group float-right" role="group" aria-label="Groups">
                                @if (!empty($selectedRows))
                                    <div class="btn-group mr-2">
                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle dropdown-icon"
                                            data-toggle="dropdown">
                                            <span class="sr-only">Toggle Dropdown</span>
                                            Bulk Actions
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                            <a wire:click="bulkGenerateFinalPay" class="dropdown-item"
                                                href="#">Generate Final Pay</a>
                                        </div>
                                    </div>
                                @endif

                                <button wire:click="openImportModal()" type="button"
                                    class="btn btn-warning btn-sm mr-2"><i class="fa fa-upload" aria-hidden="true"></i>
                                    Import</button>
                                <button wire:click="export()" type="button" class="btn btn-success btn-sm mr-2"><i
                                        class="fa fa-file-excel" aria-hidden="true"></i> Export</button>
                                <button type="button" class="btn btn-danger btn-sm mr-2"><i class="fa fa-file-pdf"
                                        aria-hidden="true"></i> PDF</button>
                                <a wire:navigate href="{{ route('employee.create', $label) }}" type="button"
                                    class="btn btn-primary btn-sm mr-2"><i class="fa fa-plus" aria-hidden="true"></i>
                                    Add New</a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="row p-3">
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <select class="form-control form-control" wire:model="perPage">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control"
                                            placeholder="Search term here" wire:model.live.debounce.500="search">
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
                            @if (!empty($selectedRows))
                                @if ($selectAllData)
                                    <a class="ml-3" style="cursor: pointer;"
                                        wire:click.live="selectAllData"><b>Select
                                            All</b></a>
                                @else
                                    <a class="ml-3" style="cursor: pointer;"
                                        wire:click.live="deselectAllData"><b>Deselect
                                            All</b></a>
                                @endif
                                <span> {{ $selectedRows ? count($selectedRows) : 0 }} rows
                                    selected</span>
                            @endif

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="3%" class="text-start"><input width="3%" type="checkbox"
                                                wire:model.live="selectAll"></th>
                                        <th width="10%"class="text-center">EMP NO</th>
                                        <th class="text-start">EMPLOYEE NAME</th>
                                        <th class="text-center">POSITION</th>
                                        <th class="text-center">WORKSHIFT</th>
                                        <th class="text-center">DEPARTMENT</th>
                                        <th class="text-center">STATUS</th>
                                        <th class="text-center">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @include('shared.table-loader')
                                    @forelse ($records as $data)
                                        <tr>
                                            <td width="3%" class="text-start"><input type="checkbox"
                                                    wire:model.prevent="selectedRows" value="{{ $data->id }}">
                                            </td>
                                            <td class="text-center">{{ $data->employee_number }}</td>
                                            <td class="text-start"><strong>{{ $data->first_name }}
                                                    {{ $data->last_name }}</strong>
                                                <br><small>{{ $data->email }}</small>
                                            </td>
                                            <td class="text-center">{{ $data->designation->name }}</td>
                                            <td class="text-center">{{ $data->workshift->title }}</td>
                                            <td class="text-center">{{ $data->department->name }}</td>
                                            <td class="text-center">{{ $data->employee_status->name }}</td>
                                            <td class="project-actions text-right">
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-info btn-sm dropdown-toggle dropdown-icon"
                                                        data-toggle="dropdown">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                        Generate
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">
                                                        <a wire:click="generateFinalPay({{ $data->id }})"
                                                            class="dropdown-item" href="#">Final Pay</a>
                                                        {{-- <a class="dropdown-item" href="#">Another action</a>
                                                        <a class="dropdown-item" href="#">Something else
                                                            here</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#">Separated link</a> --}}
                                                    </div>
                                                </div>
                                                <a wire:click="view({{ $data->id }})"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-folder">
                                                    </i>
                                                    View
                                                </a>
                                                <a wire:navigate
                                                    href="{{ route('employee.info', ['label' => $label, 'id' => $data->id]) }}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fas fa-pencil-alt">
                                                    </i>
                                                    Info
                                                </a>
                                                <a wire:click="alertConfirm({{ $data->id }})"
                                                    class="btn btn-danger btn-sm" href="javascript:void(0)">
                                                    <i class="fas fa-trash">
                                                    </i>
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td rowspan="5" colspan="8" class="text-center"><i
                                                    class="fa fa-ban" aria-hidden="true"></i> No Result Found</td>
                                        </tr>
                                    @endforelse
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
