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
                        <div class="card-header">
                            <div class="btn-group float-right" role="group" aria-label="Groups">
                                <a href="{{ route('payroll') }}" type="button" class="btn btn-secondary btn-md mr-2"><i
                                        class="fa fa-arrow-left" aria-hidden="true"></i> Back to Payroll</a>
                                <button wire:click="addNew()" type="button" class="btn btn-primary btn-md mr-2"><i
                                        class="fa fa-plus" aria-hidden="true"></i> Add New</button>
                            </div>
                        </div>
                        <div class="card-body"></div>
                        <div class="card-footer">
                            {{-- {{ $records->links() }} --}}
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
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" wire:ignore>
                                <label for="selectedDepartment">Choose Department</label>

                                <select multiple="multiple" wire:model="selectedDepartment" id="selectedDepartment"
                                    class="form-control select2bs4">
                                    <option value="">All Departments</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group" wire:ignore>
                                <label for="selectedDesignation">Choose Designation</label>

                                <select multiple="multiple" wire:model="selectedDesignation" id="selectedDesignation"
                                    class="form-control select2bs4">
                                    <option value="">All Designations</option>
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <table class="table table-sm">
                            <thead class="table-info">
                                <tr>
                                    <th></th>
                                    <th>EmpNo</th>
                                    <th>Employee Name</th>
                                    <th>Position</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $employee)
                                    <tr>
                                        <td></td>
                                        <td>{{ $employee->employee_number }}</td>
                                        <td>{{ $employee->first_name }} {{ $employee->last }}</td>
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
                        </table>
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
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

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
