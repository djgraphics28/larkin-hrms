<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Payruns</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Payruns</li>
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
                            <div class="btn-group float-right" role="group" aria-label="Groups">
                                <button type="button" class="btn btn-warning btn-sm mr-2"><i class="fa fa-upload"
                                        aria-hidden="true"></i> Import</button>
                                <button wire:click="export()" type="button" class="btn btn-success btn-sm mr-2"><i class="fa fa-file-excel"
                                        aria-hidden="true"></i> Export</button>
                                <button type="button" class="btn btn-danger btn-sm mr-2"><i class="fa fa-file-pdf"
                                        aria-hidden="true"></i> PDF</button>
                                <button wire:click="generateNew()" type="button" class="btn btn-primary btn-sm mr-2"><i
                                        class="fa fa-plus" aria-hidden="true"></i> Generate New</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
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
                            </div>


                            <table class="table table-condensed table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-start"><input type="checkbox" wire:model.live="selectAll"></th>
                                        <th class="text-start">Payrun Date</th>
                                        <th class="text-center">Fortnight</th>
                                        <th class="text-center">Business</th>
                                        <th class="text-center">Remarks</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @include('shared.table-loader')
                                    @forelse ($records as $data)
                                        <tr>
                                            <td class="text-start"><input type="checkbox"
                                                    wire:model.prevent="selectedRows" value="{{ $data->id }}"></td>
                                            <td class="text-start">{{ date('F d, Y h:i:s A', strtotime($data->created_at)) }}</td>
                                            <td class="text-center">{{ $data->fortnight->code }} ({{ \Carbon\Carbon::parse($data->fortnight->start)->format('d-M') . ' - ' . \Carbon\Carbon::parse($data->fortnight->end)->format('d-M') }})</td>
                                            <td class="text-center">{{ $data->business->code }} - {{ $data->business->name }}</td>
                                            <td class="text-center">{{ $data->remarks }}</td>

                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a wire:click.prevent="generateAba({{ $data->id }})" class="dropdown-item text-primary" href="javascript:void(0)">
                                                        <i class="fa fa-download" aria-hidden="true"></i>
                                                    </a>

                                                    <a href="#" class="dropdown-item text-warning" href="javascript:void(0)">
                                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                                    </a>

                                                    <a href="#" class="dropdown-item text-danger" href="javascript:void(0)">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </a>

                                                </div>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"><livewire:no-data-found /></td>
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
                            <label for="">Fortnight</label>
                            <select class="form-control" wire:model.live="fortnight_id">
                                <option value="">Select Fortnight range</option>
                                @foreach ($fortnights as $fn)
                                    <option value="{{ $fn->id }}">{{ $fn->code }} --
                                        {{ \Carbon\Carbon::parse($fn->start)->format('d-M') . ' - ' . \Carbon\Carbon::parse($fn->end)->format('d-M') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('fortnight_id')
                                <p class="text-sm text-danger mt-1">Fortnight Required</p>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="">Department</label>
                            <select class="form-control" wire:model.live="department_ids" multiple>
                                <option value="all" class="font-weight-bold">Select All Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_ids')
                                <p class="text-sm text-danger mt-1">Department/s Required</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Employees</label>
                            <select class="form-control" wire:model.live="employee_ids" multiple>
                                <option value="all" class="font-weight-bold">Select All Employees</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->employee_number }} -- {{ $employee->first_name . ' '.  $employee->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_ids')
                                <p class="text-sm text-danger mt-1">Employee Required</p>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        @if ($updateMode == true)
                            <button wire:click.prevent="update()" class="btn btn-success">Update</button>
                        @else
                            <button wire:click.prevent="submit()" class="btn btn-primary">Generate</button>
                            {{-- <button wire:click.prevent="submit(true)" class="btn btn-info">Generate & Create New</button> --}}
                        @endif
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
    </script>
@endpush
