<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Larkin Logo" />
    </a>

    <div class="sidebar">
        {{-- Sidebar user panel (optional)  --}}
        <div class="user-panel mt-3 pb-3 mb-6 d-flex">
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
                        <a  href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                @endcan
                @can('access_apps_management')
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
                                    <a  href="{{ route('business') }}"
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
                                    <a  href="{{ route('department') }}"
                                        class="nav-link {{ request()->is('apps/department') ? 'active' : '' }}">
                                        <i class="far fa-user-circle nav-icon"></i>
                                        <p>Departments</p>
                                    </a>
                                </li>
                            @endcan
                            @can('access_workshifts')
                                <li class="nav-item">
                                    <a  href="{{ route('workshift') }}"
                                        class="nav-link {{ request()->is('apps/workshift') ? 'active' : '' }}">
                                        <i class="far fa-user-circle nav-icon"></i>
                                        <p>Workshift</p>
                                    </a>
                                </li>
                            @endcan
                            @can('access_fortnight')
                                <li class="nav-item">
                                    <a  href="{{ route('fortnight-generator') }}"
                                        class="nav-link {{ request()->is('apps/fortnight-generator') ? 'active' : '' }}">
                                        <i class="far fa-user-circle nav-icon"></i>
                                        <p>Fortnight Generator</p>
                                    </a>
                                </li>
                            @endcan
                            @can('access_users')
                                <li class="nav-item">
                                    <a  href="{{ route('users') }}"
                                        class="nav-link {{ request()->is('apps/users') || request()->is('apps/roles') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>
                                            User & Roles
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('access_company_bank_details')
                                <li class="nav-item">
                                    <a  href="{{ route('bank-details') }}"
                                        class="nav-link {{ request()->is('apps/bank-details') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-piggy-bank"></i>
                                        <p>
                                            Company Bank Details
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('access_taxes')
                                <li class="nav-item">
                                    <a  href="{{ route('tax-table') }}"
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
                                    <a  href="{{ route('set-holiday') }}"
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
                                    <a  href="{{ route('nasfund') }}"
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
                @endcan
                {{-- HR MANAGEMENT --}}
                @can('access_hr_management')
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
                            @can('import_employees')
                                <li class="nav-item">
                                    <a  href="{{ route('import-employee') }}"
                                        class="nav-link {{ request()->is('employee/import') ? 'active' : '' }}">
                                        <i class="far fa fa-upload nav-icon"></i>
                                        <p>Import</p>
                                    </a>
                                </li>
                            @endcan
                            @can('access_employees')
                                <li class="nav-item">
                                    <a  href="{{ route('employee.index', 'all') }}"
                                        class="nav-link {{ request()->is('employee/all/employees') || request()->is('employee/all/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            All
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a  href="{{ route('employee.index', 'national') }}"
                                        class="nav-link {{ request()->is('employee/national/employees') || request()->is('employee/national/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            National
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a  href="{{ route('employee.index', 'expatriate') }}"
                                        class="nav-link {{ request()->is('employee/expatriate/employees') || request()->is('employee/expatriate/create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Expatriate
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('access_designations')
                                <li class="nav-item">
                                    <a  href="{{ route('designation') }}"
                                        class="nav-link {{ request()->is('employee/designation') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Designation/Position</p>
                                    </a>
                                </li>
                            @endcan
                            @can('access_employee_statuses')
                                <li class="nav-item">
                                    <a  href="{{ route('employee-status') }}"
                                        class="nav-link {{ request()->is('employee/status') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Employee Status</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                    @can('access_attendances')
                        <li class="nav-item {{ request()->is('attendance/*') ? 'menu-open' : '' }}">
                            <a href="javascript:void(0)" class="nav-link {{ request()->is('attendance/*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-business-time"></i>
                                <p>
                                    Attendance
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('access_logs_timesheets')
                                    <li class="nav-item">
                                        <a  href="{{ route('attendance-logs') }}"
                                            class="nav-link {{ request()->is('attendance/logs') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Logs | Timesheets</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('create_attendances')
                                    <li class="nav-item">
                                        <a  href="{{ route('attendance-create') }}"
                                            class="nav-link {{ request()->is('attendance/create') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Create</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('import_attendances')
                                    <li class="nav-item">
                                        <a  href="{{ route('attendance-import') }}"
                                            class="nav-link {{ request()->is('attendance/import') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Import</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('access_attendance_adjustments')
                                    <li class="nav-item">
                                        <a  href="{{ route('attendance-adjustment') }}"
                                            class="nav-link {{ request()->is('attendance/adjustment') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Adjustment</p>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('access_leaves')
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
                                        <a  href="{{ route('leave-request') }}"
                                            class="nav-link {{ request()->is('leave/request') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Leave Request</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('access_leave_types')
                                    <li class="nav-item">
                                        <a  href="{{ route('leave-types') }}"
                                            class="nav-link {{ request()->is('leave/types') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Leave Types</p>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('access_payroll')
                        <li class="nav-item {{ request()->is('payroll/*') ? 'menu-open' : '' }}">
                            <a href="javascript:void(0)" class="nav-link {{ request()->is('payroll/*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-dollar-sign"></i>
                                <p>
                                    Payroll
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('access_payroll')
                                    <li class="nav-item">
                                        <a href="{{ route('payroll') }}"
                                            class="nav-link {{ request()->is('payroll/lists') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Payroll</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('access_payslips')
                                    <li class="nav-item">
                                        <a href="{{ route('payslip') }}"
                                            class="nav-link {{ request()->is('payroll/payslip') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Payslip</p>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('access_loans')
                        <li class="nav-item {{ request()->is('loan/*') ? 'menu-open' : '' }}">
                            <a href="javascript:void(0)" class="nav-link {{ request()->is('loan/*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-comments-dollar"></i>
                                <p>
                                    Loans
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('access_loans')
                                    <li class="nav-item">
                                        <a href="{{ route('loans') }}"
                                            class="nav-link {{ request()->is('loan/request') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Loan Request</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('access_loan_types')
                                    <li class="nav-item">
                                        <a href="{{ route('loan-type') }}"
                                            class="nav-link {{ request()->is('loan/type') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Loan Type</p>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('access_assets')
                        <li class="nav-item {{ request()->is('asset*') ? 'menu-open' : '' }}">
                            <a href="javascript:void(0)" class="nav-link {{ request()->is('asset*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-hat-wizard"></i>
                                <p>
                                    Assets
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('access_assets')
                                    <li class="nav-item">
                                        <a href="{{ route('asset') }}"
                                            class="nav-link {{ request()->is('asset') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Assets</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('access_asset_types')
                                    <li class="nav-item">
                                        <a href="{{ route('asset-type') }}"
                                            class="nav-link {{ request()->is('asset/type') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Asset Type</p>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                @endcan
                {{-- GENERAL SETTINGS --}}
                @can('access_general_settings')
                    <li class="nav-header">GENERAL SETTINGS</li>
                    @can('access_email_templates')
                        <li class="nav-item {{ request()->is('email-template/*') ? 'menu-open' : '' }}">
                            <a href="javascript:void(0)"
                                class="nav-link {{ request()->is('email-template/*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-envelope"></i>
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
                    @endcan
                @endcan
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="nav-link"
                            onclick="event.preventDefault();
                    this.closest('form').submit();">
                            <i class="nav-icon fas fa-power-off"></i>
                            <p>
                                {{ __('Log Out') }}
                            </p>
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
