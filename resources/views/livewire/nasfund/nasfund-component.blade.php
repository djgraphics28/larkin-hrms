<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Nasfund</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a  href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Nasfund</li>
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
                                            <th  class="text-center align-middle">
                                                <small class="text-start text-bold">EMP. NO.</small>
                                            </th>
                                            <th  class="text-start align-middle">
                                                <small class="text-start text-bold">LAST NAME</small>
                                            </th>
                                            <th class="text-center align-middle">
                                                <small class="text-center text-bold">FIRST NAME</small>
                                            </th>
                                            <th  class="text-center align-middle">
                                                <small class="text-center text-bold">EMPLOYMENT DATE</small>
                                            </th>
                                            <th  class="text-center align-middle">
                                                <small class="text-center text-bold">NPF NUMBER</small>
                                            </th>
                                            <th  class="text-center align-middle">
                                                <small class="text-center text-bold">EMPLOYER RN</small>
                                            </th>
                                            <th  class="text-center align-middle">
                                                <small class="text-center text-bold">PAY</small>
                                            </th>
                                            <th  class="text-center align-middle">
                                                <small class="text-center text-bold">ER (8.4%)</small>
                                            </th>
                                            <th  class="text-center align-middle">
                                                <small class="text-center text-bold">EE (6%)</small>
                                            </th>
                                            <th  class="text-center align-middle">
                                                <small class="text-center text-bold">TOTAL</small>
                                            </th>

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
                                                <td class="text-center">{{ $data->employee_number }}</td>
                                                <td class="text-start">{{ $data->last_name }} </td>
                                                <td class="text-start">{{ $data->first_name }} </td>
                                                <td class="text-center">{{ date('d-M-y', strtotime($data->joining_date)) }} </td>
                                                <td class="text-center">{{ $data->nasfund_number }} </td>
                                                <td class="text-center"> </td>

                                                @forelse($data->nasfund as $nasfund)
                                                    @if((int)$nasfund->fortnight_id === $selectedFN && (int)$nasfund->employee_id === $data->id)
                                                        <td class="text-center">{{$nasfund->pay}}</td>
                                                        <td class="text-center">{{$nasfund->ER}}</td>
                                                        <td class="text-center">{{$nasfund->EE}}</td>
                                                        <td class="text-center">{{$nasfund->ER + $nasfund->EE}}</td>
                                                    @endif
                                                @empty
                                                    <td colspan="4" class="text-center">No Record</td>
                                                @endforelse

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
