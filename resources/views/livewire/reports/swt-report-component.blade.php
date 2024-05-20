<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">SWT Reports</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">SWT Reports</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fortnight">Select Month</label>
                        <select wire:model="month" id="month" class="form-control"
                            wire:change="generateSwt">
                            <option value="">Choose Fortnight ...</option>
                            @foreach ($fortnights as $item)
                                <option value="{{ $item->id }}">{{ $item->code }} from
                                    {{ \Carbon\Carbon::parse($item->start)->format('M-d-Y') }} to
                                    {{ \Carbon\Carbon::parse($item->end)->format('M-d-Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- <a class="btn btn-app bg-success">
                <i class="fas fa-file-excel"></i>Export Excel
            </a>
            <a class="btn btn-app bg-success">
                <i class="fas fa-file-csv"></i>Export CSV
            </a>
            <a class="btn btn-app bg-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a> --}}
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <p class="mb-0">LARKIN ENTERPRISES LIMITED <br>NASFUND</p>
                {{-- <p>{{ $selectedFortnight }}</p> --}}
            </div>
            <div class="ml-auto">
                <button class="btn btn-success btn-md"><i class="fas fa-file-excel"></i> Excel</button>
                <button class="btn btn-danger btn-md"><i class="fas fa-file-pdf"></i> PDF</button>
                <button class="btn btn-primary btn-md"><i class="fas fa-print"></i> PRINT</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div style="max-height: 650px; overflow-y: auto;">
                <table class="table table-sm table-striped">
                    <thead class="table-info" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th>Emp Code</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th class="text-center">Employement Date</th>
                            <th>NPF Number</th>
                            <th>Employer RN</th>
                            <th>Pay</th>
                            <th>ER(8.4%)</th>
                            <th>EE(6%)</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $item)
                            <tr>
                                <td>{{ $item->employee_number }}</td>
                                <td>{{ $item->last_name }}</td>
                                <td>{{ $item->first_name }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item->joining_date)->format('M-d-Y') }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
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
