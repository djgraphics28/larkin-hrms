<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Attendance</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="">
                            <input type="text" class="form-control form-control mr-2" placeholder="Search term here"
                                wire:model.live.debounce.500="search">
                        </li>
                        <li class=""><button wire:click="store" class="btn btn-primary">Save Changes</button></li>
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
                    <div class="card card-outline card-info">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Fortnight</label>
                                        <select class="form-control" wire:model="selectedFortnight"
                                            wire:change="getDays">
                                            <option value="">Select Fortnight</option>
                                            @foreach ($fortnights as $item)
                                                <option value="{{ $item->id }}">{{ $item->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <label for="">Days</label>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            @forelse ($days as $item)
                                                <label
                                                    class="btn {{ $item['day'] == 'Sun' ? 'bg-danger' : 'bg-info' }} {{ $item['date'] == $selectedDay ? 'active' : '' }} ">
                                                    <input {{ $item['date'] == $selectedDay ? 'checked' : '' }}
                                                        type="radio" name="options" id="option_{{ $loop->index + 1 }}"
                                                        value="{{ $item['date'] }}" wire:model.live="selectedDay"
                                                        wire:change="getRecords"
                                                        autocomplete="off">
                                                    {{ $item['day'] }} <br>
                                                    <small>{{ $item['date'] }}</small>
                                                </label>
                                            @empty
                                                <p>Please select Fortnight first!</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body p-0">
                            <table class="table table-sm table-hover table-strippe">
                                <thead class="table-dark">
                                    <tr>
                                        <th colspan="3"></th>
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
                                <tbody>
                                    <tr>
                                        <td colspan="10" class="text-center align-items-center">
                                            <div wire:loading wire:target="search,selectedDay"><livewire:table-loader /></div>
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
                                                <small>{{ $record->workshift->start }} -
                                                    {{ $record->workshift->end }}</small>
                                            </td>
                                            <td>
                                                @forelse ($record->attendances as $data)
                                                <input value="{{ $data->time_in }}" wire:model.live="records.{{ $index }}.time_in"
                                                type="text" class="form-control">
                                                @empty

                                                @endforelse

                                            </td>
                                            <td>
                                                <input wire:model.live="records.{{ $index }}.time_out"
                                                    type="text" class="form-control">
                                            </td>
                                            <td>
                                                <input wire:model.live="records.{{ $index }}.time_in_2"
                                                    type="text" class="form-control">
                                            </td>
                                            <td>
                                                <input wire:model.live="records.{{ $index }}.time_out_2"
                                                    type="text" class="form-control">
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
        })
    </script>
@endpush
