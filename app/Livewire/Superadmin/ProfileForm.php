<?php
namespace App\Livewire\Superadmin;

use App\Models\User;
use App\Models\Salary;
use App\Models\BankDetail;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class ProfileForm extends Component
{
    use WithPagination;

    public $user_id;
    public $sr_no, $name, $email, $user_email, $password, $designation, $ref_no, $date_joined, $status, $wfh_hybrid, $date_left;
    public $father_name, $dob, $address, $contact_number, $on_role_date;
    public $salary, $salary_on_role;
    public $account_name, $account_number, $ifsc, $bank_name, $upi_id;

    public $isEdit = false;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|string',
        'email' => 'required|email',
        'user_email' => 'required|email',
        'password' => 'required|string|min:8',
        'salary' => 'nullable|numeric',
        'salary_on_role' => 'nullable|numeric',
        'account_number' => 'nullable|string',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'user_id', 'sr_no', 'name', 'email', 'password', 'user_email', 'designation', 'ref_no', 'date_joined',
            'status', 'wfh_hybrid', 'date_left', 'father_name', 'dob', 'address', 'contact_number',
            'on_role_date', 'salary', 'salary_on_role', 'account_name', 'account_number', 'ifsc', 'bank_name', 'upi_id', 'isEdit'
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $user = User::updateOrCreate(
            ['id' => $this->user_id],
            [
                'sr_no' => $this->sr_no,
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'user_email' => $this->user_email,
                'designation' => $this->designation,
                'ref_no' => $this->ref_no,
                'date_joined' => $this->date_joined,
                'status' => $this->status,
                'wfh_hybrid' => $this->wfh_hybrid,
                'date_left' => $this->date_left,
                'father_name' => $this->father_name,
                'dob' => $this->dob,
                'address' => $this->address,
                'contact_number' => $this->contact_number,
                'on_role_date' => $this->on_role_date,
            ]
        );

        Salary::updateOrCreate(
            ['user_id' => $user->id],
            [
                'salary' => $this->salary,
                'salary_on_role' => $this->salary_on_role,
            ]
        );

        BankDetail::updateOrCreate(
            ['user_id' => $user->id],
            [
                'account_name' => $this->account_name,
                'account_number' => $this->account_number,
                'ifsc' => $this->ifsc,
                'bank_name' => $this->bank_name,
                'upi_id' => $this->upi_id,
            ]
        );

        session()->flash('message', $this->isEdit ? 'User updated successfully' : 'User created successfully');
        $this->closeModal();
    }

    public function edit($id)
    {
        $user = User::with(['salary', 'bankDetail'])->findOrFail($id);

        $this->fill($user->toArray());
        $this->user_id = $user->id;
        $this->salary = $user->salary->salary ?? null;
        $this->salary_on_role = $user->salary->salary_on_role ?? null;
        $this->account_name = $user->bankDetail->account_name ?? null;
        $this->account_number = $user->bankDetail->account_number ?? null;
        $this->ifsc = $user->bankDetail->ifsc ?? null;
        $this->bank_name = $user->bankDetail->bank_name ?? null;
        $this->upi_id = $user->bankDetail->upi_id ?? null;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('message', 'User deleted successfully');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $users = User::with(['salary', 'bankDetail'])
                            ->where('role', 'user')
                            ->paginate(10);
        return view('livewire.superadmin.profile-form', ['users' => $users])->layout('layouts.app');
    }
}