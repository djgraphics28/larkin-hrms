<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Fortnight Generator</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a  href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Fortnight Generator</li>
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
                <div class="col-md-5">
                    <div class="card">
                        <form wire:submit.prevent="generate">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">Select Date Start for FN</label>
                                    <input wire:model.live="fnStartDate" type="date"
                                        class="form-control @error('fnStartDate') is-invalid @enderror">
                                    @error('fnStartDate')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading.remove>Generate</span>
                                    <span wire:loading>
                                        Generating ...
                                    </span>
                                </button>
                                <button class="btn btn-default">Clear</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <div class="d-flex justify-content-center items-align-center">
                                    <div wire:loading>
                                        <div class="spinner-border" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-sm" wire:loading.remove>
                                <thead>
                                    <tr>
                                        <th class="text-center">FN#</th>
                                        <th class="text-center">Code</th>
                                        <th class="text-center">Period</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($records as $data)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $data->code }}</td>
                                            <td class="text-center text-bold">{{ \Carbon\Carbon::parse($data->start)->format('d-M') . ' - ' . \Carbon\Carbon::parse($data->end)->format('d-M') }}</td>
                                        </tr>
                                    @empty
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
