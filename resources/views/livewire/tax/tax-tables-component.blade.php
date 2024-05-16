<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tax Table</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tax Table</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="description">Tax Description</label>
                                <input wire:model="description" type="text" class="form-control form-control-lg">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="effective_date">Effectivity Date</label>
                                <input wire:model="effective_date" type="date" class="form-control form-control-lg">
                            </div>
                        </div>
                        <input type="hidden" wire:model="editId">
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <label for="">Add Salary Ranges with Percentage</label>
                            @foreach ($ranges as $key => $range)
                                {{-- @if ($key > 0) --}}
                                <div class="row range" wire:key="range-{{ $key }}">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input placeholder="From" wire:model="ranges.{{ $key }}.from"
                                                type="number" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">

                                            <input placeholder="To" wire:model="ranges.{{ $key }}.to"
                                                type="number" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">

                                            <input placeholder="Percentage"
                                                wire:model="ranges.{{ $key }}.percentage" type="number"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            @if ($key == 0)
                                                <button wire:click.live="addRange" class="btn btn-primary">Add</button>
                                            @endif
                                            @if ($key > 0)
                                                <button wire:click.live="removeRange({{ $key }})"
                                                    class="btn btn-danger">Remove</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- @endif --}}
                            @endforeach
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <button wire:click="save" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">Save Changes</span>
                        <span wire:loading wire:target="save">
                            <span class="spinner-border spinner-border-sm" role="status"
                                aria-hidden="true"></span>
                            <span class="visually-hidden">Loading...</span>
                        </span>
                    </button>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead  class="table-dark">
                            <tr>
                                <th><input type="checkbox" wire:model.live="selectAll"></th>
                                <th class="text-start">Description</th>
                                <th class="text-start">Date</th>
                                <th>Salary Ranges</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center align-items-center">
                                    <div wire:loading wire:target="save"><livewire:table-loader /></div>
                                </td>
                            </tr>
                            @forelse ($records as $data)
                            <tr wire:key="search-{{ $data->id }}">
                                <td class="text-start"><input type="checkbox"
                                        wire:model.prevent="selectedRows" value="{{ $data->id }}"></td>
                                <td class="text-start">{{ $data->description }}</td>
                                <td class="text-start">{{ $data->effective_date }}</td>
                                <td></td>
                                <td class="text-center">@livewire('active-status', ['model' => $data, 'field' => 'is_active'], key($data->id))</td>

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

                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>


            {{-- <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="btn-group float-right" role="group" aria-label="Groups">
                                <button type="button" class="btn btn-warning btn-sm mr-2"><i class="fa fa-upload"
                                        aria-hidden="true"></i> Import</button>
                                <button wire:click="export()" type="button" class="btn btn-success btn-sm mr-2"><i class="fa fa-file-excel"
                                        aria-hidden="true"></i> Export</button>
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


                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-start"><input type="checkbox" wire:model.live="selectAll"></th>
                                        <th class="text-start">Description</th>
                                        <th class="text-center">Salary  Range</th>
                                        <th class="text-center">Percentage</th>
                                        <th class="text-center">Status</th>
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
                                            <td class="text-start">{{ $data->description }}</td>
                                            <td class="text-center">{{ number_format($data->from, 0, '.', ',') }} - {{ number_format($data->to, 0, '.', ',') }}</td>
                                            <td class="text-center">{{ $data->percentage }}% </td>
                                            <td class="text-center">@livewire('active-status', ['model' => $data, 'field' => 'is_active'], key($data->id))</td>

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
            </div> --}}
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
