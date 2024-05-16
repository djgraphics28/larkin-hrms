<div>
    <div class="card">
        <form class="form-horizontal" wire:submit.prevent="submit">
            <div class="card-header">
                <h5>Leave Credit/Balance</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    @forelse ($records as $item)
                        <tr>
                            <td><label for="">{{ $item->name }}</label></td>
                            <td>
                                <input type="number" class="form-control">
                            </td>
                        </tr>
                    @empty
                        <p>No Records</p>
                    @endforelse
                </table>
            </div>
            <div class="card-footer">
                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        <button wire:click="submit" class="btn btn-primary float-right" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="submit">Save Changes</span>
                            <span wire:loading wire:target="submit">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span class="visually-hidden">Loading...</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <hr>

    <div class="card">
        <div class="card-header">
            <h5>Leave Histories</h5>
        </div>
        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Status</th>
                        <th>Leave Type</th>
                        <th>Date From</th>
                        <th>Date To</th>
                        <th class="text-center">Days with pay</th>
                        <th class="text-center">Days without pay</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leaves as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if ($item->status == 'Approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($item->status == 'Pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($item->status == 'Rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @elseif($item->status == 'Cancelled')
                                    <span class="badge bg-secondary">Cancelled</span>
                                @endif
                            </td>
                            <td>{{ $item->leave_type->name }}</td>
                            <td>{{ $item->date_from }}</td>
                            <td>{{ $item->date_to }}</td>
                            <td class="text-center">{{ $item->with_pay_number_of_days ?? 0 }}</td>
                            <td class="text-center">{{ $item->without_pay_number_of_days ?? 0 }}</td>
                            <td>{{ $item->reason }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No leave histories found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
