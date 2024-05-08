<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Payslip | View</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Payslip | View</li>
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
                                        <button wire:click="show" class="btn btn-primary">View</button>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

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
                                <div class="d-flex justify-content-center items-align-center">
                                    <div class="overlay-wrapper mt-10 mb-10" wire:loading wire:target="generate">
                                        <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                            <div class="text-bold pt-2">Loading...</div>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-sm table-hover table-bordered" wire:loading.remove>
                                    <thead>

                                        <tr>

                                                <th rowspan="2" class="text-start align-middle"><input type="checkbox"
                                                        wire:model.live="selectAll"></th>
                                                <th rowspan="2" class="text-center align-middle">
                                                    <small class="text-bold">EMP. NO.</small></th>
                                                <th rowspan="2" class="text-center align-middle">
                                                    <small class="text-bold">EMPLOYEE NAME</small></th>
                                                <th colspan="6" class="text-center align-middle">
                                                    <small class="text-bold" style="color: green;">Gross</small>
                                                </th>
                                                <th colspan="4" class="text-center align-middle">
                                                    <small class="text-bold" style="color: red;">Deductions</small>
                                                </th>

                                        </tr>
                                        <tr>

                                                <th class="text-center align-middle">
                                                    <small class="text-bold">Regular</small>
                                                </th>
                                                <th class="text-center align-middle">
                                                    <small class="text-bold">Over Time</small>
                                                </th>
                                                <th class="text-center align-middle">
                                                    <small class="text-bold">Sunday OT</small>
                                                </th>
                                                <th class="text-center align-middle">
                                                    <small class="text-bold">Holiday OT</small>
                                                </th>
                                                <th class="text-center align-middle">
                                                    <small class="text-bold">PLP/ALP/FP</small>
                                                </th>
                                                <th class="text-center align-middle">
                                                    <small class="text-bold">Other</small>
                                                </th>

                                                <th class="text-center align-middle">
                                                    <small class="text-bold">FN Tax</small>
                                                </th>
                                                <th class="text-center align-middle">
                                                    <small class="text-bold">NPF (6%)</small>
                                                </th>
                                                <th class="text-center align-middle">
                                                    <small class="text-bold">NCSL</small>
                                                </th>
                                                <th class="text-center align-middle">
                                                    <small class="text-bold">Cash Advance</small>
                                                </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @include('shared.table-loader')

                                        @forelse ($payslip as $data)

                                            <tr>
                                                <td class="text-start"><input type="checkbox"
                                                        wire:model.prevent="selectedRows" value="{{ $data->id }}">
                                                </td>

                                                <td class="text-center">{{ $data->employee->employee_number }}</td>
                                                <td class="text-start">{{ $data->employee->first_name }} {{ $data->employee->last_name }}</td>

                                                <td class="text-center">{{ $data->regular }}</td>
                                                <td class="text-center">{{ $data->overtime }}</td>
                                                <td class="text-center">{{ $data->sunday_ot }}</td>
                                                <td class="text-center">{{ $data->holiday_ot }}</td>
                                                <td class="text-center">{{ $data->plp_alp_fp }}</td>
                                                <td class="text-center">{{ $data->other }}</td>

                                                <td class="text-center">{{ $data->fn_tax }}</td>
                                                <td class="text-center">{{ $data->npf }}</td>
                                                <td class="text-center">{{ $data->ncsl }}</td>
                                                <td class="text-center">{{ $data->cash_adv }}</td>

                                            </tr>

                                        @empty
                                            <tr>
                                                <td rowspan="5" colspan="22" class="text-center"><i
                                                        class="fa fa-ban" aria-hidden="true"></i> No Result Found</td>
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
