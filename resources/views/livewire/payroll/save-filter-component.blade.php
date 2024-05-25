<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Saved Filters</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Saved Filters</li>
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
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <select class="form-control" wire:model.live="perPage">
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
                                <div class="col-md-8">
                                    <div class="btn-group float-right" role="group" aria-label="Groups">
                                        <a href="{{ route('payroll') }}" type="button"
                                            class="btn btn-secondary btn-md mr-2"><i class="fa fa-arrow-left"
                                                aria-hidden="true"></i> Back to Payroll</a>
                                        <button wire:click="addNew()" type="button"
                                            class="btn btn-primary btn-md mr-2"><i class="fa fa-plus"
                                                aria-hidden="true"></i> Add New</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-0">
                            <table class="table table-sm">
                                <thead class="table-info">
                                    <tr>
                                        <th class="text-start align-middle">
                                            <div class="icheck-primary d-inline"><input id="selectAll" type="checkbox"
                                                    wire:model.live="selectAll"><label for="selectAll"></div>
                                        </th>
                                        <th>Filter Name</th>
                                        <th>Employees</th>
                                        <th class="text-center">Employees Counts</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="3" class="text-center align-items-center">
                                            <div wire:loading wire:target="search"><livewire:table-loader /></div>
                                        </td>
                                    </tr>
                                    @forelse ($records as $data)
                                        <tr wire:key="search-{{ $data->id }}">
                                            <td class="text-start align-middle">
                                                <div class="icheck-primary d-inline"><input class="icheck-primary"
                                                        type="checkbox" wire:model.prevent="selectedRows"
                                                        id="{{ $data->id }}" value="{{ $data->id }}"><label
                                                        for="{{ $data->id }}"></div>
                                            </td>
                                            <td width="20%" class="text-start  align-middle">{{ $data->title }}</td>
                                            <td class="text-start align-middle">
                                                @forelse (json_decode($data->employee_lists) as $item)
                                                    <span data-toggle="tooltip" data-placement="top"
                                                        title="{{ \App\Models\Employee::find($item)->first_name }} {{ \App\Models\Employee::find($item)->last_name }}"
                                                        class="badge badge-primary"
                                                        style="cursor: pointer;">{{ \App\Models\Employee::find($item)->employee_number }}</span>
                                                @empty
                                                    No Employee
                                                @endforelse
                                            </td>
                                            <td class="text-center align-middle">
                                                {{ count(json_decode($data->employee_lists)) }} employees</td>
                                            <td class="text-center align-middle">
                                                <div class="btn-group">
                                                    <a wire:click="edit({{ $data->id }})"
                                                        class="dropdown-item text-warning" href="javascript:void(0)"><i
                                                            class="fa fa-edit" aria-hidden="true"></i></a>
                                                    <a wire:click="alertConfirm({{ $data->id }})"
                                                        class="dropdown-item text-danger" href="javascript:void(0)"><i
                                                            class="fa fa-trash" aria-hidden="true"></i></a>
                                                </div>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <livewire:no-data-found />
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true" wire:ignore.self data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Create Employee Filters</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Filter Name</label>

                        <input wire:model="title" id="title" type="text" class="form-control">
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" wire:ignore>
                                <label for="selectedDepartment">Choose Department</label>

                                <select multiple="multiple" wire:model.live="selectedDepartment"
                                    id="selectedDepartment" wire:change="filterEmployees"
                                    class="form-control select2bs4">
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group" wire:ignore>
                                <label for="selectedDesignation">Choose Position</label>

                                <select multiple="multiple" wire:model="selectedDesignation" id="selectedDesignation"
                                    class="form-control select2bs4">
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" wire:ignore>
                                <label for="selectedDepartment">Choose Department</label>
                                <select multiple="multiple" wire:model.live="selectedDepartment"
                                        id="selectedDepartment" class="form-control select2bs4">
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group" wire:ignore>
                                <label for="selectedDesignation">Choose Position</label>
                                <select multiple="multiple" wire:model="selectedDesignation" id="selectedDesignation"
                                        class="form-control select2bs4">
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-sm table-hover">
                                <thead class="table-info">
                                    <tr>
                                        <th class="text-start"><div class="icheck-primary d-inline"><input id="selectAllEmployees" type="checkbox"
                                                wire:model.live="selectAllEmployees"><label for="selectAllEmployees"></div></th>
                                        <th>EmpNo</th>
                                        <th>Employee Name</th>
                                        <th>Position</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center align-items-center">
                                            <div wire:loading wire:target="search"><livewire:table-loader /></div>
                                        </td>
                                    </tr>
                                    @forelse ($employees as $employee)
                                        <tr wire:key="search-{{ $employee->id }}">
                                            <td class="text-start"><div class="icheck-primary d-inline"><input id="employee-{{ $employee->id }}" type="checkbox"
                                                    wire:model.live="selectedEmployeeRows"
                                                    value="{{ $employee->id }}"><label
                                                    for="employee-{{ $employee->id }}"></div></td>
                                            <td>{{ $employee->employee_number }}</td>
                                            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                            <td>{{ $employee->designation->name }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <livewire:no-data-found />
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="table-info">
                                    <tr>
                                        <th class="text-start"><div class="icheck-primary d-inline"><input id="selectAllEmployees" type="checkbox"
                                                wire:model.live="selectAllEmployees"><label for="selectAllEmployees"></div></th>
                                        <th>EmpNo</th>
                                        <th>Employee Name</th>
                                        <th>Position</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <p class="float-left">{{ $selectedEmployeeRows ? count($selectedEmployeeRows) .' employee(s) selected' : ''}}</p>
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
</div>

@push('scripts')
    <script>
        window.addEventListener('show-add-modal', () => {
            $('#addModal').modal('show');
        });

        window.addEventListener('hide-add-modal', () => {
            $('#addModal').modal('hide');
        });

        $(function() {
            $('#selectedDepartment').on('change', function(e) {
                var data = $(this).val();
                @this.set('selectedDepartment', data);
            });

            $('#selectedDesignation').on('change', function(e) {
                var data = $(this).val();
                @this.set('selectedDesignation', data);
            });

        });
    </script>
@endpush
