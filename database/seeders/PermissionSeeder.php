<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            //Dashboard
            'access_dashboard',
            //Apps ///////////////////////////>>>>>>>>>>>>>>>
            'access_apps_management',
            //Businesses
            'access_businesses',
            'create_businesses',
            'show_businesses',
            'edit_businesses',
            'delete_businesses',
            'export_businesses',
            //Departments
            'access_departments',
            'create_departments',
            'show_departments',
            'edit_departments',
            'delete_departments',
            'export_departments',
            //Workshifts
            'access_workshifts',
            'create_workshifts',
            'show_workshifts',
            'edit_workshifts',
            'delete_workshifts',
            'export_workshifts',
            //Fortnights
            'access_fortnights',
            'create_fortnights',
            //User & Roles
            'access_users',
            'create_users',
            'show_users',
            'edit_users',
            'delete_users',
            'export_users',
            'access_roles',
            'create_roles',
            'show_roles',
            'edit_roles',
            'delete_roles',
            'export_roles',
            //CompanyBank Details
            'access_company_bank_details',
            'create_company_bank_details',
            'show_company_bank_details',
            'edit_company_bank_details',
            'delete_company_bank_details',
            'export_company_bank_details',
            //Taxes
            'access_taxes',
            'create_taxes',
            'show_taxes',
            'edit_taxes',
            'delete_taxes',
            'export_taxes',
            //Holidays
            'access_holidays',
            'create_holidays',
            'show_holidays',
            'edit_holidays',
            'delete_holidays',
            'export_holidays',
            //Nasfunds
            'access_nasfunds',
            'create_nasfunds',
            'show_nasfunds',
            'edit_nasfunds',
            'delete_nasfunds',
            'export_nasfunds',
            //Hr Management ///////////////////////////>>>>>>>>>>>>>>>
            'access_hr_management',
            //Employees
            'access_employees',
            'create_employees',
            'show_employees',
            'edit_employees',
            'delete_employees',
            'import_employees',
            'export_employees',
            //Designations
            'access_designations',
            'create_designations',
            'show_designations',
            'edit_designations',
            'delete_designations',
            'export_designations',
            //EmployeeStatuses
            'access_employee_statuses',
            'create_employee_statuses',
            'show_employee_statuses',
            'edit_employee_statuses',
            'delete_employee_statuses',
            'export_employee_statuses',
            //Attendances
            'access_logs_timesheets',
            'create_attendances',
            'show_attendances',
            'edit_attendances',
            'delete_attendances',
            'import_attendances',
            'export_attendances',
            //Leaves
            'access_leaves',
            'create_leaves',
            'show_leaves',
            'edit_leaves',
            'delete_leaves',
            'export_leaves',
            //Payroll
            'access_payroll',
            'create_payroll',
            'show_payroll',
            'edit_payroll',
            'delete_payroll',
            'export_payroll',
            //Payruns
            'access_payruns',
            'create_payruns',
            'show_payruns',
            'edit_payruns',
            'delete_payruns',
            'export_payruns',
            //Payslip
            'access_payslip',
            'create_payslip',
            'show_payslip',
            'edit_payslip',
            'delete_payslip',
            'export_payslip',
            //Loans
            'access_loans',
            'create_loans',
            'show_loans',
            'edit_loans',
            'delete_loans',
            'export_loans',
            //LoanTypes
            'access_loan_types',
            'create_loan_types',
            'show_loan_types',
            'edit_loan_types',
            'delete_loan_types',
            'export_loan_types',
            //Assets
            'access_assets',
            'create_assets',
            'show_assets',
            'edit_assets',
            'delete_assets',
            'export_assets',
            //AssetTypes
            'access_asset_types',
            'create_asset_types',
            'show_asset_types',
            'edit_asset_types',
            'delete_asset_types',
            'export_asset_types',
            //Genreal Settings ///////////////////////////>>>>>>>>>>>>>>>
            'access_general_settings',
            //EmailTemplates
            'access_email_templates',
            'create_email_templates',
            'show_email_templates',
            'edit_email_templates',
            'delete_email_templates',
            'export_email_templates',
            //EmailTemplateTypes
            'access_email_template_types',
            'create_email_template_types',
            'show_email_template_types',
            'edit_email_template_types',
            'delete_email_template_types',
            'export_email_template_types',
            //EmailVariables
            'access_email_variables',
            'create_email_variables',
            'show_email_variables',
            'edit_email_variables',
            'delete_email_variables',
            'export_email_variables',
            //Imports
            'access_imports_management',
            //Reports
            'access_reports_management',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission
            ]);
        }

        $role = Role::create([
            'name' => 'Admin'
        ]);

        $role->givePermissionTo($permissions);
        // $role->revokePermissionTo('access_user_management');
    }
}
