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
                        <li class="breadcrumb-item"><a  href="{{ route('dashboard') }}">Dashboard</a></li>
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
            {{-- <form wire:submit.prevent="save"> --}}
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="description">Tax Description</label>
                                <input wire:model="description" type="text"
                                    class="form-control form-control-lg @error('description') in-valid @enderror">
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="effective_date">Effectivity Date</label>
                                <input wire:model="effective_date" type="date" class="form-control form-control-lg">
                                @error('effective_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <input type="hidden" wire:model="editId">
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="col-md-6">
                                <small>To create a tax table, first gather all the salary ranges and tax rates. Next, sort the salary ranges from lowest to highest. As you insert the ranges into the table, ensure they are in ascending order and that there are no gaps or overlaps. Organize the data into columns, with one for the salary range and one for the tax rate. Finally, review the table to ensure it is accurate and complete.</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <label for="">Add Salary Ranges with Percentage</label>
                            @foreach ($ranges as $key => $range)
                                {{-- @if ($key > 0) --}}
                                <div class="row range" wire:key="range-{{ $key }}">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input placeholder="Description"
                                                wire:model="ranges.{{ $key }}.description" type="text"
                                                class="form-control">
                                        </div>
                                    </div>
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
                                    <div class="col-md-2">
                                        <div class="form-group">

                                            <input placeholder="Percentage"
                                                wire:model="ranges.{{ $key }}.percentage" type="number"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
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
                    <button wire:click="save" class="btn btn-primary">
                        Save Changes
                    </button>
                </div>
            </div>
        {{-- </form> --}}

            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead class="table-dark">
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
                                    <td class="text-start"><input type="checkbox" wire:model.prevent="selectedRows"
                                            value="{{ $data->id }}"></td>
                                    <td class="text-start">{{ $data->description }}</td>
                                    <td class="text-start">{{ $data->effective_date }}</td>
                                    <td width="60%" class="text-start">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th>Ranges</th>
                                                    <th>Percentage</th>
                                                </tr>
                                            </thead>
                                            @forelse ($data->tax_table_ranges as $item)
                                                <tr>
                                                    <td>{{ $item->description }}</td>
                                                    <td class="text-start">from <span class="badge badge-info">K
                                                            {{ number_format($item->from, 2, '.', ',') }} </span>
                                                        @if ($item->to == null)
                                                            and Above
                                                        @else
                                                        to
                                                        <span class="badge badge-info">K
                                                            {{ number_format($item->to, 2, '.', ',') }}</span>
                                                        @endif
                                                        </td>
                                                    <td class="text-start" width="20%">{{ $item->percentage }} %
                                                    </td>
                                                </tr>
                                            @empty
                                            @endforelse
                                        </table>

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
                                <td colspan="6">
                                    <livewire:no-data-found />
                                </td>
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
