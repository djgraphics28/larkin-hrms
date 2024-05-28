<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use App\Models\Business;
use App\Models\BusinessUser;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class UserComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove'];
    public $approveConfirmed;
    // filters
    #[Url]
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Add New User';
    public $updateMode = false;

    public $name;
    public $email;
    public $password;
    public $role;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    public $selectAllBusiness = false;
    public $selectedBusinessRows = [];
    public $businesses = [];
    public $roles = [];

    #[Title('Users')]
    public function render()
    {
        return view('livewire.user.user-component', [
            'records' => $this->records
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->businesses = Business::where('is_active', 1)->get();
        $this->roles = Role::whereNot('name','SuperAdmin')->get();
    }

    public function getRecordsProperty()
    {
        return User::whereNot('is_super_admin', 1)->with(['businesses', 'roles'])->search(trim($this->search))
            ->paginate($this->perPage);
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
        $this->modalTitle = 'Add New User';
        $this->updateMode = false;

    }

    public function submit($saveAndCreateNew)
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);

        if(empty($this->selectedBusinessRows)) {
            $this->alert('warning', 'Please assign business to this user!');
        }else{

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $user->assignRole($this->role);
            $user->businesses()->sync($this->selectedBusinessRows);

            $businessUser = BusinessUser::where('user_id',$user->id)->first();
            $businessUser->update(['is_active' => 1]);

            if ($user) {
                $this->resetInputFields();
                if ($saveAndCreateNew) {
                    $this->alert('success', 'New User has been save successfully!');
                } else {
                    $this->dispatch('hide-add-modal');
                    $this->alert('success', 'New User has been save successfully!');
                }
            }
        }

    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = '';
        $this->selectedBusinessRows = [];
    }

    public function edit($id)
    {
        $this->edit_id = $id;
        $this->dispatch('show-add-modal');
        $data = User::find($id);
        $this->name = $data->name;
        $this->email = $data->email;
        $this->role = $data->roles()->pluck('name')->toArray();
        $this->selectedBusinessRows = $data->businesses()->pluck('business_id')->toArray();
        $this->modalTitle = 'Edit ' . $this->name;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required'
        ]);

        $data = User::find($this->edit_id);

        if ($this->password !== '') {
            $data->password = Hash::make($this->password);
        }

        $data->name = $this->name;
        $data->email = $this->email;
        $data->save();

        $data->syncRoles($this->role);

        $data->businesses()->sync($this->selectedBusinessRows);

        if ($data) {
            $this->dispatch('hide-add-modal');

            $this->resetInputFields();

            $this->alert('success', $data->name . ' has been updated!');
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
        $delete = User::find($this->approveConfirmed);
        $name = $delete->name;
        $delete->delete();
        if ($delete) {
            $this->alert('success', $name . ' has been removed!');
        }
    }
}
