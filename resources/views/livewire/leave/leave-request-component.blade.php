<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Leave Request</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Leave Request</li>
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
                                <button wire:click="addNew()" type="button" class="btn btn-primary btn-sm mr-2"><i
                                        class="fa fa-paper-plane" aria-hidden="true"></i> Request Leave</button>
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
                                        <th class="text-center"><input type="checkbox" wire:model.live="selectAll"></th>
                                        <th class="text-center">Status</th>
                                        <th class="text-start">Employee Details</th>
                                        <th class="text-start">Date Filed</th>
                                        <th class="text-start">Leave Type</th>
                                        <th class="text-start">Date From</th>
                                        <th class="text-start">Date To</th>
                                        <th class="text-center">With Pay No. of Days</th>
                                        <th class="text-center">W/out Pay No. of Days</th>
                                        <th class="text-start">Reason</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="11" class="text-center align-items-center">
                                            <div wire:loading wire:target="search"><livewire:table-loader /></div>
                                        </td>
                                    </tr>
                                    @forelse ($records as $data)
                                        <tr>
                                            <td class="text-start"><input type="checkbox"
                                                    wire:model.prevent="selectedRows" value="{{ $data->id }}"></td>
                                            <td class="text-start">
                                                @if ($data->status == 'Approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($data->status == 'Pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($data->status == 'Rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @elseif($data->status == 'Cancelled')
                                                    <span class="badge bg-secondary">Cancelled</span>
                                                @endif
                                            </td>
                                            <td class="text-start">
                                                <strong>{{ $data->employee->employee_number }}</strong> -
                                                {{ $data->employee->first_name }} {{ $data->employee->last_name }}
                                            </td>
                                            <td class="text-start">{{ $data->created_at }}</td>
                                            <td class="text-start">{{ $data->leave_type->name }}</td>
                                            <td class="text-start">{{ $data->date_from }}</td>
                                            <td class="text-start">{{ $data->date_to }}</td>
                                            <td class="text-center">{{ $data->with_pay_number_of_days }}</td>
                                            <td class="text-center">{{ $data->without_pay_number_of_days }}</td>
                                            <td class="text-start">{{ $data->reason }}</td>
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
                                            <td colspan="11">
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
        <div class="modal-dialog modal-xl" role="document">
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
                            @livewire('shared.search-employee')
                        </div>
                        @if ($employeeData)
                            <div class="card card-outline card-primary">
                                <div class="card-body">
                                    <div class="row">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td width="5%"><img width="100px"
                                                        src="{{ asset('assets/images/male.png') }}" alt="Photo">
                                                </td>
                                                <td width="20%">
                                                    <label for="">{{ $employeeData->first_name }}
                                                        {{ $employeeData->last_name }}</label><br>
                                                    <label for="">{{ $employeeData->employee_number }}
                                                    </label><br>
                                                    <label for="">{{ $employeeData->email }} </label><br>
                                                </td>
                                                <td width="10%">
                                                    <label for="">Leave Credits</label>
                                                </td>
                                                <td width="25%">
                                                    <br>
                                                    @forelse ($employeeData->leave_credits as $lc)
                                                    @empty
                                                        @foreach ($leaveTypes as $lt)
                                                            <label for="">{{ $lt->name }} - 0</label><br>
                                                        @endforeach
                                                    @endforelse
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        @endif

                        <div class="card card-outline card-warning">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Leave Type:</label>
                                            <select wire:model="leave_type"
                                                class="form-control @error('leave_type') in-valid @enderror">
                                                <option value="">Choose Leave Type</option>
                                                @foreach ($leaveTypes as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('leave_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Date From:</label>
                                            <input wire:model="date_from" type="date"
                                                class="form-control @error('date_from') in-valid @enderror">
                                            @error('date_from')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Date To:</label>
                                            <input wire:model="date_to" type="date"
                                                class="form-control @error('date_to') in-valid @enderror">
                                            @error('date_to')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Reason:</label>
                                            <textarea wire:model="reason" class="form-control @error('reason') in-valid @enderror" name="" id=""
                                                cols="30" rows="3"></textarea>
                                            @error('reason')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
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
                            <button wire:click.prevent="submit(false)" class="btn btn-primary">Submit</button>
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
