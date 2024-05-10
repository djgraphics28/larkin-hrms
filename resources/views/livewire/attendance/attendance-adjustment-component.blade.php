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
                        <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Attendance Logs  | Timesheets</li>
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
                                                <option value="{{ $fn->code }}">{{ $fn->code }} --
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
                                    {{-- <button wire:click="addNew()" type="button" class="btn btn-primary btn-sm mr-2"><i
                                        class="fa fa-plus" aria-hidden="true"></i> Add New</button> --}}
                                </div>
                            </div>
                        @endif

                        <div class="card-body">
                            <div>
                                <div class="d-flex justify-content-center items-align-center">
                                    <div wire:loading wire:target="generate">
                                        <div class="spinner-border" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-hover table-bordered" wire:loading.remove>
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="text-start align-middle"><input type="checkbox" wire:model.live="selectAll"></th>
                                        <th rowspan="2" class="text-start align-middle"><small class="text-start text-bold">EMP. NO.</small></th>
                                        <th rowspan="2" class="text-start align-middle"><small class="text-start text-bold">EMPLOYEE NAME</small></th>
                                        <th rowspan="2" class="text-center align-middle"><small class="text-center text-bold">REG</small></th>
                                        <th rowspan="2" class="text-center align-middle"><small class="text-center text-bold">OT Hrs.(1.5)</small></th>
                                        <th rowspan="2" class="text-center align-middle"><small class="text-center text-bold">Sun OT.(2.0)</small></th>
                                        <th rowspan="2" class="text-center align-middle"><small class="text-center text-bold">HOL.</small></th>
                                        @forelse ($ranges as $dr)
                                            <th class="text-center"> <small
                                                    class="text-center text-bold">{{ $dr['day'] }}</small></th>
                                        @empty
                                            <th>No Records</th>
                                        @endforelse

                                    </tr>
                                    <tr>
                                        @forelse ($ranges as $dr)
                                            <th class="text-center">
                                                <small class="text-center text-bold">{{ $dr['date'] }}</small>
                                            </th>
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
                                                    wire:model.prevent="selectedRows" value="{{ $data->id }}"></td>
                                            <td class="text-start">{{ $data->name }}</td>
                                            <td class="text-center">@livewire('active-status', ['model' => $data, 'field' => 'is_active'], key($data->id)){{ $data->employees_count }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a wire:click="view({{ $data->id }})"
                                                        class="dropdown-item text-primary" href="javascript:void(0)"><i
                                                            class="fa fa-eye" aria-hidden="true"></i></a>
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
                                            <td rowspan="5" colspan="22" class="text-center"><i class="fa fa-ban"
                                                    aria-hidden="true"></i> No Result Found</td>
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
