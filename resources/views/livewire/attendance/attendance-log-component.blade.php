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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
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
                                        <button wire:click="generate" class="btn btn-primary">Generate</button>
                                    </div>
                                </div>

                                {{-- <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control"
                                            placeholder="Search term here" wire:model.live.debounce.500="search">
                                    </div>
                                </div> --}}

                                <div class="col-md-8">
                                    {{-- <div class="form-group">
                                        <input type="text" class="form-control form-control"
                                            placeholder="Search term here" wire:model.live.debounce.500="search">
                                    </div> --}}
                                    @if (!empty($selectedRows))
                                        <div class="btn-group float-right" role="group" aria-label="Groups">
                                            <button wire:click="export()" type="button"
                                                class="btn btn-success btn-md mr-2"><i class="fa fa-file-excel"
                                                    aria-hidden="true"></i> Export</button>
                                            <button wire:click="generatePdf" type="button" class="btn btn-danger btn-md mr-2"><i
                                                    class="fa fa-file-pdf" aria-hidden="true"></i> PDF</button>
                                        </div>
                                    @endif
                                </div>
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

                                <table class="table table-sm table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center align-middle">
                                                <div class="icheck-primary d-inline"><input id="selectAll"
                                                        type="checkbox" wire:model.live="selectAll"><label
                                                        for="selectAll"></div>
                                            </th>
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
                                                {{-- @php

                                                    $isHoliday = $holidays
                                                        ->where('holiday_date', $dr['full_date'])
                                                        ->isNotEmpty();

                                                @endphp --}}

                                                @if ($dr['is_holiday'])
                                                    <th class="text-center bg-success">
                                                        <small class="text-center text-bold">{{ $dr['day'] }}</small>
                                                    </th>
                                                @else
                                                    <th
                                                        class="text-center {{ $dr['is_sunday'] ? 'bg-danger' : '' }}">
                                                        <small class="text-center text-bold">{{ $dr['day'] }}</small>
                                                    </th>
                                                @endif

                                            @empty
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                            @endforelse

                                        </tr>
                                        <tr>
                                            @forelse ($ranges as $dr)
                                                {{-- @php

                                                    $isHoliday = $holidays
                                                        ->where('holiday_date', $dr['full_date'])
                                                        ->isNotEmpty();

                                                @endphp --}}

                                                @if ($dr['is_holiday'])
                                                    <th class="text-center bg-success">
                                                        <small class="text-center text-bold">{{ $dr['date'] }}</small>
                                                    </th>
                                                @else
                                                    <th
                                                        class="text-center {{ $dr['is_sunday'] ? 'bg-danger' : '' }}">
                                                        <small class="text-center text-bold">{{ $dr['date'] }}</small>
                                                    </th>
                                                @endif

                                            @empty
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                                <th>---</th>
                                            @endforelse

                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse ($records as $data)
                                            <tr>
                                                <td class="text-center align-middle">
                                                    <div class="icheck-primary d-inline">
                                                        <input id="business-{{ $data['id'] }}" type="checkbox"
                                                            wire:model.live="selectedRows" value="{{ $data['id'] }}">
                                                        <label for="business-{{ $data['id'] }}"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $data['employee_number'] }}</td>
                                                <td class="text-start">{{ $data['employee_name'] }}</td>
                                                <td class="text-center">{{ $data['regular'] }}</td>
                                                <td class="text-center">{{ $data['ot_hours'] }}</td>
                                                <td class="text-center">{{ $data['sunday_ot'] }}</td>
                                                <td class="text-center">{{ $data['holiday'] }}</td>
                                                @forelse ($data['days'] as $item)
                                                    <td  class="text-center {{ $item['is_sunday'] ? 'bg-danger' : '' }} {{ $item['is_holiday'] ? 'bg-success' : '' }}">{{ $item['daily_hours'] }}</td>
                                                @empty
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
