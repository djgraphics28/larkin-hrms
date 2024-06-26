<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Assets</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a  href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Assets</li>
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
                                <a  href="{{ route('asset-type') }}" type="button"
                                    class="btn btn-success btn-sm mr-2"><i class="fa fa-tasks" aria-hidden="true"></i>
                                    Asset Type</a>
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
                                <thead>
                                    <tr>
                                        <th class="text-start"><input type="checkbox" wire:model.live="selectAll"></th>
                                        <th class="text-start">Asset Code</th>
                                        <th class="text-start">Asset Name</th>
                                        <th class="text-start">Asset Type</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-start">Employee</th>
                                        <th class="text-start">Date Received</th>
                                        <th class="text-start">Date Returned</th>
                                        <th class="text-center">Is Working?</th>
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
                                            <td class="text-start"><input type="checkbox"
                                                    wire:model.prevent="selectedRows" value="{{ $data->id }}"></td>
                                            <td class="text-start">{{ $data->asset_code }}</td>
                                            <td class="text-start">{{ $data->name }}</td>
                                            <td class="text-start">{{ $data->asset_type->name }}</td>
                                            <td class="text-center">{{ $data->quantity }}</td>
                                            <td class="text-start">
                                                {{ $data->employee->full_name_with_emp_no ?? 'Not yet Assigned' }}</td>
                                            <td class="text-start">{{ $data->date_received }}</td>
                                            <td class="text-start">{{ $data->date_returned }}</td>
                                            <td class="text-center">
                                                @php
                                                    switch ($data->is_working) {
                                                        case 'yes':
                                                            $statusLabel = 'Working';
                                                            $badgeClass = 'badge-success';
                                                            break;
                                                        case 'no':
                                                            $statusLabel = 'Not Working';
                                                            $badgeClass = 'badge-danger';
                                                            break;
                                                        case 'maintenance':
                                                            $statusLabel = 'Maintenance';
                                                            $badgeClass = 'badge-warning';
                                                            break;
                                                        default:
                                                            $statusLabel = 'Unknown';
                                                            $badgeClass = 'badge-secondary';
                                                    }
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                                            </td>
                                            <td class="text-center">
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
                                            <td colspan="9">
                                                <livewire:no-data-found />
                                            </td>
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
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">{{ $modalTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Asset Code</label>
                        <input readonly wire:model="asset_code" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Asset Name</label>
                        <input wire:model="name" type="text"
                            class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Serial Number</label>
                        <input wire:model="serial_number" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Quantity</label>
                        <input wire:model="quantity" type="number" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Is Working?</label><br>
                        <div class="form-check form-check-inline">
                            <input {{ $is_working == "yes" ? 'checked' : '' }}  wire:model="is_working" class="form-check-input" type="radio"
                                name="inlineRadioOptions" id="inlineRadio1" value="yes">
                            <label class="form-check-label" for="inlineRadio1">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input {{ $is_working == "no" ? 'checked' : '' }} wire:model="is_working" class="form-check-input" type="radio"
                                name="inlineRadioOptions" id="inlineRadio2" value="no">
                            <label class="form-check-label" for="inlineRadio2">No</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input {{ $is_working == "maintenance" ? 'checked' : '' }} wire:model="is_working" class="form-check-input" type="radio"
                                name="inlineRadioOptions" id="inlineRadio3" value="maintenance">
                            <label class="form-check-label" for="inlineRadio3">Maintenance</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Asset Type</label>
                        <select wire:model="asset_type"
                            class="form-control @error('asset_type') is-invalid @enderror">
                            <option value="">Choose Asset Type ...</option>
                            @foreach ($assetTypes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('asset_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Assign Employee</label>
                        @livewire('shared.search-employee')
                        <input class="form-control text-center" disabled type="text" placeholder="Search Employee First" wire:model.live="employeeName">
                        {{-- <select wire:model="employee" class="form-control">
                            <option value="">Choose Employee ...</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->employee_number }} -
                                    {{ $employee->first_name }} {{ $employee->last_name }}</option>
                            @endforeach
                        </select> --}}
                    </div>
                    <div class="form-group">
                        <label for="">Note</label>
                        <textarea wire:model="note" cols="30" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Date Received</label>
                                <input wire:model="date_received" type="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Date Returned</label>
                                <input wire:model="date_returned" type="date" class="form-control">
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
