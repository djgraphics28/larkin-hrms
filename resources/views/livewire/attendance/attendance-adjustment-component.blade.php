<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Attendance Adjustment</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a wire:navigate href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Attendance Adjustment</li>
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
                    <form wire:submit.prevent="update">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-md-6">
                                    <small>To adjust attendance time, start by searching for the employee in the system.
                                        Once you've found them, select the date you need to update within their
                                        attendance record. Then, simply edit the time in or time out as required. It's a
                                        straightforward process that allows you to quickly make any necessary
                                        adjustments to the employee's attendance details.</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-md-6 mb-4">
                                    @livewire('shared.search-employee')
                                    {{-- <div class="input-group">
                                        <select wire:model.live="selectedEmployee" wire:change="searchEmployee"
                                            class="form-control form-control-lg">
                                            <option value="">Choose Employee ...</option>
                                            @forelse ($employees as $item)
                                                <option value="{{ $item->employee_number }}">
                                                    {{ $item->employee_number }} | {{ strtoupper($item->first_name) }}
                                                    {{ strtoupper($item->last_name) }}</option>
                                            @empty
                                                <option value="">NO OPTIONS</option>
                                            @endforelse
                                        </select>
                                    </div> --}}
                                </div>
                                @if ($employeeId !== '')
                                    <div class="info-box ml-2">
                                        <div wire:loading wire:target="searchEmployee,selectedEmployee">
                                            <div class="d-flex justify-content-center items-align-center">
                                                <div class="overlay-wrapper mt-10 mb-10">
                                                    <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                                        {{-- <div class="text-bold pt-2">Loading...</div> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <span class="info-box-icon bg-info"><i class="far fa-user"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><b>Employee Number:</b>
                                                {{ $employee->employee_number ?? '' }}</span>
                                            <span class="info-box-text"><b>Employee Name:</b>
                                                {{ $employee->first_name ?? '' }}
                                                {{ $employee->last_name ?? '' }}</span>
                                            <span class="info-box-text"><b>Workshift:</b>
                                                {{ $employee->workshift->title ?? '' }} |
                                                {{ $employee->workshift->start ?? '' }} -
                                                {{ $employee->workshift->end ?? '' }}</span>
                                        </div>
                                    </div>
                                @else
                                    <p>Select Employee first!</p>
                                @endif
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Choose Date ...</label>
                                            <input {{ $employeeId == '' && empty($employee) ? 'disabled' : '' }}
                                                wire:model="selectedDate" wire:change="searchAttendance" type="date"
                                                class="form-control form-control-lg">
                                        </div>
                                    </div>
                                </div>
                                @if ($selectedDate !== '')
                                    <div class="card card-outline card-info">
                                        <div wire:loading wire:target="searchAttendance">
                                            <div class="d-flex justify-content-center items-align-center">
                                                <div class="overlay-wrapper mt-10 mb-10">
                                                    <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                                        {{-- <div class="text-bold pt-2">Loading...</div> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($attendance)
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="notes">Reason to update ...</label>
                                                            <textarea wire:model="notes" id="notes" cols="30" rows="5" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="time_in">Time In 1st half</label>
                                                            <input wire:model="time_in" type="time" id="time_in"
                                                                class="form-control form-control-lg">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="time_out">Time Out 1st half</label>
                                                            <input wire:model="time_out" type="time" id="time_out"
                                                                class="form-control form-control-lg">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="time_in_2">Time In 2nd half</label>
                                                            <input wire:model="time_in_2" type="time" id="time_in_2"
                                                                class="form-control form-control-lg">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="time_out_2">Time Out 2nd half</label>
                                                            <input wire:model="time_out_2" type="time"
                                                                id="time_out_2" class="form-control form-control-lg">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="custom-control custom-switch" style="z-index: 0;">
                                                            <input @if ($is_break == true) checked @endif
                                                                type="checkbox" role="switch"
                                                                class="custom-control-input" wire:model="is_break"
                                                                id="is_break">
                                                            <label class="custom-control-label" for="is_break">With
                                                                Break?</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                @else
                                    <livewire:no-data-found />
                                @endif
                                @endif

                            </div>
                            <div class="card-footer">
                                <button wire:click="update" class="btn btn-primary" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="update">Save Changes</span>
                                    <span wire:loading wire:target="update">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        <span class="visually-hidden">Loading...</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
