<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Businesses</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Businesses</li>
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
                                <button wire:click="export()" type="button" class="btn btn-success btn-sm mr-2"><i
                                        class="fa fa-file-excel" aria-hidden="true"></i> Export</button>
                                <button type="button" class="btn btn-danger btn-sm mr-2"><i class="fa fa-file-pdf"
                                        aria-hidden="true"></i> PDF</button>
                                <button wire:click="addNew()" type="button" class="btn btn-primary btn-sm mr-2"><i
                                        class="fa fa-plus" aria-hidden="true"></i> Add New</button>
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
                                <thead class="table-info">
                                    <tr>
                                        <th width="3%" class="text-start">
                                            <div class="icheck-primary d-inline"><input id="selectAll" type="checkbox"
                                                    wire:model.live="selectAll"><label for="selectAll"></div>
                                        </th>
                                        <th class="text-center">Code</th>
                                        <th class="text-start">Business Name</th>
                                        <th width="30%" class="text-start">Departments</th>
                                        <th class="text-center">Contact Number</th>
                                        <th class="text-start">Address</th>
                                        <th width="10%" class="text-center">Status</th>
                                        <th width="5%" class="text-center">Employees</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="9" class="text-center align-items-center">
                                            <div wire:loading wire:target="search"><livewire:table-loader /></div>
                                        </td>
                                    </tr>
                                    @forelse ($records as $data)
                                        <tr wire:key="search-{{ $data->id }}">
                                            <td width="3%" class="text-start align-middle">
                                                <div class="icheck-primary d-inline">
                                                    <input id="business-{{ $data->id }}" type="checkbox"
                                                        wire:model.live="selectedRows" value="{{ $data->id }}">
                                                    <label for="business-{{ $data->id }}"></label>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $data->code }}</td>
                                            <td class="text-start">{{ $data->name }}</td>
                                            <td class="text-start">
                                                @forelse ($data->departments as $dept)
                                                    <span class="badge badge-primary">{{ $dept->name }}</span>
                                                @empty
                                                    <span class="badge badge-danger">no department</span>
                                                @endforelse
                                            </td>
                                            <td class="text-center">{{ $data->contact_number }}</td>
                                            <td class="text-start">{{ $data->address }}</td>
                                            <td class="text-center">@livewire('active-status', ['model' => $data, 'field' => 'is_active'], key($data->id))</td>
                                            <td class="text-center">{{ $data->employees_count }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-sm btn-default dropdown-toggle mr-2"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        Compensations
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a wire:click="addAllowance({{ $data->id }})"
                                                            class="dropdown-item" href="#">
                                                            <i class="fa fa-plus"></i> Allowances
                                                        </a>
                                                        <a wire:click="addDeduction({{ $data->id }})"
                                                            class="dropdown-item" href="#">
                                                            <i class="fa fa-minus"></i> Deductions
                                                        </a>
                                                    </div>
                                                    <a wire:click="edit({{ $data->id }})"
                                                        class="dropdown-item text-warning"
                                                        href="javascript:void(0)"><i class="fa fa-edit"
                                                            aria-hidden="true"></i></a>
                                                    <a wire:click="alertConfirm({{ $data->id }})"
                                                        class="dropdown-item text-danger" href="javascript:void(0)"><i
                                                            class="fa fa-trash" aria-hidden="true"></i></a>
                                                </div>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9">
                                                <livewire:no-data-found />
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="table-info">
                                    <tr>
                                        <th width="3%" class="text-start">
                                            <div class="icheck-primary d-inline"><input id="selectAll"
                                                    type="checkbox" wire:model.live="selectAll"><label
                                                    for="selectAll"></div>
                                        </th>
                                        <th class="text-center">Code</th>
                                        <th class="text-start">Business Name</th>
                                        <th width="30%" class="text-start">Departments</th>
                                        <th class="text-center">Contact Number</th>
                                        <th class="text-start">Address</th>
                                        <th width="10%" class="text-center">Status</th>
                                        <th width="5%" class="text-center">Employees</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </tfoot>
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
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">{{ $modalTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Code</label>
                        <input wire:model="code" type="text"
                            class="form-control form-control-lg @error('code') is-invalid @enderror">

                        @error('code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Business Name</label>
                        <input wire:model="name" type="text"
                            class="form-control form-control-lg @error('name') is-invalid @enderror">

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="">Contact Number</label>
                            <input wire:model="contact_number" type="text"
                                class="form-control form-control-lg @error('contact_number') is-invalid @enderror">

                            @error('contact_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="">Address</label>
                            <input wire:model="address" type="text"
                                class="form-control form-control-lg @error('address') is-invalid @enderror">

                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group" wire:ignore>
                        <label>Select Departments</label>
                        <table class="table table-sm table-condensed">
                            <tbody>
                                <tr>
                                    <th width="10px" class="text-start">
                                        <div class="icheck-primary d-inline"><input id="selectAllDepartment"
                                                type="checkbox" wire:model.live="selectAllDepartment"><label
                                                for="selectAllDepartment"></div>
                                    </th>
                                    <th>Select All</th>
                                </tr>
                                @foreach ($departments as $data)
                                    <tr>
                                        <td class="text-start">
                                            <div class="icheck-primary d-inline">
                                                <input id="department-{{ $data->id }}" type="checkbox"
                                                    wire:model.live="selectedDepartmentRows"
                                                    value="{{ $data->id }}">
                                                <label for="department-{{ $data->id }}"></label>
                                            </div>
                                        </td>
                                        <td><label for="{{ $data->name }}">{{ $data->name }}</label></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    @if ($updateMode == true)
                        <button wire:click.prevent="update()" class="btn btn-success">Update</button>
                    @else
                        <button wire:click.prevent="submit(false)" class="btn btn-primary">Save</button>
                        <button wire:click.prevent="submit(true)" class="btn btn-info">Save & Create
                            New</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="allowanceModal" tabindex="-1" role="dialog" aria-labelledby="allowanceModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="allowanceModalLabel">Add Allowances </h5>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> --}}
                    <button wire:click.live="addAllowanceRow" class="btn btn-primary"><i
                            class="fa fa-plus"></i></button>
                </div>
                <div class="modal-body">
                    @foreach ($allowances as $key => $item)
                        {{-- @if ($key > 0) --}}
                        <div class="row allowance" wire:key="allowance-{{ $key }}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select wire:model="allowances.{{ $key }}.name" id=""
                                        class="form-control">
                                        <option value="">Choose Allowance</option>
                                        @forelse ($allowanceItems as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @empty
                                            <option value="">No Options</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select wire:model="allowances.{{ $key }}.label" id=""
                                        class="form-control">
                                        <option value="">Choose Label</option>
                                        <option value="National">National</option>
                                        <option value="Expatriate">Expatriate</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input placeholder="0.00" wire:model="allowances.{{ $key }}.amount"
                                        type="number" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button wire:click.live="removeAllowanceRow({{ $key }})"
                                        class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        {{-- @endif --}}
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button wire:click.prevent="saveAllowance" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deductionModal" tabindex="-1" role="dialog" aria-labelledby="deductionModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deductionModalLabel">Add Deductions</h5>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> --}}
                    <button wire:click.live="addDeductionRow" class="btn btn-primary"><i
                            class="fa fa-plus"></i></button>
                </div>
                <div class="modal-body">
                    @foreach ($deductions as $key => $item)
                        {{-- @if ($key > 0) --}}
                        <div class="row deduction" wire:key="deduction-{{ $key }}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select wire:model="deductions.{{ $key }}.name" id=""
                                        class="form-control">
                                        <option value="">Choose Deduction</option>
                                        @forelse ($deductionItems as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @empty
                                            <option value="">No Options</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select wire:model="deductions.{{ $key }}.label" id=""
                                        class="form-control">
                                        <option value="">Choose Label</option>
                                        <option value="National">National</option>
                                        <option value="Expatriate">Expatriate</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input placeholder="0.00" wire:model="deductions.{{ $key }}.amount"
                                        type="number" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button wire:click.live="removeDeductionRow({{ $key }})"
                                        class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        {{-- @endif --}}
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button wire:click.prevent="saveDeduction" class="btn btn-primary">Save</button>
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

        window.addEventListener('show-allowance-modal', () => {
            $('#allowanceModal').modal('show');
        });

        window.addEventListener('hide-allowance-modal', () => {
            $('#allowanceModal').modal('hide');
        });

        window.addEventListener('show-deduction-modal', () => {
            $('#deductionModal').modal('show');
        });

        window.addEventListener('hide-deduction-modal', () => {
            $('#deductionModal').modal('hide');
        });
    </script>

    <script>
        $(function() {
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        })
    </script>
@endpush
