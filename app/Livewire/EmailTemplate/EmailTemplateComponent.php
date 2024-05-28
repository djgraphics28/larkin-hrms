<?php

namespace App\Livewire\EmailTemplate;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\EmailTemplate;
use App\Models\EmailVariable;
use Livewire\Attributes\Title;
use App\Models\EmailTemplateType;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class EmailTemplateComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove'];
    public $approveConfirmed;
    // filters
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Add New Email Template';
    public $updateMode = false;

    public $title;
    public $subject;
    public $body = '';
    public $email_template_type;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    public $emailTemplateTypes = [];
    public $variables = [];

    #[Title('Email Template Type')]
    public function render()
    {
        return view('livewire.email-template.email-template-component',[
            'records' => $this->records
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->emailTemplateTypes = EmailTemplateType::all();
        $this->variables = EmailVariable::all();
    }

    public function getRecordsProperty()
    {
        return EmailTemplate::search(trim($this->search))
            ->orderBy('email_template_type_id','asc')
            ->paginate($this->perPage);
    }

    public function updatedSelectAll($value)
    {
        if($value){
            $this->selectedRows = $this->records->pluck('id');
        }else{
            $this->selectedRows = [];
        }
    }

    public function addNew()
    {
        $this->resetInputFields();
        $this->dispatch('show-add-modal');
        $this->modalTitle = 'Add New Email Template';
        $this->updateMode = false;

    }

    public function submit($saveAndCreateNew)
    {
        $this->validate([
            'title' => 'required',
            'subject' => 'required',
            'body' => 'required',
            'email_template_type' => 'required',
        ]);

        $create = EmailTemplate::create([
            'title' => $this->title,
            'subject' => $this->subject,
            'body' => $this->body,
            'email_template_type_id' => $this->email_template_type,
        ]);

        if($create){
            $this->resetInputFields();
            if($saveAndCreateNew) {
                $this->alert('success', 'New Email Template has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');
                $this->alert('success', 'New Email Template has been save successfully!');
            }
        }
    }

    public function resetInputFields()
    {
        $this->title = '';
        $this->subject = '';
        $this->body = '';
        $this->email_template_type = '';
    }

    public function edit($id)
    {
        $this->edit_id = $id;
        $this->dispatch('show-add-modal');
        $data = EmailTemplate::find($id);

        $this->title = $data->title;
        $this->subject = $data->subject;
        $this->body = $data->body;
        $this->email_template_type = $data->email_template_type_id;
        $this->modalTitle = 'Edit '.$this->title;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'title' => 'required',
            'subject' => 'required',
            'body' => 'required',
            'email_template_type' => 'required',
        ]);

        $data = EmailTemplate::find($this->edit_id);
        $data->update([
            'title' => $this->title,
            'subject' => $this->subject,
            'body' => $this->body,
            'email_template_type_id' => $this->email_template_type,
        ]);

        if($data) {
            $this->dispatch('hide-add-modal');

            $this->resetInputFields();

            $this->alert('success', $data->title.' has been updated!');
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
        $delete = EmailTemplate::find($this->approveConfirmed);
        $name = $delete->title;
        $delete->delete();
        if($delete){
            $this->alert('success', $name.' has been removed!');
        }
    }

    // public function insertVariable($variable)
    // {
    //     $this->body .= "{{$variable}}"; // Append the selected variable to the body

    // }
}
