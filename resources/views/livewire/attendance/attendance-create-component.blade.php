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
                        <select class="form-control @error('selectedFortnight') is-invalid @enderror select2bs4"
                            wire:model="selectedFortnight" wire:change="getDays">
                            <option value="">Select Fortnight</option>
                            @foreach ($fortnights as $item)
                                <option value="{{ $item->id }}">{{ $item->code }} from {{ \Carbon\Carbon::parse($item->start)->format('M-d-Y') }} to {{ \Carbon\Carbon::parse($item->end)->format('M-d-Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-sm-2">
                    <select wire:model.live="selectedDepartment" class="form-control select2bs4">
                        <option value="">All Departments</option>
                        @foreach ($departments as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div><!-- /.col -->
                <div class="col-sm-2">
                    <select wire:model.live="selectedDesignation" class="form-control select2bs4">
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
                        <button {{ !$selectedFortnight ? 'disabled' : ''  }} wire:click="store" class="btn btn-primary">SAVE CHANGES</button>
                    </div>
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="btn-group btn-group-toggle d-flex flex-wrap" data-toggle="buttons">
                                    @forelse ($days as $item)
                                        <label
                                            class="btn flex-fill {{ $item['day'] == 'Sun' ? 'bg-danger' : 'bg-info' }} {{ $item['date'] == $selectedDay ? 'active' : '' }}">
                                            <input {{ $item['date'] == $selectedDay ? 'checked' : '' }}
                                                type="radio" name="options" id="option_{{ $loop->index + 1 }}"
                                                value="{{ $item['date'] }}" wire:model.live="selectedDay"
                                                wire:change="getRecords" autocomplete="off">
                                            {{ $item['day'] }} <br>
                                            <small>{{ \Carbon\Carbon::parse($item['date'])->format('M-d'); }}</small>
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
                                            <th colspan="3">FN: {{ \App\Models\Fortnight::find($selectedFortnight)->code ?? '' }} | Date: {{ \Carbon\Carbon::parse($selectedDay)->format('M-d-Y D') ?? '' }}</th>
                                            <th class="text-center" colspan="2">1st Half</th>
                                            <th class="text-center" colspan="2">2nd Half</th>
                                            <th class="text-center" colspan="3"></th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" width="7%">EmpNo</th>
                                            <th>Employee Name</th>
                                            <th class="text-center" width="10%">Workshift</th>
                                            <th class="text-center" width="12%">Time In</th>
                                            <th class="text-center" width="12%">Time Out</th>
                                            <th class="text-center" width="12%">Time In</th>
                                            <th class="text-center" width="12%">Time Out</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Late</th>
                                            <th class="text-center">Time Early</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-hover">
                                        <tr>
                                            <td colspan="10" class="text-center align-items-center">
                                                <div wire:loading
                                                    wire:target="search,selectedDay,selectedFortnight,selectedDepartment,selectedDesignation">
                                                    <livewire:table-loader />
                                                </div>
                                            </td>
                                        </tr>
                                        @forelse ($records as $index => $record)
                                            <tr>
                                                <td class="text-center" width="7%">{{ $record->employee_number }}
                                                </td>
                                                <td>{{ strtoupper($record->first_name) }}
                                                    {{ strtoupper($record->last_name) }}</td>
                                                <td class="text-center">
                                                    <small>{{ $record->workshift->title }}</small><br>
                                                    <small>{{ \Carbon\Carbon::parse($record->workshift->start)->format('h:i A') }} -
                                                        {{ \Carbon\Carbon::parse($record->workshift->end)->format('h:i A') }}</small>
                                                </td>
                                                <td>
                                                    @if($selectedFortnight)
                                                    <div class="form-group">
                                                        <input
                                                            wire:model="attendances.{{ $record->employee_number }}.time_in"
                                                            type="time"
                                                            class="form-control {{ $attendances[$record->employee_number]['time_in'] == null ? 'in-valid' : 'is-valid' }} @error('attendances.' . $record->employee_number . '.time_in') is-invalid @enderror">
                                                        @error('attendances.' . $record->employee_number . '.time_in')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($selectedFortnight)
                                                    <div class="form-group">
                                                        <input
                                                            wire:model="attendances.{{ $record->employee_number }}.time_out"
                                                            type="time"
                                                            class="form-control {{ $attendances[$record->employee_number]['time_out'] == null ? 'in-valid' : 'is-valid' }} @error('attendances.' . $record->employee_number . '.time_out') is-invalid @enderror">
                                                        @error('attendances.' . $record->employee_number . '.time_out')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($selectedFortnight)
                                                    <div class="form-group">
                                                        <input
                                                            wire:model="attendances.{{ $record->employee_number }}.time_in_2"
                                                            type="time"
                                                            class="form-control {{ $attendances[$record->employee_number]['time_in_2'] == null ? 'in-valid' : 'is-valid' }} @error('attendances.' . $record->employee_number . '.time_in_2') is-invalid @enderror">
                                                        @error('attendances.' . $record->employee_number . '.time_in_2')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($selectedFortnight)
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
                                                <td></td>
                                                <td></td>
                                                <td></td>
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
</div>



@push('scripts')
    <script>
        $(function() {
            //Timepicker
            $('.timepicker').datetimepicker({
                format: 'LT'
            })

            // $('.select2bs4').select2({
            //     theme: 'bootstrap4'
            // });
        })
    </script>
@endpush
