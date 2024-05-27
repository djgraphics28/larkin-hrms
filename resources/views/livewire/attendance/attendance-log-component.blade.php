<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Attendance Logs | Timesheets</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a  href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Attendance Logs | Timesheets</li>
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
                        <div class="card-body">
                            <div class="row">
                                {{-- <div class="col-md-1">
                                    <div class="form-group">
                                        <select class="form-control" wire:model.live="perPage">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <select class="form-control" wire:model.live="selectedFN">
                                            <option value="">Select Fortnight range</option>
                                            @foreach ($fortnights as $fn)
                                                <option value="{{ $fn->id }}">{{ $fn->code }} --
                                                    {{ \Carbon\Carbon::parse($fn->start)->format('d-M') . ' - ' . \Carbon\Carbon::parse($fn->end)->format('d-M') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <button wire:click="generate" class="btn btn-primary">Search</button>
                                    </div>
                                </div>


                                {{-- <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control"
                                            placeholder="Search term here" wire:model.live.debounce.500="search">
                                    </div>
                                </div> --}}
                            </div>


                        </div>
                        {{-- <div class="card-footer">
                            {{ $records->links() }}
                        </div> --}}
                    </div>
                </div>
                <!-- /.col-md-12 -->
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        @if (!empty($records))
                            <div class="card-header">
                                <div class="btn-group float-right" role="group" aria-label="Groups">
                                    <button type="button" class="btn btn-warning btn-sm mr-2"><i class="fa fa-upload"
                                            aria-hidden="true"></i> Import</button>
                                    <button wire:click="export()" type="button" class="btn btn-success btn-sm mr-2"><i
                                            class="fa fa-file-excel" aria-hidden="true"></i> Export</button>
                                    <button type="button" class="btn btn-danger btn-sm mr-2"><i class="fa fa-file-pdf"
                                            aria-hidden="true"></i> PDF</button>
                                </div>
                            </div>
                        @endif

                        <div class="card-body">
                            <div>
                                {{-- <div class="d-flex justify-content-center items-align-center">
                                    <div class="overlay-wrapper mt-10 mb-10" wire:loading wire:target="generate">
                                        <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                            <div class="text-bold pt-2">Loading...</div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div wire:loading wire:target="generate">
                                    <livewire:table-loader />
                                </div>

                                <table class="table table-sm table-hover table-bordered" wire:loading.remove>
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-start align-middle"><input type="checkbox"
                                                    wire:model.live="selectAll"></th>
                                            <th rowspan="2" class="text-center align-middle"><small
                                                    class="text-start text-bold">EMP. NO.</small></th>
                                            <th rowspan="2" class="text-start align-middle"><small
                                                    class="text-start text-bold">EMPLOYEE NAME</small></th>
                                            <th rowspan="2" class="text-center align-middle"><small
                                                    class="text-center text-bold">REG</small></th>
                                            <th rowspan="2" class="text-center align-middle"><small
                                                    class="text-center text-bold">OT Hrs.(1.5)</small></th>
                                            <th rowspan="2" class="text-center align-middle"><small
                                                    class="text-center text-bold">Sun OT.(2.0)</small></th>
                                            <th rowspan="2" class="text-center align-middle"><small
                                                    class="text-center text-bold">HOL.</small></th>
                                            @forelse ($ranges as $dr)
                                                @php

                                                    $isHoliday = $holidays
                                                        ->where('holiday_date', $dr['full_date'])
                                                        ->isNotEmpty();

                                                @endphp

                                                @if ($isHoliday)
                                                    <th class="text-center bg-success">
                                                        <small class="text-center text-bold">{{ $dr['day'] }}</small>
                                                    </th>
                                                @else
                                                    <th
                                                        class="text-center @if ($dr['day'] == 'Sun' || $dr['day'] == 'Sun2') bg-danger @endif">
                                                        <small class="text-center text-bold">{{ $dr['day'] }}</small>
                                                    </th>
                                                @endif

                                            @empty
                                                <th>No Records</th>
                                            @endforelse

                                        </tr>
                                        <tr>
                                            @forelse ($ranges as $dr)
                                                @php

                                                    $isHoliday = $holidays
                                                        ->where('holiday_date', $dr['full_date'])
                                                        ->isNotEmpty();

                                                @endphp

                                                @if ($isHoliday)
                                                    <th class="text-center bg-success">
                                                        <small class="text-center text-bold">{{ $dr['date'] }}</small>
                                                    </th>
                                                @else
                                                    <th
                                                        class="text-center @if ($dr['day'] == 'Sun' || $dr['day'] == 'Sun2') bg-danger @endif">
                                                        <small class="text-center text-bold">{{ $dr['date'] }}</small>
                                                    </th>
                                                @endif

                                            @empty
                                                <th>No Records</th>
                                            @endforelse

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @include('shared.table-loader')

                                        @forelse ($records as $data)
                                            <tr>
                                                <td class="text-start"><input type="checkbox"
                                                        wire:model.prevent="selectedRows" value="{{ $data->id }}">
                                                </td>
                                                <td class="text-center">{{ $data->employee_number }}</td>
                                                <td class="text-start">{{ $data->first_name }} {{ $data->last_name }}
                                                </td>

                                                @forelse ($data->employee_hours as $hours)
                                                    @if ($hours->fortnight_id === $ranges[0]['fortnight_id'])
                                                        <td>{{ $hours->regular_hr }}</td>
                                                        <td>{{ $hours->overtime_hr }}</td>
                                                        <td>{{ $hours->sunday_ot_hr }}</td>
                                                        <td>{{ $hours->holiday_ot_hr }}</td>
                                                    @endif

                                                @empty
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                @endforelse



                                                @forelse ($ranges as $dr)
                                                    @php
                                                        $found = false;

                                                        // echo $data;
                                                    @endphp

                                                    @foreach ($data->attendances as $attendance)

                                                        @if ($dr['fortnight_id'] === $attendance->fortnight_id && $attendance->date === $dr['full_date'])
                                                            {{-- <td>{{ $hour_diff_total }}</td> --}}
                                                            <td>{{ \App\Helpers\Helpers::compute_daily_hr($attendance->date,$attendance->time_in,$attendance->time_out,$attendance->time_in_2,$attendance->time_out_2,$attendance->is_break) }}</td>
                                                            @php
                                                                $found = true;
                                                            @endphp
                                                        @endif

                                                    @endforeach

                                                    @if (!$found)
                                                        <td>0</td>
                                                    @endif

                                                @empty
                                                    <td>0</td>
                                                @endforelse

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="22">
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
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
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
        <script>
            $(function() {
                $('.select2').select2()

                //Initialize Select2 Elements
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })
            })
        </script>
    @endpush
