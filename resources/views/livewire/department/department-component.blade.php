<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Departments</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a  href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Departments</li>
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
                                <button wire:click="export()" type="button" class="btn btn-success btn-md mr-2"><i
                                        class="fa fa-file-excel" aria-hidden="true"></i> Export</button>
                                {{-- <button type="button" class="btn btn-danger btn-sm mr-2"><i class="fa fa-file-pdf"
                                        aria-hidden="true"></i> PDF</button> --}}
                                <button wire:click="addNew()" type="button" class="btn btn-primary btn-md mr-2"><i
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
                                        <th class="text-start">Department Name</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Employees count</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center align-items-center">
                                            <div wire:loading wire:target="search" class="overlay dark">
                                                <livewire:table-loader /></div>
                                        </td>
                                    </tr>
                                    @forelse ($records as $data)
                                        <tr wire:key="search-{{ $data->id }}">
                                            <td width="3%" class="text-start align-middle">
                                                <div class="icheck-primary d-inline">
                                                    <input id="department-{{ $data->id }}" type="checkbox"
                                                        wire:model.live="selectedRows" value="{{ $data->id }}">
                                                    <label for="department-{{ $data->id }}"></label>
                                                </div>
                                            </td>
                                            <td class="text-start">{{ $data->name }}</td>
                                            <td class="text-center">@livewire('active-status', ['model' => $data, 'field' => 'is_active'], key($data->id))</td>
                                            <td class="text-center">{{ $data->employees_count }}</td>
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
                                            <td colspan="5">
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
                                        <th class="text-start">Department Name</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Employees count</th>
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">{{ $modalTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Department Name</label>
                        <input wire:model="name" type="text"
                            class="form-control form-control-lg @error('name') is-invalid @enderror">

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    @if ($updateMode == true)
                        <button wire:click.prevent="update()" class="btn btn-success">Update</button>
                    @else
                        <button wire:click.prevent="submit(false)" class="btn btn-primary">Save</button>
                        <button wire:click.prevent="submit(true)" class="btn btn-info">Save & Create New</button>
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
@endpush
