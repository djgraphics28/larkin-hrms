<div>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th class="text-center">Status</th>
                <th class="text-center">Loan Type</th>
                <th class="text-center">Loan Amount</th>
                <th class="text-center">Date Requested</th>
                <th class="text-center">Approved By</th>
                <th class="text-center">Date Approved</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-center">
                        @if ($item->status == 'Approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($item->status == 'Pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($item->status == 'Rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @elseif($item->status == 'Cancelled')
                            <span class="badge bg-secondary">Cancelled</span>
                        @elseif($item->status == 'On-Hold')
                            <span class="badge bg-info">On-Hold</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->loan_type->name }}</td>
                    <td class="text-center">{{ $item->amount }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->date_requested)->format('F j, Y') }}</td>
                    <td class="text-center">{{ $item->approved_by ? Auth::user()->find($item->approved_by)->name : '' }}</td>
                    <td class="text-center">{{ $item->date_approved ? \Carbon\Carbon::parse($item->date_approved)->format('F j, Y') : '' }}</td>
                    <td>{{ $item->reason }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No loan histories found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
