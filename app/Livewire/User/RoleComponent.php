<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Role;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class RoleComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove'];
    public $approveConfirmed;
    // filters
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Add New Department';
    public $updateMode = false;

    public $name;
    public $roleId;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];
    public $permissions = [];

    //Permissions
    //all
    public $all_permission = false;
    //Dashboard
    public $access_dashboard = false;
    //Apps ///////////////////////////>>>>>>>>>>>>>>>
    public $access_apps_management = true;
    //Businesses
    public $access_businesses = false;
    public $create_businesses = false;
    public $show_businesses = false;
    public $edit_businesses = false;
    public $delete_businesses = false;
    public $export_businesses = false;
    //Departments
    public $access_departments = false;
    public $create_departments = false;
    public $show_departments = false;
    public $edit_departments = false;
    public $delete_departments = false;
    public $export_departments = false;
    //Workshifts
    public $access_workshifts = false;
    public $create_workshifts = false;
    public $show_workshifts = false;
    public $edit_workshifts = false;
    public $delete_workshifts = false;
    public $export_workshifts = false;
    //Fortnights
    public $access_fortnight = false;
    public $create_fortnight = false;
    //User & Roles
    public $access_users = false;
    public $create_users = false;
    public $show_users = false;
    public $edit_users = false;
    public $delete_users = false;
    public $export_users = false;
    public $access_roles = false;
    public $create_roles = false;
    public $show_roles = false;
    public $edit_roles = false;
    public $delete_roles = false;
    public $export_roles = false;
    //CompanyBank Details
    public $access_company_bank_details = false;
    public $create_company_bank_details = false;
    public $show_company_bank_details = false;
    public $edit_company_bank_details = false;
    public $delete_company_bank_details = false;
    public $export_company_bank_details = false;
    //Taxes
    public $access_taxes = false;
    public $create_taxes = false;
    public $show_taxes = false;
    public $edit_taxes = false;
    public $delete_taxes = false;
    public $export_taxes = false;
    //Holidays
    public $access_holidays = false;
    public $create_holidays = false;
    public $show_holidays = false;
    public $edit_holidays = false;
    public $delete_holidays = false;
    public $export_holidays = false;
    //Nasfunds
    public $access_nasfunds = false;
    public $create_nasfunds = false;
    public $show_nasfunds = false;
    public $edit_nasfunds = false;
    public $delete_nasfunds = false;
    public $export_nasfunds = false;
    //Hr Management ///////////////////////////>>>>>>>>>>>>>>>
    public $access_hr_management = false;
    //Employees
    public $access_employees = false;
    public $create_employees = false;
    public $show_employees = false;
    public $edit_employees = false;
    public $delete_employees = false;
    public $import_employees = false;
    public $export_employees = false;
    //Designations
    public $access_designations = false;
    public $create_designations = false;
    public $show_designations = false;
    public $edit_designations = false;
    public $delete_designations = false;
    public $export_designations = false;
    //EmployeeStatuses
    public $access_employee_statuses = false;
    public $create_employee_statuses = false;
    public $show_employee_statuses = false;
    public $edit_employee_statuses = false;
    public $delete_employee_statuses = false;
    public $export_employee_statuses = false;
    //Attendances
    public $access_attendances = false;
    public $access_logs_timesheets = false;
    public $create_attendances = false;
    public $show_attendances = false;
    public $edit_attendances = false;
    public $delete_attendances = false;
    public $import_attendances = false;
    public $export_attendances = false;
    public $access_attendance_adjustments = false;
    //Leaves
    public $access_leaves = false;
    public $create_leaves = false;
    public $show_leaves = false;
    public $edit_leaves = false;
    public $delete_leaves = false;
    public $export_leaves = false;
    //Payroll
    public $access_payroll = false;
    public $create_payroll = false;
    public $show_payroll = false;
    public $edit_payroll = false;
    public $delete_payroll = false;
    public $export_payroll = false;
    //Payruns
    public $access_payruns = false;
    public $create_payruns = false;
    public $show_payruns = false;
    public $edit_payruns = false;
    public $delete_payruns = false;
    public $export_payruns = false;
    //Payslip
    public $access_payslips = false;
    public $create_payslips = false;
    public $show_payslips = false;
    public $edit_payslips = false;
    public $delete_payslips = false;
    public $export_payslips = false;
    //ABA Generator
    public $access_aba_generator = false;
    //Loans
    public $access_loans = false;
    public $create_loans = false;
    public $show_loans = false;
    public $edit_loans = false;
    public $delete_loans = false;
    public $export_loans = false;
    //LoanTypes
    public $access_loan_types = false;
    public $create_loan_types = false;
    public $show_loan_types = false;
    public $edit_loan_types = false;
    public $delete_loan_types = false;
    public $export_loan_types = false;
    //Assets
    public $access_assets = false;
    public $create_assets = false;
    public $show_assets = false;
    public $edit_assets = false;
    public $delete_assets = false;
    public $export_assets = false;
    //AssetTypes
    public $access_asset_types = false;
    public $create_asset_types = false;
    public $show_asset_types = false;
    public $edit_asset_types = false;
    public $delete_asset_types = false;
    public $export_asset_types = false;
    //Genreal Settings ///////////////////////////>>>>>>>>>>>>>>>
    public $access_general_settings = false;
    //EmailTemplates
    public $access_email_templates = false;
    public $create_email_templates = false;
    public $show_email_templates = false;
    public $edit_email_templates = false;
    public $delete_email_templates = false;
    public $export_email_templates = false;
    //EmailTemplateTypes
    public $access_email_template_types = false;
    public $create_email_template_types = false;
    public $show_email_template_types = false;
    public $edit_email_template_types = false;
    public $delete_email_template_types = false;
    public $export_email_template_types = false;
    //EmailVariables
    public $access_email_variables = false;
    public $create_email_variables = false;
    public $show_email_variables = false;
    public $edit_email_variables = false;
    public $delete_email_variables = false;
    public $export_email_variables = false;
    //Imports
    public $access_imports_management = false;
    //Reports
    public $access_reports_management = false;

    #[Title('Roles')]
    public function render()
    {
        return view('livewire.user.role-component', [
            'records' => $this->records
        ]);
    }

    public function getRecordsProperty()
    {
        return Role::paginate($this->perPage);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedRows = $this->records->pluck('id');
        } else {
            $this->selectedRows = [];
        }
    }

    public function addNew()
    {
        $this->resetInputFields();
        $this->dispatch('show-add-modal');
        $this->modalTitle = 'Add New Role';
        $this->updateMode = false;

    }

    public function submit($saveAndCreateNew)
    {
        $this->validate([
            'name' => 'required'
        ]);

        $role = Role::create([
            'name' => $this->name
        ]);

        if ($role) {
            // Define permissions array
            $permissions = [];

            // Loop through each permission property
            foreach ($this->getPermissionProperties() as $property) {
                // If permission property is true, add it to permissions array
                if ($this->$property) {
                    $permissions[] = $property;
                }
            }

            // Sync permissions with the role
            $role->syncPermissions($permissions);

            $this->resetInputFields();

            if ($saveAndCreateNew) {
                $this->alert('success', 'New Role has been saved successfully!');
            } else {
                $this->dispatch('hide-add-modal');
                $this->alert('success', 'New Role has been saved successfully!');
            }
        }
    }

    public function resetInputFields()
    {
        $this->name = '';
    }

    public function edit($id)
    {
        $this->roleId = $id;
        $this->dispatch('show-add-modal');
        $data = Role::find($id);
        $this->name = $data->name;
        $this->modalTitle = 'Edit ' . $this->name;
        $this->updateMode = true;

        // Fetch the role's permissions
        $permissions = $data->permissions->pluck('name')->toArray();

        // Set permission properties based on the fetched permissions
        foreach ($permissions as $permission) {
            $property = str_replace('.', '_', $permission); // Replace dots with underscores
            if (property_exists($this, $property)) {
                $this->$property = true;
            }
        }
    }

    public function update()
    {
        $this->validate([
            'name' => 'required'
        ]);

        $role = Role::find($this->roleId);
        $role->update([
            'name' => $this->name
        ]);

        if ($role) {
            // Define permissions array
            $permissions = [];

            // Loop through each permission property
            foreach ($this->getPermissionProperties() as $property) {
                // If permission property is true, add it to permissions array
                if ($this->$property) {
                    $permissions[] = $property;
                }
            }

            // Sync permissions with the role
            $role->syncPermissions($permissions);

            $this->resetInputFields();

            $this->dispatch('hide-add-modal');

            $this->resetInputFields();

            $this->alert('success', $role->name . ' has been updated!');
        }
    }

    public function alertConfirm($id)
    {
        $this->approveConfirmed = $id;

        $this->confirm('Are you sure you want to delete this?', [
            'confirmButtonText' => 'Yes Delete it!',
            'onConfirmed' => 'remove',
        ]);
    }

    public function remove()
    {
        $delete = Role::find($this->approveConfirmed);
        $name = $delete->name;
        $delete->delete();
        if ($delete) {
            $this->alert('success', $name . ' has been removed!');
        }
    }

    // Helper function to get all permission properties
    private function getPermissionProperties()
    {
        // Get all class properties
        $properties = get_object_vars($this);

        // Filter out properties that start with "access_" or "create_", etc.
        return array_filter(array_keys($properties), function ($property) {
            return preg_match('/^access_|^create_|^show_|^edit_|^delete_|^export_|^import_/', $property);
        });
    }

    public function toggleAllPermissions()
    {
        if ($this->all_permission) {
            // If "all_permission" is checked, set all other permission properties to true
            $this->access_dashboard = true;
            //Apps
            $this->access_apps_management = true;
            //Businesses
            $this->access_businesses = true;
            $this->create_businesses = true;
            $this->show_businesses = true;
            $this->edit_businesses = true;
            $this->delete_businesses = true;
            $this->export_businesses = true;
            //Departments
            $this->access_departments = true;
            $this->create_departments = true;
            $this->show_departments = true;
            $this->edit_departments = true;
            $this->delete_departments = true;
            $this->export_departments = true;
            //Workshifts
            $this->access_workshifts = true;
            $this->create_workshifts = true;
            $this->show_workshifts = true;
            $this->edit_workshifts = true;
            $this->delete_workshifts = true;
            $this->export_workshifts = true;
            //Fortnights
            $this->access_fortnight = true;
            $this->create_fortnight = true;
            //User & Roles
            $this->access_users = true;
            $this->create_users = true;
            $this->show_users = true;
            $this->edit_users = true;
            $this->delete_users = true;
            $this->export_users = true;
            $this->access_roles = true;
            $this->create_roles = true;
            $this->show_roles = true;
            $this->edit_roles = true;
            $this->delete_roles = true;
            $this->export_roles = true;
            //CompanyBank Details
            $this->access_company_bank_details = true;
            $this->create_company_bank_details = true;
            $this->show_company_bank_details = true;
            $this->edit_company_bank_details = true;
            $this->delete_company_bank_details = true;
            $this->export_company_bank_details = true;
            //Taxes
            $this->access_taxes = true;
            $this->create_taxes = true;
            $this->show_taxes = true;
            $this->edit_taxes = true;
            $this->delete_taxes = true;
            $this->export_taxes = true;
            //Holidays
            $this->access_holidays = true;
            $this->create_holidays = true;
            $this->show_holidays = true;
            $this->edit_holidays = true;
            $this->delete_holidays = true;
            $this->export_holidays = true;
            //Nasfunds
            $this->access_nasfunds = true;
            $this->create_nasfunds = true;
            $this->show_nasfunds = true;
            $this->edit_nasfunds = true;
            $this->delete_nasfunds = true;
            $this->export_nasfunds = true;
            //Hr Management ///////////////////////////>>>>>>>>>>>>>>>
            $this->access_hr_management = true;
            $this->access_employees = true;
            $this->create_employees = true;
            $this->show_employees = true;
            $this->edit_employees = true;
            $this->delete_employees = true;
            $this->import_employees = true;
            $this->export_employees = true;
            $this->access_designations = true;
            $this->create_designations = true;
            $this->show_designations = true;
            $this->edit_designations = true;
            $this->delete_designations = true;
            $this->export_designations = true;
            $this->access_employee_statuses = true;
            $this->create_employee_statuses = true;
            $this->show_employee_statuses = true;
            $this->edit_employee_statuses = true;
            $this->delete_employee_statuses = true;
            $this->export_employee_statuses = true;
            $this->access_attendances = true;
            $this->access_logs_timesheets = true;
            $this->create_attendances = true;
            $this->show_attendances = true;
            $this->edit_attendances = true;
            $this->delete_attendances = true;
            $this->import_attendances = true;
            $this->export_attendances = true;
            $this->access_attendance_adjustments = true;
            $this->access_leaves = true;
            $this->create_leaves = true;
            $this->show_leaves = true;
            $this->edit_leaves = true;
            $this->delete_leaves = true;
            $this->export_leaves = true;
            $this->access_payroll = true;
            $this->create_payroll = true;
            $this->show_payroll = true;
            $this->edit_payroll = true;
            $this->delete_payroll = true;
            $this->export_payroll = true;
            $this->access_payruns = true;
            $this->create_payruns = true;
            $this->show_payruns = true;
            $this->edit_payruns = true;
            $this->delete_payruns = true;
            $this->export_payruns = true;
            $this->access_payslips = true;
            $this->create_payslips = true;
            $this->show_payslips = true;
            $this->edit_payslips = true;
            $this->delete_payslips = true;
            $this->export_payslips = true;
            $this->access_aba_generator = true;
            $this->access_loans = true;
            $this->create_loans = true;
            $this->show_loans = true;
            $this->edit_loans = true;
            $this->delete_loans = true;
            $this->export_loans = true;
            $this->access_loan_types = true;
            $this->create_loan_types = true;
            $this->show_loan_types = true;
            $this->edit_loan_types = true;
            $this->delete_loan_types = true;
            $this->export_loan_types = true;
            $this->access_assets = true;
            $this->create_assets = true;
            $this->show_assets = true;
            $this->edit_assets = true;
            $this->delete_assets = true;
            $this->export_assets = true;
            $this->access_asset_types = true;
            $this->create_asset_types = true;
            $this->show_asset_types = true;
            $this->edit_asset_types = true;
            $this->delete_asset_types = true;
            $this->export_asset_types = true;
            $this->access_general_settings = true;
            $this->access_email_templates = true;
            $this->create_email_templates = true;
            $this->show_email_templates = true;
            $this->edit_email_templates = true;
            $this->delete_email_templates = true;
            $this->export_email_templates = true;
            $this->access_email_template_types = true;
            $this->create_email_template_types = true;
            $this->show_email_template_types = true;
            $this->edit_email_template_types = true;
            $this->delete_email_template_types = true;
            $this->export_email_template_types = true;
            $this->access_email_variables = true;
            $this->create_email_variables = true;
            $this->show_email_variables = true;
            $this->edit_email_variables = true;
            $this->delete_email_variables = true;
            $this->export_email_variables = true;
            $this->access_imports_management = true;
            $this->access_reports_management = true;
            // Set other permission properties to true similarly
        } else {
            // If "all_permission" is unchecked, set all other permission properties to false
            $this->access_dashboard = false;
            $this->access_apps_management = false;
            $this->access_businesses = false;
            $this->create_businesses = false;
            $this->show_businesses = false;
            $this->edit_businesses = false;
            $this->delete_businesses = false;
            $this->export_businesses = false;
            $this->access_departments = false;
            $this->create_departments = false;
            $this->show_departments = false;
            $this->edit_departments = false;
            $this->delete_departments = false;
            $this->export_departments = false;
            $this->access_workshifts = false;
            $this->create_workshifts = false;
            $this->show_workshifts = false;
            $this->edit_workshifts = false;
            $this->delete_workshifts = false;
            $this->export_workshifts = false;
            $this->access_fortnight = false;
            $this->create_fortnight = false;
            $this->access_users = false;
            $this->create_users = false;
            $this->show_users = false;
            $this->edit_users = false;
            $this->delete_users = false;
            $this->export_users = false;
            $this->access_roles = false;
            $this->create_roles = false;
            $this->show_roles = false;
            $this->edit_roles = false;
            $this->delete_roles = false;
            $this->export_roles = false;
            $this->access_company_bank_details = false;
            $this->create_company_bank_details = false;
            $this->show_company_bank_details = false;
            $this->edit_company_bank_details = false;
            $this->delete_company_bank_details = false;
            $this->export_company_bank_details = false;
            $this->access_taxes = false;
            $this->create_taxes = false;
            $this->show_taxes = false;
            $this->edit_taxes = false;
            $this->delete_taxes = false;
            $this->export_taxes = false;
            $this->access_holidays = false;
            $this->create_holidays = false;
            $this->show_holidays = false;
            $this->edit_holidays = false;
            $this->delete_holidays = false;
            $this->export_holidays = false;
            $this->access_nasfunds = false;
            $this->create_nasfunds = false;
            $this->show_nasfunds = false;
            $this->edit_nasfunds = false;
            $this->delete_nasfunds = false;
            $this->export_nasfunds = false;
            $this->access_hr_management = false;
            $this->access_employees = false;
            $this->create_employees = false;
            $this->show_employees = false;
            $this->edit_employees = false;
            $this->delete_employees = false;
            $this->import_employees = false;
            $this->export_employees = false;
            $this->access_designations = false;
            $this->create_designations = false;
            $this->show_designations = false;
            $this->edit_designations = false;
            $this->delete_designations = false;
            $this->export_designations = false;
            $this->access_employee_statuses = false;
            $this->create_employee_statuses = false;
            $this->show_employee_statuses = false;
            $this->edit_employee_statuses = false;
            $this->delete_employee_statuses = false;
            $this->export_employee_statuses = false;
            $this->access_attendances = false;
            $this->access_logs_timesheets = false;
            $this->create_attendances = false;
            $this->show_attendances = false;
            $this->edit_attendances = false;
            $this->delete_attendances = false;
            $this->import_attendances = false;
            $this->export_attendances = false;
            $this->access_attendance_adjustments = false;
            $this->access_leaves = false;
            $this->create_leaves = false;
            $this->show_leaves = false;
            $this->edit_leaves = false;
            $this->delete_leaves = false;
            $this->export_leaves = false;
            $this->access_payroll = false;
            $this->create_payroll = false;
            $this->show_payroll = false;
            $this->edit_payroll = false;
            $this->delete_payroll = false;
            $this->export_payroll = false;
            $this->access_payruns = false;
            $this->create_payruns = false;
            $this->show_payruns = false;
            $this->edit_payruns = false;
            $this->delete_payruns = false;
            $this->export_payruns = false;
            $this->access_payslips = false;
            $this->create_payslips = false;
            $this->show_payslips = false;
            $this->edit_payslips = false;
            $this->delete_payslips = false;
            $this->export_payslips = false;
            $this->access_aba_generator = false;
            $this->access_loans = false;
            $this->create_loans = false;
            $this->show_loans = false;
            $this->edit_loans = false;
            $this->delete_loans = false;
            $this->export_loans = false;
            $this->access_loan_types = false;
            $this->create_loan_types = false;
            $this->show_loan_types = false;
            $this->edit_loan_types = false;
            $this->delete_loan_types = false;
            $this->export_loan_types = false;
            $this->access_assets = false;
            $this->create_assets = false;
            $this->show_assets = false;
            $this->edit_assets = false;
            $this->delete_assets = false;
            $this->export_assets = false;
            $this->access_asset_types = false;
            $this->create_asset_types = false;
            $this->show_asset_types = false;
            $this->edit_asset_types = false;
            $this->delete_asset_types = false;
            $this->export_asset_types = false;
            $this->access_general_settings = false;
            $this->access_email_templates = false;
            $this->create_email_templates = false;
            $this->show_email_templates = false;
            $this->edit_email_templates = false;
            $this->delete_email_templates = false;
            $this->export_email_templates = false;
            $this->access_email_template_types = false;
            $this->create_email_template_types = false;
            $this->show_email_template_types = false;
            $this->edit_email_template_types = false;
            $this->delete_email_template_types = false;
            $this->export_email_template_types = false;
            $this->access_email_variables = false;
            $this->create_email_variables = false;
            $this->show_email_variables = false;
            $this->edit_email_variables = false;
            $this->delete_email_variables = false;
            $this->export_email_variables = false;
            $this->access_imports_management = false;
            $this->access_reports_management = false;
            // Set other permission properties to false similarly
        }
    }
}
