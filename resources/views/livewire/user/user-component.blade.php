<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">User Management</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">User Management</li>
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
                                <a wire:navigate href="{{ route('roles') }}" type="button"
                                    class="btn btn-success btn-sm mr-2"><i class="fa fa-tasks" aria-hidden="true"></i>
                                    Roles</a>
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
                                <thead>
                                    <tr>
                                        <th class="text-start"><input type="checkbox" wire:model.live="selectAll"></th>
                                        <th class="text-start">Name</th>
                                        <th class="text-start">Email</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-start">Roles</th>
                                        <th class="text-start">Business Handled</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center align-items-center">
                                            <div wire:loading wire:target="search"><livewire:table-loader /></div>
                                        </td>
                                    </tr>
                                    @forelse ($records as $data)
                                        <tr wire:key="search-{{ $data->id }}">
                                            <td class="text-start"><input type="checkbox"
                                                    wire:model.prevent="selectedRows" value="{{ $data->id }}"></td>
                                            <td class="text-start">{{ $data->name }}</td>
                                            <td class="text-start">{{ $data->email }}</td>
                                            <td class="text-center">@livewire('active-status', ['model' => $data, 'field' => 'is_active'], key($data->id))</td>

                                            <td width="20%" class="text-start">
                                                @forelse ($data->roles as $role)
                                                    <span class="badge badge-success">{{ $role->name }}</span>
                                                @empty
                                                    <span class="badge badge-danger">no role</span>
                                                @endforelse
                                            </td>
                                            <td width="40%" class="text-start">
                                                @forelse ($data->businesses as $busi)
                                                    <span class="badge badge-primary">{{ $busi->name }}</span>
                                                @empty
                                                    <span class="badge badge-danger">no department</span>
                                                @endforelse
                                            </td>
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
                                            <td colspan="6">
                                                <livewire:no-data-found />
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
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
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">{{ $modalTitle }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Name</label>
                        <input wire:model="name" type="text"
                            class="form-control form-control-lg @error('name') is-invalid @enderror">

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input wire:model="email" type="text"
                            class="form-control form-control-lg @error('email') is-invalid @enderror">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        @if ($updateMode)
                            <small>If you don't want to change the password, leave it blank</small>
                        @endif
                        <input wire:model="password" type="text"
                            class="form-control form-control-lg @error('password') is-invalid @enderror">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Roles</label>
                        <select wire:model="role" class="form-control @error('role') is-invalid @enderror">">
                            <option value="">Choose Role ...</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group" wire:ignore>
                        <label>Assign Businesses</label>
                        <table class="table table-sm table-condensed">
                            <tbody>
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
