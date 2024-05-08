<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
// use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
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
            ['name' => 'access_dashboard', 'group_name' => 'Dashboard'],
            //Apps ///////////////////////////>>>>>>>>>>>>>>>
            ['name' => 'access_apps_management', 'group_name' => 'Apps'],
            //Businesses
            ['name' => 'access_businesses', 'group_name' => 'Businesses'],
            ['name' => 'create_businesses', 'group_name' => 'Businesses'],
            ['name' => 'show_businesses', 'group_name' => 'Businesses'],
            ['name' => 'edit_businesses', 'group_name' => 'Businesses'],
            ['name' => 'delete_businesses', 'group_name' => 'Businesses'],
            ['name' => 'export_businesses', 'group_name' => 'Businesses'],
            //Departments
            ['name' => 'access_departments', 'group_name' => 'Departments'],
            ['name' => 'create_departments', 'group_name' => 'Departments'],
            ['name' => 'show_departments', 'group_name' => 'Departments'],
            ['name' => 'edit_departments', 'group_name' => 'Departments'],
            ['name' => 'delete_departments', 'group_name' => 'Departments'],
            ['name' => 'export_departments', 'group_name' => 'Departments'],
            //Workshifts
            ['name' => 'access_workshifts', 'group_name' => 'Workshifts'],
            ['name' => 'create_workshifts', 'group_name' => 'Workshifts'],
            ['name' => 'show_workshifts', 'group_name' => 'Workshifts'],
            ['name' => 'edit_workshifts', 'group_name' => 'Workshifts'],
            ['name' => 'delete_workshifts', 'group_name' => 'Workshifts'],
            ['name' => 'export_workshifts', 'group_name' => 'Workshifts'],
            //Fortnights
            ['name' => 'access_fortnights', 'group_name' => 'Fortnights'],
            ['name' => 'create_fortnights', 'group_name' => 'Fortnights'],
            //User & Roles
            ['name' => 'access_users', 'group_name' => 'User & Roles'],
            ['name' => 'create_users', 'group_name' => 'User & Roles'],
            ['name' => 'show_users', 'group_name' => 'User & Roles'],
            ['name' => 'edit_users', 'group_name' => 'User & Roles'],
            ['name' => 'delete_users', 'group_name' => 'User & Roles'],
            ['name' => 'export_users', 'group_name' => 'User & Roles'],
            ['name' => 'access_roles', 'group_name' => 'User & Roles'],
            ['name' => 'create_roles', 'group_name' => 'User & Roles'],
            ['name' => 'show_roles', 'group_name' => 'User & Roles'],
            ['name' => 'edit_roles', 'group_name' => 'User & Roles'],
            ['name' => 'delete_roles', 'group_name' => 'User & Roles'],
            ['name' => 'export_roles', 'group_name' => 'User & Roles'],
            //CompanyBank Details
            ['name' => 'access_company_bank_details', 'group_name' => 'CompanyBank Details'],
            ['name' => 'create_company_bank_details', 'group_name' => 'CompanyBank Details'],
            ['name' => 'show_company_bank_details', 'group_name' => 'CompanyBank Details'],
            ['name' => 'edit_company_bank_details', 'group_name' => 'CompanyBank Details'],
            ['name' => 'delete_company_bank_details', 'group_name' => 'CompanyBank Details'],
            ['name' => 'export_company_bank_details', 'group_name' => 'CompanyBank Details'],
            //Taxes
            ['name' => 'access_taxes', 'group_name' => 'Taxes'],
            ['name' => 'create_taxes', 'group_name' => 'Taxes'],
            ['name' => 'show_taxes', 'group_name' => 'Taxes'],
            ['name' => 'edit_taxes', 'group_name' => 'Taxes'],
            ['name' => 'delete_taxes', 'group_name' => 'Taxes'],
            ['name' => 'export_taxes', 'group_name' => 'Taxes'],

            //Holidays
            ['name' => 'access_holidays', 'group_name' => 'Holidays'],
            ['name' => 'create_holidays', 'group_name' => 'Holidays'],
            ['name' => 'show_holidays', 'group_name' => 'Holidays'],
            ['name' => 'edit_holidays', 'group_name' => 'Holidays'],
            ['name' => 'delete_holidays', 'group_name' => 'Holidays'],
            ['name' => 'export_holidays', 'group_name' => 'Holidays'],

            //Nasfunds
            ['name' => 'access_nasfunds', 'group_name' => 'Nasfunds'],
            ['name' => 'create_nasfunds', 'group_name' => 'Nasfunds'],
            ['name' => 'show_nasfunds', 'group_name' => 'Nasfunds'],
            ['name' => 'edit_nasfunds', 'group_name' => 'Nasfunds'],
            ['name' => 'delete_nasfunds', 'group_name' => 'Nasfunds'],
            ['name' => 'export_nasfunds', 'group_name' => 'Nasfunds'],

            //Hr Management ///////////////////////////>>>>>>>>>>>>>>>
            // Employees
            ['name' => 'access_employees', 'group_name' => 'Hr Management'],
            ['name' => 'create_employees', 'group_name' => 'Hr Management'],
            ['name' => 'show_employees', 'group_name' => 'Hr Management'],
            ['name' => 'edit_employees', 'group_name' => 'Hr Management'],
            ['name' => 'delete_employees', 'group_name' => 'Hr Management'],
            ['name' => 'import_employees', 'group_name' => 'Hr Management'],
            ['name' => 'export_employees', 'group_name' => 'Hr Management'],

            // Designations
            ['name' => 'access_designations', 'group_name' => 'Hr Management'],
            ['name' => 'create_designations', 'group_name' => 'Hr Management'],
            ['name' => 'show_designations', 'group_name' => 'Hr Management'],
            ['name' => 'edit_designations', 'group_name' => 'Hr Management'],
            ['name' => 'delete_designations', 'group_name' => 'Hr Management'],
            ['name' => 'export_designations', 'group_name' => 'Hr Management'],

            // EmployeeStatuses
            ['name' => 'access_employee_statuses', 'group_name' => 'Hr Management'],
            ['name' => 'create_employee_statuses', 'group_name' => 'Hr Management'],
            ['name' => 'show_employee_statuses', 'group_name' => 'Hr Management'],
            ['name' => 'edit_employee_statuses', 'group_name' => 'Hr Management'],
            ['name' => 'delete_employee_statuses', 'group_name' => 'Hr Management'],
            ['name' => 'export_employee_statuses', 'group_name' => 'Hr Management'],

            // Attendances
            ['name' => 'access_logs_timesheets', 'group_name' => 'Hr Management'],
            ['name' => 'create_attendances', 'group_name' => 'Hr Management'],
            ['name' => 'show_attendances', 'group_name' => 'Hr Management'],
            ['name' => 'edit_attendances', 'group_name' => 'Hr Management'],
            ['name' => 'delete_attendances', 'group_name' => 'Hr Management'],
            ['name' => 'import_attendances', 'group_name' => 'Hr Management'],
            ['name' => 'export_attendances', 'group_name' => 'Hr Management'],

            // Leaves
            ['name' => 'access_leaves', 'group_name' => 'Hr Management'],
            ['name' => 'create_leaves', 'group_name' => 'Hr Management'],
            ['name' => 'show_leaves', 'group_name' => 'Hr Management'],
            ['name' => 'edit_leaves', 'group_name' => 'Hr Management'],
            ['name' => 'delete_leaves', 'group_name' => 'Hr Management'],
            ['name' => 'export_leaves', 'group_name' => 'Hr Management'],

            // Payroll
            ['name' => 'access_payroll', 'group_name' => 'Hr Management'],
            ['name' => 'create_payroll', 'group_name' => 'Hr Management'],
            ['name' => 'show_payroll', 'group_name' => 'Hr Management'],
            ['name' => 'edit_payroll', 'group_name' => 'Hr Management'],
            ['name' => 'delete_payroll', 'group_name' => 'Hr Management'],
            ['name' => 'export_payroll', 'group_name' => 'Hr Management'],

            // Payruns
            ['name' => 'access_payruns', 'group_name' => 'Hr Management'],
            ['name' => 'create_payruns', 'group_name' => 'Hr Management'],
            ['name' => 'show_payruns', 'group_name' => 'Hr Management'],
            ['name' => 'edit_payruns', 'group_name' => 'Hr Management'],
            ['name' => 'delete_payruns', 'group_name' => 'Hr Management'],
            ['name' => 'export_payruns', 'group_name' => 'Hr Management'],

            // Payslip
            ['name' => 'access_payslip', 'group_name' => 'Hr Management'],
            ['name' => 'create_payslip', 'group_name' => 'Hr Management'],
            ['name' => 'show_payslip', 'group_name' => 'Hr Management'],
            ['name' => 'edit_payslip', 'group_name' => 'Hr Management'],
            ['name' => 'delete_payslip', 'group_name' => 'Hr Management'],
            ['name' => 'export_payslip', 'group_name' => 'Hr Management'],

            // Loans
            ['name' => 'access_loans', 'group_name' => 'Hr Management'],
            ['name' => 'create_loans', 'group_name' => 'Hr Management'],
            ['name' => 'show_loans', 'group_name' => 'Hr Management'],
            ['name' => 'edit_loans', 'group_name' => 'Hr Management'],
            ['name' => 'delete_loans', 'group_name' => 'Hr Management'],
            ['name' => 'export_loans', 'group_name' => 'Hr Management'],

            // LoanTypes
            ['name' => 'access_loan_types', 'group_name' => 'Hr Management'],
            ['name' => 'create_loan_types', 'group_name' => 'Hr Management'],
            ['name' => 'show_loan_types', 'group_name' => 'Hr Management'],
            ['name' => 'edit_loan_types', 'group_name' => 'Hr Management'],
            ['name' => 'delete_loan_types', 'group_name' => 'Hr Management'],
            ['name' => 'export_loan_types', 'group_name' => 'Hr Management'],

            // Assets
            ['name' => 'access_assets', 'group_name' => 'Hr Management'],
            ['name' => 'create_assets', 'group_name' => 'Hr Management'],
            ['name' => 'show_assets', 'group_name' => 'Hr Management'],
            ['name' => 'edit_assets', 'group_name' => 'Hr Management'],
            ['name' => 'delete_assets', 'group_name' => 'Hr Management'],
            ['name' => 'export_assets', 'group_name' => 'Hr Management'],

            // AssetTypes
            ['name' => 'access_asset_types', 'group_name' => 'Hr Management'],
            ['name' => 'create_asset_types', 'group_name' => 'Hr Management'],
            ['name' => 'show_asset_types', 'group_name' => 'Hr Management'],
            ['name' => 'edit_asset_types', 'group_name' => 'Hr Management'],
            ['name' => 'delete_asset_types', 'group_name' => 'Hr Management'],
            ['name' => 'export_asset_types', 'group_name' => 'Hr Management'],

            //Genreal Settings ///////////////////////////>>>>>>>>>>>>>>>
            ['name' => 'access_general_settings', 'group_name' => 'General Settings'],
            // EmailTemplates
            ['name' => 'access_email_templates', 'group_name' => 'EmailTemplates'],
            ['name' => 'create_email_templates', 'group_name' => 'EmailTemplates'],
            ['name' => 'show_email_templates', 'group_name' => 'EmailTemplates'],
            ['name' => 'edit_email_templates', 'group_name' => 'EmailTemplates'],
            ['name' => 'delete_email_templates', 'group_name' => 'EmailTemplates'],
            ['name' => 'export_email_templates', 'group_name' => 'EmailTemplates'],

            // EmailTemplateTypes
            ['name' => 'access_email_template_types', 'group_name' => 'EmailTemplateTypes'],
            ['name' => 'create_email_template_types', 'group_name' => 'EmailTemplateTypes'],
            ['name' => 'show_email_template_types', 'group_name' => 'EmailTemplateTypes'],
            ['name' => 'edit_email_template_types', 'group_name' => 'EmailTemplateTypes'],
            ['name' => 'delete_email_template_types', 'group_name' => 'EmailTemplateTypes'],
            ['name' => 'export_email_template_types', 'group_name' => 'EmailTemplateTypes'],

            // EmailVariables
            ['name' => 'access_email_variables', 'group_name' => 'EmailVariables'],
            ['name' => 'create_email_variables', 'group_name' => 'EmailVariables'],
            ['name' => 'show_email_variables', 'group_name' => 'EmailVariables'],
            ['name' => 'edit_email_variables', 'group_name' => 'EmailVariables'],
            ['name' => 'delete_email_variables', 'group_name' => 'EmailVariables'],
            ['name' => 'export_email_variables', 'group_name' => 'EmailVariables'],
            //Imports
            ['name' => 'access_imports_management', 'group_name' => 'Imports'],

            //Reports
            ['name' => 'access_reports_management', 'group_name' => 'Reports'],
        ];

        foreach ($permissions as $permission) {
            Permission::create([$permission]);
        }

        $role = Role::create([
            'name' => 'Admin'
        ]);

        $role->givePermissionTo($permissions);
        // $role->revokePermissionTo('access_user_management');
    }
}
