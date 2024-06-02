<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-4">
                    <h1 class="m-0">Create Attendance</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control @error('selectedFortnight') is-invalid @enderror"
                            wire:model="selectedFortnight" wire:change="getDays">
                            <option value="">Select Fortnight</option>
                            @foreach ($fortnights as $item)
                                <option value="{{ $item->id }}">{{ $item->code }} from
                                    {{ \Carbon\Carbon::parse($item->start)->format('M-d-Y') }} to
                                    {{ \Carbon\Carbon::parse($item->end)->format('M-d-Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-sm-2">
                    <select wire:model.live="selectedDepartment" class="form-control">
                        <option value="">All Departments</option>
                        @foreach ($departments as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div><!-- /.col -->
                <div class="col-sm-2">
                    <select wire:model.live="selectedDesignation" class="form-control">
                        <option value="">All Designations</option>
                        @foreach ($designations as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div><!-- /.col -->
                <div class="col-sm-4">
                    <div class="input-group float-right">
                        <input type="text" class="form-control mr-2" placeholder="Search Employee Number / Name"
                            wire:model.live.debounce.500ms="search">
                        <button wire:click="store" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="store">Save Changes</span>
                            <span wire:loading wire:target="store">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span class="visually-hidden">Loading...</span>
                            </span>
                        </button>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row mb-2">
                @if (count($selectedRows) > 0 && $selectedFortnight !== '')
                    <div class="col-md-6">
                        <div class="btn-group" role="group" aria-label="Auto Filler Button Group">
                            <button wire:click="autoFiller('whole_day')" type="button" class="btn btn-default mr-2">Whole Day</button>
                            <button wire:click="autoFiller('first_half')" type="button" class="btn btn-default mr-2">First Half</button>
                            <button wire:click="autoFiller('second_half')" type="button" class="btn btn-default mr-2">Second Half</button>
                            {{-- <button wire:click="autoFiller('whole_day')" type="button" class="btn btn-default mr-2">With Breaktime</button> --}}
                            {{-- <div class="custom-control custom-switch">
                                <input type="checkbox" role="switch" class="custom-control-input"
                                    id="BreakTimeFiller" wire:model.live="breakTimeFiller">
                                <label class="custom-control-label" for="BreakTimeFiller"></label>
                            </div> --}}
                            <button type="button" class="btn btn-default">Reset</button>
                            <p class="ml-3">{{ count($selectedRows) }} employee(s) selected</p>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="btn-group btn-group-toggle d-flex flex-wrap" data-toggle="buttons">
                                    @forelse ($days as $item)
                                        <label
                                            class="btn flex-fill {{ $item['day'] == 'Sun' ? 'bg-danger' : 'bg-info' }} {{ $item['date'] == $selectedDay ? 'active' : '' }}">
                                            <input {{ $item['date'] == $selectedDay ? 'checked' : '' }} type="radio"
                                                name="options" id="option_{{ $loop->index + 1 }}"
                                                value="{{ $item['date'] }}" wire:model.live="selectedDay"
                                                wire:change="getRecords" autocomplete="off">
                                            {{ $item['day'] }} <br>
                                            <small>{{ \Carbon\Carbon::parse($item['date'])->format('M-d') }}</small>
                                        </label>
                                    @empty
                                        <p>Please select Fortnight first!</p>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body p-0">
                            <div style="max-height: 650px; overflow-y: auto;">
                                <table class="table table-sm table-striped table-hover">
                                    <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
                                        <tr>
                                            <th rowspan="2" width="3%" class="text-center align-middle">
                                                <div class="icheck-primary d-inline"><input id="selectAll"
                                                        type="checkbox" wire:model.live="selectAll"><label
                                                        for="selectAll"></div>
                                            </th>
                                            <th colspan="4">FN:
                                                {{ \App\Models\Fortnight::find($selectedFortnight)->code ?? '' }} |
                                                Date:
                                                {{ \Carbon\Carbon::parse($selectedDay)->format('M-d-Y D') ?? '' }}</th>
                                            <th class="text-center" colspan="2">1st Half</th>
                                            <th class="text-center" colspan="2">2nd Half</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" width="7%">EmpNo</th>
                                            <th>Employee Name</th>
                                            <th class="text-center" width="10%">Workshift</th>
                                            <th class="text-center">With Break?</th>
                                            <th class="text-center" width="12%">Time In</th>
                                            <th class="text-center" width="12%">Time Out</th>
                                            <th class="text-center" width="12%">Time In</th>
                                            <th class="text-center" width="12%">Time Out</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-hover">
                                        <tr>
                                            <td colspan="10" class="text-center align-items-center">
                                                <div wire:loading
                                                    wire:target="autoFiller,search,selectedDay,selectedFortnight,selectedDepartment,selectedDesignation">
                                                    <livewire:table-loader />
                                                </div>
                                            </td>
                                        </tr>
                                        @forelse ($records as $index => $record)
                                            <tr
                                                >
                                                <td width="3%" class="text-start align-middle">
                                                    <div class="icheck-primary d-inline">
                                                        <input id="record-{{ $record->id }}" type="checkbox"
                                                            wire:model.live="selectedRows"
                                                            value="{{ $record->id }}">
                                                        <label for="record-{{ $record->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center" width="7%">{{ $record->employee_number }}
                                                </td>
                                                <td>{{ strtoupper($record->first_name) }}
                                                    {{ strtoupper($record->last_name) }}</td>
                                                <td class="text-center">
                                                    <small>{{ $record->workshift->title }}</small><br>
                                                    <small>{{ \Carbon\Carbon::parse($record->workshift->start)->format('h:i A') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($record->workshift->end)->format('h:i A') }}</small>
                                                </td>
                                                <td class="text-center">
                                                    @if ($selectedFortnight)
                                                        <div class="custom-control custom-switch" style="z-index: 0;">
                                                            <input @if ($attendances[$record->employee_number]['is_break'] == true) checked @endif
                                                                type="checkbox" role="switch"
                                                                class="custom-control-input"
                                                                wire:model="attendances.{{ $record->employee_number }}.is_break"
                                                                id="attendances.{{ $record->employee_number }}.is_break">
                                                            <label class="custom-control-label"
                                                                for="attendances.{{ $record->employee_number }}.is_break"></label>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($selectedFortnight)
                                                        <div class="form-group">
                                                            <input
                                                                wire:model="attendances.{{ $record->employee_number }}.time_in"
                                                                type="time"
                                                                class="form-control {{ $attendances[$record->employee_number]['time_in'] == null ? 'in-valid' : 'is-valid' }} @error('attendances.' . $record->employee_number . '.time_in') is-invalid @enderror">
                                                            @error('attendances.' . $record->employee_number .
                                                                '.time_in')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($selectedFortnight)
                                                        <div class="form-group">
                                                            <input
                                                                wire:model="attendances.{{ $record->employee_number }}.time_out"
                                                                type="time"
                                                                class="form-control {{ $attendances[$record->employee_number]['time_out'] == null ? 'in-valid' : 'is-valid' }} @error('attendances.' . $record->employee_number . '.time_out') is-invalid @enderror">
                                                            @error('attendances.' . $record->employee_number .
                                                                '.time_out')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($selectedFortnight)
                                                        <div class="form-group">
                                                            <input
                                                                wire:model="attendances.{{ $record->employee_number }}.time_in_2"
                                                                type="time"
                                                                class="form-control {{ $attendances[$record->employee_number]['time_in_2'] == null ? 'in-valid' : 'is-valid' }} @error('attendances.' . $record->employee_number . '.time_in_2') is-invalid @enderror">
                                                            @error('attendances.' . $record->employee_number .
                                                                '.time_in_2')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($selectedFortnight)
                                                        <div class="form-group">
                                                            <input
                                                                wire:model="attendances.{{ $record->employee_number }}.time_out_2"
                                                                type="time"
                                                                class="form-control {{ $attendances[$record->employee_number]['time_out_2'] == null ? 'in-valid' : 'is-valid' }} @error('attendances.' . $record->employee_number . '.time_out_2') is-invalid @enderror">
                                                            @error('attendances.' . $record->employee_number .
                                                                '.time_out_2')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    @endif
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Auto filler modal --}}
    <!-- Modal -->
    <div class="modal fade" id="autoFillerModal" tabindex="-1" role="dialog"
        aria-labelledby="autoFillerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="autoFillerModalLabel">Auto Filler</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

</div>



@push('scripts')
    <script>
        window.addEventListener('show-autofiller-modal', () => {
            $('#autoFillerModal').modal('show');
        });

        window.addEventListener('hide-autofiller-modal', () => {
            $('#autoFillerModal').modal('hide');
        });

        $(function() {
            //Timepicker
            $('.timepicker').datetimepicker({
                format: 'LT'
            })
        })
    </script>
@endpush
