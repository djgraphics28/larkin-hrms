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
                @can('access_dashboard')
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                @endcan
                <li class="nav-item {{ request()->is('apps/*') ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)" class="nav-link {{ request()->is('apps/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>
                            Apps
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('access_businesses')
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('business') }}"
                                    class="nav-link {{ request()->is('apps/business') ? 'active' : '' }}">
                                    <i class="far fa-building nav-icon"></i>
                                    <p>
                                        Businesses
                                    </p>
                                </a>
                            </li>
                        @endcan
                        @can('access_departments')
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('department') }}"
                                    class="nav-link {{ request()->is('apps/department') ? 'active' : '' }}">
                                    <i class="far fa-user-circle nav-icon"></i>
                                    <p>Departments</p>
                                </a>
                            </li>
                        @endcan
                        @can('access_workshifts')
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('workshift') }}"
                                    class="nav-link {{ request()->is('apps/workshift') ? 'active' : '' }}">
                                    <i class="far fa-user-circle nav-icon"></i>
                                    <p>Workshift</p>
                                </a>
                            </li>
                        @endcan
                        @can('access_fortnights')
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('fortnight-generator') }}"
                                    class="nav-link {{ request()->is('apps/fortnight-generator') ? 'active' : '' }}">
                                    <i class="far fa-user-circle nav-icon"></i>
                                    <p>Fortnight Generator</p>
                                </a>
                            </li>
                        @endcan
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('users') }}"
                                class="nav-link {{ request()->is('apps/users') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    User Managment
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('bank-details') }}"
                                class="nav-link {{ request()->is('apps/bank-details') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-piggy-bank"></i>
                                <p>
                                    Company Bank Details
                                </p>
                            </a>
                        </li>

                        @can('access_taxes')
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('tax-table') }}"
                                    class="nav-link {{ request()->is('apps/tax-table') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-percent"></i>
                                    <p>
                                        Tax Table
                                    </p>
                                </a>
                            </li>
                        @endcan
                        @can('access_holidays')
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('set-holiday') }}"
                                    class="nav-link {{ request()->is('apps/set-holiday') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-calendar"></i>
                                    <p>
                                        Holidays
                                    </p>
                                </a>
                            </li>
                        @endcan
                        @can('access_nasfunds')
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('nasfund') }}"
                                    class="nav-link {{ request()->is('apps/nasfund') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-money-bill"></i>
                                    <p>
                                        Nasfund
                                    </p>
                                </a>
                            </li>
                        @endcan
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
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('import-employee') }}"
                                class="nav-link {{ request()->is('employee/import') ? 'active' : '' }}">
                                <i class="far fa fa-upload nav-icon"></i>
                                <p>Import</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('employee.index', 'all') }}"
                                class="nav-link {{ request()->is('employee/all/employees') || request()->is('employee/all/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    All
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('employee.index', 'national') }}"
                                class="nav-link {{ request()->is('employee/national/employees') || request()->is('employee/national/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    National
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('employee.index', 'expatriate') }}"
                                class="nav-link {{ request()->is('employee/expatriate/employees') || request()->is('employee/expatriate/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Expatriate
                                </p>
                            </a>
                        </li>
                        @can('access_designations')
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('designation') }}"
                                    class="nav-link {{ request()->is('employee/designation') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Designation/Position</p>
                                </a>
                            </li>
                        @endcan
                        @can('access_employee_statuses')
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('employee-status') }}"
                                    class="nav-link {{ request()->is('employee/status') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Employee Status</p>
                                </a>
                            </li>
                        @endcan
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
                            <a wire:navigate href="{{ route('attendance-logs') }}"
                                class="nav-link {{ request()->is('attendance/logs') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Logs | Timesheets</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('attendance-import') }}"
                                class="nav-link {{ request()->is('attendance/import') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Import</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a wire:navigate href="{{ route('attendance-adjustment') }}"
                                class="nav-link {{ request()->is('attendance/adjustment') ? 'active' : '' }}">
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
                        @can('access_leaves')
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('leave-request') }}"
                                    class="nav-link {{ request()->is('leave/request') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> Leave Request</p>
                                </a>
                            </li>
                        @endcan
                        @can('access_leave_types')
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('leave-types') }}"
                                    class="nav-link {{ request()->is('leave/types') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> Leave Types</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li class="nav-item {{ request()->is('payroll/*') ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)" class="nav-link {{ request()->is('payroll/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-dollar-sign"></i>
                        <p>
                            Payroll
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('payslip') }}"
                                class="nav-link {{ request()->is('payroll/payslip') ? 'active' : '' }}">
                                <i class="far fa-print nav-icon"></i>
                                <p> Generate</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->is('loan/*') ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)" class="nav-link {{ request()->is('loan/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-dollar-sign"></i>
                        <p>
                            Loan Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('access_loans')
                            <li class="nav-item">
                                <a href="{{ route('cash-advance') }}"
                                    class="nav-link {{ request()->is('loan/cash-advance') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Loan Request</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                <li class="nav-item {{ request()->is('asset*') ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)" class="nav-link {{ request()->is('asset*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-dollar-sign"></i>
                        <p>
                            Asset Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('access_assets')
                            <li class="nav-item">
                                <a href="{{ route('asset') }}"
                                    class="nav-link {{ request()->is('asset*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Assets</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                {{-- GENERAL SETTINGS --}}
                <li class="nav-header">GENERAL SETTINGS</li>
                <li class="nav-item {{ request()->is('email-template/*') ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)"
                        class="nav-link {{ request()->is('email-template/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-dollar-sign"></i>
                        <p>
                            Email Templates
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('access_email_template_types')
                            <li class="nav-item">
                                <a href="{{ route('email-template-type') }}"
                                    class="nav-link {{ request()->is('email-template/type') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Email Template Type</p>
                                </a>
                            </li>
                        @endcan
                        @can('access_email_templates')
                            <li class="nav-item">
                                <a href="{{ route('email-template') }}"
                                    class="nav-link {{ request()->is('email-template/') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Email Template</p>
                                </a>
                            </li>
                        @endcan
                        @can('access_email_variables')
                            <li class="nav-item">
                                <a href="{{ route('email-variable') }}"
                                    class="nav-link {{ request()->is('email-template/variable') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Email Variable</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                {{-- <li class="nav-item">
                    <a href="/dashboard" class="nav-link">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>  --}}
            </ul>
        </nav>
    </div>
</aside>
