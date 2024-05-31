<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Roles</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a  href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a  href="{{ route('users') }}">User
                                Management</a></li>
                        <li class="breadcrumb-item active">Roles</li>
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
                                <a  href="{{ route('users') }}" type="button"
                                    class="btn btn-secondary btn-md mr-2"><i class="fa fa-arrow-left"
                                        aria-hidden="true"></i> Return to Users</a>
                                <button wire:click="addNew()" type="button" class="btn btn-primary btn-md mr-2"><i
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
                                        <th class="text-start">Role Name</th>
                                        <th class="text-start">Permissions</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center align-items-center">
                                            <div wire:loading wire:target="search" class="overlay dark">
                                                <livewire:table-loader />
                                            </div>
                                        </td>
                                    </tr>
                                    @forelse ($records as $data)
                                        <tr wire:key="search-{{ $data->id }}">
                                            <td width="3%" class="text-start align-middle">
                                                <div class="icheck-primary d-inline">
                                                    <input id="role-{{ $data->id }}" type="checkbox"
                                                        wire:model.live="selectedRows" value="{{ $data->id }}">
                                                    <label for="role-{{ $data->id }}"></label>
                                                </div>
                                            </td>
                                            <td class="text-start">{{ $data->name }}</td>
                                            <td class="text-start">
                                                @foreach ($data->permissions as $permission)
                                                    <span class="badge badge-primary">{{ $permission->name }}</span>
                                                @endforeach
                                            </td>
                                            <td class="text-center">
                                                @if ($data->name != 'Admin')
                                                    <div class="btn-group">
                                                        <a wire:click="edit({{ $data->id }})"
                                                            class="dropdown-item text-warning"
                                                            href="javascript:void(0)"><i class="fa fa-edit"
                                                                aria-hidden="true"></i></a>
                                                        <a wire:click="alertConfirm({{ $data->id }})"
                                                            class="dropdown-item text-danger"
                                                            href="javascript:void(0)"><i class="fa fa-trash"
                                                                aria-hidden="true"></i></a>
                                                    </div>
                                                @endif
                                            </td>
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
                                        <th width="3%" class="text-start">
                                            <div class="icheck-primary d-inline"><input id="selectAll" type="checkbox"
                                                    wire:model.live="selectAll"><label for="selectAll"></div>
                                        </th>
                                        <th class="text-start">Role Name</th>
                                        <th class="text-start">Permissions</th>
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
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">{{ $modalTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Role Name</label>
                        <input wire:model="name" type="text"
                            class="form-control form-control-lg @error('name') is-invalid @enderror">

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label for="">Permissions</label>
                    {{-- PERMISSIONS --}}
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" wire:model="all_permission"
                                id="select-all" wire:change="toggleAllPermissions">
                            <label class="custom-control-label" for="select-all">Give All Permissions</label>
                        </div>
                    </div>
                    {{-- Dashboard Managmenet --}}
                    <div class="card">
                        <div class="card-header">

                            <h3 class="card-title">
                                <div class="custom-control custom-switch">
                                    <input {{ $access_dashboard ? 'checked' : '' }} type="checkbox"
                                        class="custom-control-input" id="access_dashboard"
                                        wire:model="access_dashboard">
                                    <label class="custom-control-label" for="access_dashboard">Dashboard
                                        Management</label>
                                </div>
                            </h3>
                            <div class="card-tools">
                                <!-- Collapse Button -->
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i></button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- /.card-body -->
                        </div>
                    </div>
                    {{-- @foreach ($allPermissions as $permission)
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="{{ $permission->id }}"
                                wire:model="permissions" value="{{ $permission->id }}"
                                {{ in_array($permission->id, $permissions) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    @endforeach --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <div class="custom-control custom-switch">
                                    <input {{ $access_apps_management ? 'checked' : '' }} type="checkbox"
                                        class="custom-control-input" id="access_apps_management"
                                        wire:model="access_apps_management" checked>
                                    <label class="custom-control-label" for="access_apps_management">Apps
                                        Management</label>
                                </div>
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Businesses --}}
                            <div class="card">
                                <div class="card-body">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input"
                                                id="access_to_businesses" wire:model="access_businesses"
                                                {{ $access_businesses ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="access_to_businesses">Access to
                                                Businesses</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_businesses ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_businesses"
                                                wire:model="create_businesses">
                                            <label class="custom-control-label" for="create_businesses">Can Create New
                                                Business</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_businesses ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_businesses"
                                                wire:model="edit_businesses">
                                            <label class="custom-control-label" for="edit_businesses">Can Edit
                                                Business</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_businesses ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_businesses"
                                                wire:model="delete_businesses">
                                            <label class="custom-control-label" for="delete_businesses">Can Delete
                                                Business</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            {{-- Businesses --}}
                            {{-- Departments --}}
                            <div class="card">
                                <div class="card-body mt-2">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_departments ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_departments"
                                                wire:model="access_departments">
                                            <label class="custom-control-label" for="access_departments">Access to
                                                Departments</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_departments ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_departments"
                                                wire:model="create_departments">
                                            <label class="custom-control-label" for="create_departments">Can Create
                                                New
                                                Department</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_departments ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_departments"
                                                wire:model="edit_departments">
                                            <label class="custom-control-label" for="edit_departments">Can Edit
                                                Department</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_departments ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_departments"
                                                wire:model="delete_departments">
                                            <label class="custom-control-label" for="delete_departments">Can Delete
                                                Department</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Departments --}}
                            {{-- Workshifts --}}
                            <div class="card">
                                <div class="card-body">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_workshifts ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_workshifts"
                                                wire:model="access_workshifts">
                                            <label class="custom-control-label" for="access_workshifts">Access to
                                                Workshifts</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_workshifts ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_workshifts"
                                                wire:model="create_workshifts">
                                            <label class="custom-control-label" for="create_workshifts">Can Create New
                                                Workshift</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_workshifts ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_workshifts"
                                                wire:model="edit_workshifts">
                                            <label class="custom-control-label" for="edit_workshifts">Can Edit
                                                Workshift</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_workshifts ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_workshifts"
                                                wire:model="delete_workshifts">
                                            <label class="custom-control-label" for="delete_workshifts">Can Delete
                                                Workshift</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Workshifts --}}
                            {{-- Fortnight Generator --}}
                            <div class="card">
                                <div class="card-body mt-2">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_fortnight ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_fortnight"
                                                wire:model="access_fortnight">
                                            <label class="custom-control-label" for="access_fortnight">Access to
                                                Fortnight Generator</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_fortnight ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_fortnight"
                                                wire:model="create_fortnight">
                                            <label class="custom-control-label" for="create_fortnight">Can Generate
                                                Fortnight</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Fortnight Generator --}}
                            {{-- User & Roles --}}
                            <div class="card">
                                <div class="card-body mt-2">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_users ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_users"
                                                wire:model="access_users">
                                            <label class="custom-control-label" for="access_users">Access to
                                                Users</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_users ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_users"
                                                wire:model="create_users">
                                            <label class="custom-control-label" for="create_users">Can Create New
                                                User</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_users ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_users" wire:model="edit_users">
                                            <label class="custom-control-label" for="edit_users">Can Edit
                                                User</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_users ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_users"
                                                wire:model="delete_users">
                                            <label class="custom-control-label" for="delete_users">Can Delete
                                                User</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- User & Roles --}}
                            {{-- Company Bank Details --}}
                            <div class="card">
                                <div class="card-body mt-2">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_company_bank_details ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_company_bank_details"
                                                wire:model="access_company_bank_details">
                                            <label class="custom-control-label"
                                                for="access_company_bank_details">Access to
                                                Company Bank Details</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_company_bank_details ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_company_bank_details"
                                                wire:model="create_company_bank_details">
                                            <label class="custom-control-label" for="create_company_bank_details">Can
                                                Create New
                                                Company Bank Detail</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_company_bank_details ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_company_bank_details"
                                                wire:model="edit_company_bank_details">
                                            <label class="custom-control-label" for="edit_company_bank_details">Can
                                                Edit
                                                Company Bank Detail</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_company_bank_details ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_company_bank_details"
                                                wire:model="delete_company_bank_details">
                                            <label class="custom-control-label" for="delete_company_bank_details">Can
                                                Delete
                                                Company Bank Detail</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Company Bank Details --}}
                            {{-- Tax Tables --}}
                            <div class="card">
                                <div class="card-body mt-2">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_taxes ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_taxes"
                                                wire:model="access_taxes">
                                            <label class="custom-control-label" for="access_taxes">Access to
                                                Tax Tables</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_taxes ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_taxes"
                                                wire:model="create_taxes">
                                            <label class="custom-control-label" for="create_taxes">Can Create New
                                                Tax Table</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_taxes ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_taxes" wire:model="edit_taxes">
                                            <label class="custom-control-label" for="edit_taxes">Can Edit
                                                Tax Table</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_taxes ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_taxes"
                                                wire:model="delete_taxes">
                                            <label class="custom-control-label" for="delete_taxes">Can Delete
                                                Tax Table</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Tax Tables --}}
                            {{-- Holidays --}}
                            <div class="card">
                                <div class="card-body mt-2">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_holidays ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_holidays"
                                                wire:model="access_holidays">
                                            <label class="custom-control-label" for="access_holidays">Access to
                                                Holidays</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_holidays ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_holidays"
                                                wire:model="create_holidays">
                                            <label class="custom-control-label" for="create_holidays">Can Create New
                                                Holiday</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_holidays ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_holidays"
                                                wire:model="edit_holidays">
                                            <label class="custom-control-label" for="edit_holidays">Can Edit
                                                Holiday</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_holidays ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_holidays"
                                                wire:model="delete_holidays">
                                            <label class="custom-control-label" for="delete_holidays">Can Delete
                                                Holiday</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Holidays --}}
                            {{-- Nasfunds --}}
                            <div class="card">
                                <div class="card-body mt-2">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_nasfunds ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_nasfunds"
                                                wire:model="access_nasfunds">
                                            <label class="custom-control-label" for="access_nasfunds">Access to
                                                Nasfunds</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_nasfunds ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_nasfunds"
                                                wire:model="create_nasfunds">
                                            <label class="custom-control-label" for="create_nasfunds">Can Create New
                                                Nasfund</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_nasfunds ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_nasfunds"
                                                wire:model="edit_nasfunds">
                                            <label class="custom-control-label" for="edit_nasfunds">Can Edit
                                                Nasfund</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_nasfunds ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_nasfunds"
                                                wire:model="delete_nasfunds">
                                            <label class="custom-control-label" for="delete_nasfunds">Can Delete
                                                Nasfund</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Nasfunds --}}
                             {{-- COMPENSATIONS (ALLOWANCES AND DEDUCTIONS) --}}
                             <div class="card">
                                <div class="card-body mt-2">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_compensations ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_compensations"
                                                wire:model="access_compensations">
                                            <label class="custom-control-label" for="access_compensations">Access to
                                                Compensations (Allowances & Deductions)</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_compensations ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_compensations"
                                                wire:model="create_nasfunds">
                                            <label class="custom-control-label" for="create_compensations">Can Create New
                                                Allowance or Deduction</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_compensations ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_compensations"
                                                wire:model="edit_compensations">
                                            <label class="custom-control-label" for="edit_compensations">Can Edit
                                                Allowance or Deduction</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_compensations ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_compensations"
                                                wire:model="delete_compensations">
                                            <label class="custom-control-label" for="delete_compensations">Can Delete
                                                Allowance or Deduction</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- COMPENSATIONS (ALLOWANCES AND DEDUCTIONS) --}}
                        </div>
                    </div>
                    {{-- HR MANAGEMENT --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <div class="custom-control custom-switch">
                                    <input {{ $access_hr_management ? 'checked' : '' }} type="checkbox"
                                        class="custom-control-input" id="access_hr_management"
                                        wire:model="access_hr_management" checked>
                                    <label class="custom-control-label" for="access_hr_management">HR
                                        Management</label>
                                </div>
                            </h3>
                            <div class="card-tools">
                                <!-- Collapse Button -->
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i></button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="card">
                                <div class="card-body">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_employees ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_employees"
                                                wire:model="access_employees">
                                            <label class="custom-control-label" for="access_employees">Access to
                                                Employees</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_employees ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_employees"
                                                wire:model="create_employees">
                                            <label class="custom-control-label" for="create_employees">Can Create New
                                                Employee</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_employees ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_employees"
                                                wire:model="edit_employees">
                                            <label class="custom-control-label" for="edit_employees">Can Edit
                                                Employee</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_employees ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_employees"
                                                wire:model="delete_employees">
                                            <label class="custom-control-label" for="delete_employees">Can Delete
                                                Employee</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_attendances ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_attendances"
                                                wire:model="access_attendances">
                                            <label class="custom-control-label" for="access_attendances">Access to
                                                Attendances
                                            </label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_logs_timesheets ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_logs_timesheets"
                                                wire:model="access_logs_timesheets">
                                            <label class="custom-control-label" for="access_logs_timesheets">Access to
                                                Attendance Logs | Time Sheets
                                            </label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $import_attendances ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="import_attendances"
                                                wire:model="import_attendances">
                                            <label class="custom-control-label" for="import_attendances">Can Import
                                                Attendance</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_attendance_adjustments ? 'checked' : '' }}
                                                type="checkbox" class="custom-control-input"
                                                id="access_attendance_adjustments"
                                                wire:model="access_attendance_adjustments">
                                            <label class="custom-control-label"
                                                for="access_attendance_adjustments">Can Adjust Attendance</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_leaves ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_leaves"
                                                wire:model="access_leaves">
                                            <label class="custom-control-label" for="access_leaves">Access to
                                                Leaves</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_leaves ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_leaves"
                                                wire:model="create_leaves">
                                            <label class="custom-control-label" for="create_leaves">Can Create New
                                                Leave</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_leaves ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_leaves"
                                                wire:model="edit_leaves">
                                            <label class="custom-control-label" for="edit_leaves">Can Edit
                                                Leave</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_leaves ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_leaves"
                                                wire:model="delete_leaves">
                                            <label class="custom-control-label" for="delete_leaves">Can Delete
                                                Leave</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_payroll ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_payroll"
                                                wire:model="access_payroll">
                                            <label class="custom-control-label" for="access_payroll">Access to
                                                Payroll</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_payroll ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_payroll"
                                                wire:model="create_payroll">
                                            <label class="custom-control-label" for="create_payroll">Can Create New
                                                Payroll</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_payroll ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_payroll"
                                                wire:model="edit_payroll">
                                            <label class="custom-control-label" for="edit_payroll">Can Edit
                                                Payroll</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_payroll ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_payroll"
                                                wire:model="delete_payroll">
                                            <label class="custom-control-label" for="delete_payroll">Can Delete
                                                Payroll</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_loans ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_loans"
                                                wire:model="access_loans">
                                            <label class="custom-control-label" for="access_loans">Access to
                                                Loans</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_loans ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_loans"
                                                wire:model="create_loans">
                                            <label class="custom-control-label" for="create_loans">Can Create New
                                                Loan</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_loans ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_loans" wire:model="edit_loans">
                                            <label class="custom-control-label" for="edit_loans">Can Edit
                                                Loan</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_loans ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_loans"
                                                wire:model="delete_loans">
                                            <label class="custom-control-label" for="delete_loans">Can Delete
                                                Loan</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_loan_types ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_loan_types"
                                                wire:model="access_loan_types">
                                            <label class="custom-control-label" for="access_loan_types">Access to
                                                Loan Types</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_loan_types ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_loan_types"
                                                wire:model="create_loan_types">
                                            <label class="custom-control-label" for="create_loan_types">Can Create New
                                                Loan Type</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_loan_types ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_loan_types"
                                                wire:model="edit_loan_types">
                                            <label class="custom-control-label" for="edit_loan_types">Can Edit
                                                Loan Type</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_loan_types ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_loan_types"
                                                wire:model="delete_loan_types">
                                            <label class="custom-control-label" for="delete_loan_types">Can Delete
                                                Loan Type</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_assets ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_assets"
                                                wire:model="access_assets">
                                            <label class="custom-control-label" for="access_assets">Access to
                                                Assets</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_assets ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_assets"
                                                wire:model="create_assets">
                                            <label class="custom-control-label" for="create_assets">Can Create New
                                                Asset</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_assets ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_assets"
                                                wire:model="edit_assets">
                                            <label class="custom-control-label" for="edit_assets">Can Edit
                                                Asset</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_assets ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_assets"
                                                wire:model="delete_assets">
                                            <label class="custom-control-label" for="delete_assets">Can Delete
                                                Asset</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_asset_types ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_asset_types"
                                                wire:model="access_asset_types">
                                            <label class="custom-control-label" for="access_asset_types">Access to
                                                Asset Types</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_asset_types ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_asset_types"
                                                wire:model="create_asset_types">
                                            <label class="custom-control-label" for="create_asset_types">Can Create
                                                New
                                                Asset Type</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_asset_types ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_asset_types"
                                                wire:model="edit_asset_types">
                                            <label class="custom-control-label" for="edit_asset_types">Can Edit
                                                Asset Type</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_asset_types ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_asset_types"
                                                wire:model="delete_asset_types">
                                            <label class="custom-control-label" for="delete_asset_types">Can Delete
                                                Asset Type</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- General Settings Managmenet --}}
                    <div class="card">
                        <div class="card-header">

                            <h3 class="card-title">
                                <div class="custom-control custom-switch">
                                    <input {{ $access_general_settings ? 'checked' : '' }} type="checkbox"
                                        class="custom-control-input" id="access_general_settings"
                                        wire:model="access_general_settings" checked>
                                    <label class="custom-control-label" for="access_general_settings">General Settings
                                        Management</label>
                                </div>
                            </h3>
                            <div class="card-tools">
                                <!-- Collapse Button -->
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i></button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="card">
                                <div class="card-body">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_email_templates ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_email_templates"
                                                wire:model="access_email_templates">
                                            <label class="custom-control-label" for="access_email_templates">Access to
                                                Email Templates</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_email_templates ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_email_templates"
                                                wire:model="create_email_templates">
                                            <label class="custom-control-label" for="create_email_templates">Can
                                                Create
                                                New
                                                Email Template</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_email_templates ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_email_templates"
                                                wire:model="edit_email_templates">
                                            <label class="custom-control-label" for="edit_email_templates">Can Edit
                                                Email Template</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_email_templates ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_email_templates"
                                                wire:model="delete_email_templates">
                                            <label class="custom-control-label" for="delete_email_templates">Can
                                                Delete
                                                Email Template</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card">
                                <div class="card-body">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_email_template_types ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_email_template_types"
                                                wire:model="access_email_template_types">
                                            <label class="custom-control-label"
                                                for="access_email_template_types">Access to
                                                Email Template Types</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_email_template_types ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_email_template_types"
                                                wire:model="create_email_template_types">
                                            <label class="custom-control-label" for="create_email_template_types">Can
                                                Create
                                                New
                                                Email Template Type</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_email_template_types ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_email_template_types"
                                                wire:model="edit_email_template_types">
                                            <label class="custom-control-label" for="edit_email_template_types">Can
                                                Edit
                                                Email Template Type</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_email_template_types ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_email_template_types"
                                                wire:model="delete_email_template_types">
                                            <label class="custom-control-label" for="delete_email_template_types">Can
                                                Delete
                                                Email Template Type</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="checkbox-list">
                                        <div class="custom-control custom-switch">
                                            <input {{ $access_email_variables ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="access_email_variables"
                                                wire:model="access_email_variables">
                                            <label class="custom-control-label" for="access_email_variables">Access to
                                                Email Variables</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $create_email_variables ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="create_email_variables"
                                                wire:model="create_email_variables">
                                            <label class="custom-control-label" for="create_email_variables">Can
                                                Create
                                                New
                                                Email Variable</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $edit_email_variables ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="edit_email_variables"
                                                wire:model="edit_email_variables">
                                            <label class="custom-control-label" for="edit_email_variables">Can Edit
                                                Email Variables</label>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input {{ $delete_email_variables ? 'checked' : '' }} type="checkbox"
                                                class="custom-control-input" id="delete_email_variables"
                                                wire:model="delete_email_variables">
                                            <label class="custom-control-label" for="delete_email_variables">Can
                                                Delete
                                                Email Variables</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    </script>
@endpush
