<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Larkin Logo" />
    </a>

    <div class="sidebar">
        {{-- Sidebar user panel (optional)  --}}
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/images/user.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <span class="d-block text-white">{{ Auth::user()->name }}</span>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a wire:navigate href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('apps/*') ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)" class="nav-link {{ request()->is('apps/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>
                            Apps
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('business') }}"
                                class="nav-link {{ request()->is('apps/business') ? 'active' : '' }}">
                                <i class="far fa-building nav-icon"></i>
                                <p>
                                    Businesses
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('department') }}" class="nav-link {{ request()->is('apps/department') ? 'active' : '' }}">
                                <i class="far fa-user-circle nav-icon"></i>
                                <p>Departments</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('workshift') }}" class="nav-link {{ request()->is('apps/workshift') ? 'active' : '' }}">
                                <i class="far fa-user-circle nav-icon"></i>
                                <p>Workshift</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('fortnight-generator') }}" class="nav-link {{ request()->is('apps/fortnight-generator') ? 'active' : '' }}">
                                <i class="far fa-user-circle nav-icon"></i>
                                <p>Fortnight Generator</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('users') }}" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    User Managment
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- HR MANAGEMENT --}}
                <li class="nav-header">HR Management</li>
                <li class="nav-item {{ request()->is('employee/*') ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)" class="nav-link {{ request()->is('employee/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Employees
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- <li class="nav-item">
                            <a href="{{ route('employee.create') }}"
                                class="nav-link {{ request()->is('employee/create') ? 'active' : '' }}">
                                <i class="far fa fa-plus nav-icon"></i>
                                <p>Create New</p>
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('employee.index','all') }}"
                                class="nav-link {{ request()->is('employee/all/employees') || request()->is('employee/all/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    All
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('employee.index','national') }}"
                                class="nav-link {{ request()->is('employee/national/employees') || request()->is('employee/national/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    National
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('employee.index','expatriate') }}"
                                class="nav-link {{ request()->is('employee/expatriate/employees') || request()->is('employee/expatriate/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Expatriate
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('designation') }}"
                                class="nav-link {{ request()->is('employee/designation') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Designation/Position</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('employee-status') }}"
                                class="nav-link {{ request()->is('employee/status') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Employee Status</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ request()->is('attendance/*') ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)" class="nav-link {{ request()->is('attendance/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-business-time"></i>
                        <p>
                            Attendance
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('attendance-logs') }}" class="nav-link {{ request()->is('attendance/logs') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Logs | Timesheets</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('attendance-import') }}" class="nav-link {{ request()->is('attendance/import') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Import</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('attendance-adjustment') }}" class="nav-link {{ request()->is('attendance/adjustment') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Adjustment</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ request()->is('leave/*') ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)" class="nav-link {{ request()->is('leave/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-business-time"></i>
                        <p>
                            Leave
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('leave-request') }}" class="nav-link {{ request()->is('leave/request') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Leave Request</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('leave-types') }}" class="nav-link {{ request()->is('leave/types') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Leave Types</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- <li class="nav-item {{ request()->is('employee/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('employee/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Employees
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('employee.index') }}"
                                class="nav-link {{ request()->is('employee/all') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    All Employees
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.create') }}"
                                class="nav-link {{ request()->is('employee/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create New</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('designation.index') }}"
                                class="nav-link {{ request()->is('employee/designation') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Designation</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee_status.index') }}"
                                class="nav-link {{ request()->is('employee/status') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Employee Status</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-business-time"></i>
                        <p>
                            Attendance
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Work Shift</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Timesheet</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Attendance</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Attendance Type</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            Calendar
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Leave
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Leave Request</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Leave Allocation</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Leave Type</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>
                            Announcement
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-money-bill"></i>
                        <p>
                            Payroll
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Payrun</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Payslip</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pay Type</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ request()->is('settings/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('settings/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Settings
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ request()->is('settings/general-settings') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>General Settings</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hr-docs-template.index') }}"
                                class="nav-link {{ request()->is('settings/hr-docs-template*') ? 'active' : '' }}">
                                <i class="far fa-file nav-icon"></i>
                                <p>HR Docs Template</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ request()->is('settings/sms-module') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>SMS Module</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li> --}}
            </ul>
        </nav>
    </div>
</aside>
