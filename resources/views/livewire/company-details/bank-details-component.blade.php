<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Company Banks</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a  href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Company Banks</li>
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
                        <div class="card-header">
                            <div class="btn-group float-right" role="group" aria-label="Groups">
                                <button type="button" class="btn btn-warning btn-sm mr-2"><i class="fa fa-upload"
                                        aria-hidden="true"></i> Import</button>
                                <button wire:click="export()" type="button" class="btn btn-success btn-sm mr-2"><i
                                        class="fa fa-file-excel" aria-hidden="true"></i> Export</button>
                                <button type="button" class="btn btn-danger btn-sm mr-2"><i class="fa fa-file-pdf"
                                        aria-hidden="true"></i> PDF</button>
                                <button wire:click="addNew()" type="button" class="btn btn-primary btn-sm mr-2"><i
                                        class="fa fa-plus" aria-hidden="true"></i> Add New</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <select class="form-control form-control" wire:model="perPage">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control"
                                            placeholder="Search term here" wire:model.live.debounce.500="search">
                                    </div>
                                </div>
                            </div>


                            <table class="table table-condensed table-sm table-hover">
                                <thead class="table-info">
                                    <tr>
                                        <th width="3%" class="text-start">
                                            <div class="icheck-primary d-inline"><input id="selectAll" type="checkbox"
                                                    wire:model.live="selectAll"><label for="selectAll"></div>
                                        </th>
                                        <th class="text-center">Bank Name</th>
                                        <th class="text-center">Account Name</th>
                                        <th class="text-center">Account_Number</th>
                                        <th class="text-center">BSB</th>
                                        <th width="30%" class="text-start">Business</th>
                                        <th width="10%" class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="8" class="text-center align-items-center">
                                            <div wire:loading wire:target="search"><livewire:table-loader /></div>
                                        </td>
                                    </tr>
                                    @forelse ($records as $data)
                                        <tr wire:key="search-{{ $data->id }}">
                                            <td width="3%" class="text-start align-middle">
                                                <div class="icheck-primary d-inline">
                                                    <input id="bankDetails-{{ $data->id }}" type="checkbox"
                                                        wire:model.live="selectedRows" value="{{ $data->id }}">
                                                    <label for="bankDetails-{{ $data->id }}"></label>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $data->bank_name }}</td>
                                            <td class="text-center">{{ $data->account_name }}</td>
                                            <td class="text-start">{{ $data->account_number }}</td>
                                            <td class="text-start">{{ $data->account_bsb }}</td>
                                            <td class="text-start">
                                                @forelse ($data->businesses as $business)
                                                    <span class="badge badge-primary">{{ $business->name }}</span>
                                                @empty
                                                    <span class="badge badge-danger">no business</span>
                                                @endforelse
                                            </td>
                                            <td class="text-center">@livewire('active-status', ['model' => $data, 'field' => 'is_active'], key($data->id))</td>
                                            <td class="text-center">
                                                <div class="btn-group">
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
                                            <td colspan="8">
                                                <livewire:no-data-found />
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="table-info">
                                    <tr>
                                        <th width="3%" class="text-start">
                                            <div class="icheck-primary d-inline"><input id="selectAll" type="checkbox"
                                                    wire:model.live="selectAll"><label for="selectAll"></div>
                                        </th>
                                        <th class="text-center">Bank Name</th>
                                        <th class="text-center">Account Name</th>
                                        <th class="text-center">Account_Number</th>
                                        <th class="text-center">BSB</th>
                                        <th width="30%" class="text-start">Business</th>
                                        <th width="10%" class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $records->links() }}
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">{{ $modalTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="submit()">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="">Bank Name</label>
                            <input wire:model="bank_name" type="text"
                                class="form-control form-control-lg @error('account_name') is-invalid @enderror">

                            @error('account_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Account Name</label>
                            <input wire:model="account_name" type="text"
                                class="form-control form-control-lg @error('account_name') is-invalid @enderror">

                            @error('account_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="">Account Number</label>
                                <input wire:model="account_number" type="text"
                                    class="form-control form-control-lg @error('account_number') is-invalid @enderror">

                                @error('account_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-6">
                                <label for="">BSB</label>
                                <input wire:model="bsb" type="text"
                                    class="form-control form-control-lg @error('bsb') is-invalid @enderror">

                                @error('bsb')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group" wire:ignore>
                            <label>Select Businesses</label>
                            <table class="table table-sm table-condensed">
                                <tbody>
                                    <tr>
                                        <th width="10px" class="text-start"><input type="checkbox"
                                                wire:model.live="selectAllBusiness"></th>
                                        <th>Select All</th>
                                    </tr>
                                    @foreach ($businesses as $data)
                                        <tr>
                                            <td class="text-start"><input id="{{ $data->name }}" type="checkbox"
                                                    wire:model.prevent="selectedBusinessRows"
                                                    value="{{ $data->id }}"></td>
                                            <td><label for="{{ $data->name }}">{{ $data->name }}</label></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            @if ($updateMode == true)
                                <button wire:click.prevent="update()" class="btn btn-success">Update</button>
                            @else
                                <button wire:click.prevent="submit(false)" class="btn btn-primary">Save</button>
                                <button wire:click.prevent="submit(true)" class="btn btn-info">Save & Create
                                    New</button>
                            @endif
                        </div>
                    </div>
            </div>
        </div>
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
