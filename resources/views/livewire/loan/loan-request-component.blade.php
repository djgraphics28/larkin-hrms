<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Loan/Cash Advance Request</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Loan/Cash Advance Request</li>
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
                                        class="fa fa-paper-plane" aria-hidden="true"></i> Loan/Cash Advance
                                    Request</button>
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
                                        <th class="text-start">Loan Type</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Date Requested</th>
                                        <th class="text-center">Approved By</th>
                                        <th class="text-center">Date Approved</th>
                                        <th class="text-start">Reason</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="10" class="text-center align-items-center">
                                            <div wire:loading wire:target="search"><livewire:table-loader /></div>
                                        </td>
                                    </tr>
                                    @forelse ($records as $data)
                                        <tr wire:key="search-{{ $data->id }}">
                                            <td class="text-center"><input type="checkbox"
                                                    wire:model.prevent="selectedRows" value="{{ $data->id }}"></td>
                                            <td class="text-center">
                                                @if ($data->status == 'Approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($data->status == 'Pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($data->status == 'Rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @elseif($data->status == 'On-Hold')
                                                    <span class="badge bg-info">On-Hold</span>
                                                @elseif($data->status == 'Cancelled')
                                                    <span class="badge bg-secondary">Cancelled</span>
                                                @endif
                                            </td>
                                            <td class="text-start">
                                                <strong>{{ $data->employee->employee_number }}</strong> -
                                                {{ $data->employee->first_name }} {{ $data->employee->last_name }}
                                            </td>
                                            <td class="text-start">{{ $data->loan_type->name }}</td>
                                            <td class="text-center">K{{ $data->amount }}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($data->date_requested)->format('F j, Y') }}</td>
                                            <td class="text-center">{{ $data->approved_by ? Auth::user()->find($data->approved_by)->name : '' }}</td>
                                            <td class="text-center">{{ $data->date_approved ? \Carbon\Carbon::parse($data->date_approved)->format('F j, Y') : '' }}</td>
                                            <td class="text-start">{{ $data->reason }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    @if ($data->status != "Approved")
                                                        <a wire:click="edit({{ $data->id }})"
                                                            class="dropdown-item text-warning" href="javascript:void(0)"><i
                                                                class="fa fa-edit" aria-hidden="true"></i></a>
                                                        <a wire:click="alertConfirm({{ $data->id }})"
                                                            class="dropdown-item text-danger" href="javascript:void(0)"><i
                                                                class="fa fa-trash" aria-hidden="true"></i></a>
                                                    @else
                                                        @if (Auth::user()->is_admin)
                                                            <a wire:click="edit({{ $data->id }})"
                                                                class="dropdown-item text-warning" href="javascript:void(0)"><i
                                                                    class="fa fa-edit" aria-hidden="true"></i></a>
                                                            <a wire:click="alertConfirm({{ $data->id }})"
                                                                class="dropdown-item text-danger" href="javascript:void(0)"><i
                                                                    class="fa fa-trash" aria-hidden="true"></i></a>
                                                        @else
                                                        <span><i
                                                            class="fa fa-lock text-warning" aria-hidden="true"></i></span>
                                                        @endif
                                                    @endif

                                                </div>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">{{ $modalTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="submit()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Select Employee:</label>
                                    <select wire:model="employee"
                                        class="form-control @error('employee') in-valid @enderror">
                                        <option value="">Choose Employee</option>
                                        @foreach ($employees as $data)
                                            <option value="{{ $data->id }}">{{ $data->employee_number }} -
                                                <b>{{ $data->first_name }} {{ $data->last_name }}</b> |
                                                {{ $data->designation->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Loan Type:</label>
                                    <select wire:model="loan_type"
                                        class="form-control @error('leave_type') in-valid @enderror">
                                        <option value="">Choose Loan Type</option>
                                        @foreach ($loanTypes as $type)
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
                            @if ($updateMode == true)
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Status:</label>
                                        <select wire:model="status"
                                            class="form-control @error('status') in-valid @enderror">
                                            <option value="">Choose Status</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Cancelled">Cancelled</option>
                                            <option value="Rejected">Rejected</option>
                                            <option value="On-Hold">On-Hold</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Amount:</label>
                                    <input wire:model="amount" type="number"
                                        class="form-control @error('amount') in-valid @enderror">
                                    @error('amount')
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
                                    <label for="">Payable for How many Months?:</label>
                                    <input wire:model="payable_for" type="number"
                                        class="form-control @error('payable_for') in-valid @enderror">
                                    @error('payable_for')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Percentage to be deducted every cut-off:</label>
                                    <input wire:model="percentage_to_be_deducted" type="number"
                                        class="form-control">

                                </div>
                            </div>
                        </div>
                        @if ($updateMode == true)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Date Requested:</label>
                                        <input wire:model="date_requested" type="date"
                                            class="form-control @error('date_requested') in-valid @enderror">
                                        @error('date_requested')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        @if ($updateMode == true)
                            <button wire:click.prevent="update()" class="btn btn-success">Update</button>
                        @else
                            <button wire:click.prevent="submit()" class="btn btn-primary">Submit</button>
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
