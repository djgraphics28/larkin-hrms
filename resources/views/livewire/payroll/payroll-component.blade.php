<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Payroll</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Payroll</li>
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
                                    <div class="form-group row">
                                        <select class="form-control select2bs4" wire:model.live="selectedFN"
                                            id="selectedFN">
                                            <option value="">Select Fortnight range</option>
                                            @foreach ($fortnights as $fn)
                                                <option value="{{ $fn->id }}">{{ $fn->code }} --
                                                    {{ \Carbon\Carbon::parse($fn->start)->format('d-M') . ' - ' . \Carbon\Carbon::parse($fn->end)->format('d-M') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control"
                                            placeholder="Search term here" wire:model.live.debounce.500="search">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <button wire:click="addNew" class="btn btn-primary float-right"><i
                                            class="fa fa-plus"></i>
                                        Create Payroll</button>
                                    <a wire:navigate href="{{ route('save-filters') }}"
                                        class="btn btn-success float-right mr-2"><i class="fa fa-plus"></i>
                                        Create Filters</a>

                                </div>
                            </div>


                        </div>
                        {{-- <div class="card-footer">
                            {{ $records->links() }}
                        </div> --}}
                    </div>
                </div>
                <!-- /.col-md-12 -->
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        @if (!empty($records))
                            <div class="card-header">
                                <div class="btn-group float-right" role="group" aria-label="Groups">
                                    <button type="button" class="btn btn-warning btn-sm mr-2"><i class="fa fa-upload"
                                            aria-hidden="true"></i> Import</button>
                                    <button wire:click="export()" type="button" class="btn btn-success btn-sm mr-2"><i
                                            class="fa fa-file-excel" aria-hidden="true"></i> Export</button>
                                    <button type="button" class="btn btn-danger btn-sm mr-2"><i class="fa fa-file-pdf"
                                            aria-hidden="true"></i> PDF</button>
                                </div>
                            </div>
                        @endif

                        <div class="card-body">
                            <div>
                                <div class="d-flex justify-content-center items-align-center">
                                    <div class="overlay-wrapper mt-10 mb-10" wire:loading wire:target="generate">
                                        <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                            <div class="text-bold pt-2">Loading...</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->

        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
            aria-hidden="true" wire:ignore.self data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-xxl" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <div class="d-flex justify-content-between w-100">
                            <h5 class="modal-title" id="addModalLabel">Generate Payroll</h5>
                            <div>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                @if ($updateMode == true)
                                    <button wire:click.prevent="update()" class="btn btn-success">Update</button>
                                @else
                                    <button wire:click.prevent="submit()" class="btn btn-primary">
                                        <i class="fa fa-play"></i> Payrun
                                    </button>
                                @endif
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <select class="form-control" wire:model.live="fortnight">
                                                        <option value="">Select Fortnight</option>
                                                        @foreach ($fortnights as $fn)
                                                            <option value="{{ $fn->id }}">{{ $fn->code }} --
                                                                {{ \Carbon\Carbon::parse($fn->start)->format('d-M') . ' - ' . \Carbon\Carbon::parse($fn->end)->format('d-M') }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('fortnight')
                                                        <p class="text-sm text-danger mt-1">Fortnight Required</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="custom-control custom-switch">
                                                        <input @if ($chooseFiltered) checked @endif
                                                            type="checkbox" role="switch"
                                                            class="custom-control-input"
                                                            wire:model.live="chooseFiltered" id="chooseFiltered">
                                                        <label class="custom-control-label"
                                                            for="chooseFiltered">Select from the saved
                                                            filtered Employees?</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($chooseFiltered)
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <div class="btn-group" role="group"
                                                        aria-label="Filtered Employees">
                                                        <button wire:click="selectedByFilteredEmployees('all')"
                                                            type="button" class="btn btn-primary mr-2">All Employees
                                                        </button>
                                                        @forelse ($saveFilters as $item)
                                                            <button
                                                                wire:click="selectedByFilteredEmployees({{ $item->id }})"
                                                                type="button"
                                                                class="btn btn-info mr-2">{{ $item->title }}&nbsp;({{ count(json_decode($item->employee_lists)) }})
                                                            </button>
                                                        @empty
                                                            <p>No Options</p>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group" wire:ignore>
                                            <label for="selectedDepartment">Filter by Department</label>
                                            <select multiple="multiple" wire:model.live="selectedDepartment"
                                                id="selectedDepartment" class="form-control select2bs4">
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group" wire:ignore>
                                            <label for="selectedDesignation">Filter by Position</label>
                                            <select multiple="multiple" wire:model="selectedDesignation"
                                                id="selectedDesignation" class="form-control select2bs4">
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}">{{ $designation->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between w-100 mb-3">
                                    <label for="">Select Manually </label><button
                                        wire:click="resetEmployeeSelection" class="btn btn-default">Reset
                                        table</button>
                                </div>
                                <div style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-info" style="position: sticky; top: 0; z-index: 1;">
                                            <tr>
                                                <th class="text-start">
                                                    <div class="icheck-primary d-inline"><input
                                                            id="selectAllEmployees" type="checkbox"
                                                            wire:model.live="selectAllEmployees"><label
                                                            for="selectAllEmployees"></div>
                                                </th>
                                                <th>EmpNo</th>
                                                <th>Employee Name</th>
                                                <th>Position</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="text-center align-items-center">
                                                    <div wire:loading wire:target="search, selectedDepartment, selectedDesignation, selectedByFilteredEmployees, resetEmployeeSelection">
                                                        <livewire:table-loader />
                                                    </div>
                                                </td>
                                            </tr>
                                            @forelse ($employees as $employee)
                                                <tr wire:key="search-{{ $employee->id }}">
                                                    <td class="text-start">
                                                        <div class="icheck-primary d-inline"><input
                                                                id="employee-{{ $employee->id }}" type="checkbox"
                                                                wire:model.live="selectedEmployeeRows"
                                                                value="{{ $employee->id }}"><label
                                                                for="employee-{{ $employee->id }}"></div>
                                                    </td>
                                                    <td>{{ $employee->employee_number }}</td>
                                                    <td>{{ $employee->first_name }} {{ $employee->last_name }}
                                                    </td>
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
                                        <tfoot class="table-info" style="position: sticky; bottom: 0; z-index: 1;">
                                            <tr>
                                                <th class="text-start">
                                                    <div class="icheck-primary d-inline"><input
                                                            id="selectAllEmployees" type="checkbox"
                                                            wire:model.live="selectAllEmployees"><label
                                                            for="selectAllEmployees"></div>
                                                </th>
                                                <th>EmpNo</th>
                                                <th>Employee Name</th>
                                                <th>Position</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-between w-100">
                            <p class="mb-0">
                                {{ $selectedEmployeeRows ? count($selectedEmployeeRows) . ' employee(s) selected' : '' }}
                            </p>
                            <div>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                @if ($updateMode == true)
                                    <button wire:click.prevent="update()" class="btn btn-success">Update</button>
                                @else
                                    <button wire:click.prevent="submit()" class="btn btn-primary">
                                        <i class="fa fa-play"></i> Payrun
                                    </button>
                                @endif
                            </div>
                        </div>
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
        <script>
            $(function() {
                $('#selectedFN').on('change', function(e) {
                    var data = $(this).val();
                    @this.set('selectedFN', data);
                });

                // $('#selectedFilteredEmployees').on('change', function(e) {
                //     var data = $(this).val();
                //     @this.set('selectedFilteredEmployees', data);
                // });
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
