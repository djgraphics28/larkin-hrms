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
            //Administration
            'access_administration_management',
            //Hr Management
            'access_hr_management',
            //Imports
            'access_imports_management',
            //Reports
            'access_reports_management',
            //Dashboard
            'access_dashboard',
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
            //Holidays
            'access_holidays',
            'create_holidays',
            'show_holidays',
            'edit_holidays',
            'delete_holidays',
            'export_holidays',
            //Loans
            'access_loans',
            'create_loans',
            'show_loans',
            'edit_loans',
            'delete_loans',
            'export_loans',
            //Leaves
            'access_leaves',
            'create_leaves',
            'show_leaves',
            'edit_leaves',
            'delete_leaves',
            'export_leaves',
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
            //Taxes
            'access_taxes',
            'create_taxes',
            'show_taxes',
            'edit_taxes',
            'delete_taxes',
            'export_taxes',
            //Fortnights
            'access_fortnights',
            'create_fortnights',
            //Nasfunds
            'access_nasfunds',
            'create_nasfunds',
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
