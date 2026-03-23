<?php

namespace App\Livewire\Admin;

use App\Enums\UserRole;
use App\Models\Division;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Users extends Component
{
    public ?int $editingId = null;

    public string $name = '';
    public string $email = '';
    public string $role = 'manager';
    public ?int $division_id = null;
    public bool $is_active = true;
    public string $password = '';

    public function create(): void
    {
        $this->editingId = null;
        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $user = User::findOrFail($id);

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role->value;
        $this->division_id = $user->division_id;
        $this->is_active = $user->is_active;
        $this->password = '';
    }

    public function save(): void
    {
        $roles = array_map(fn (UserRole $r) => $r->value, UserRole::cases());

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingId)],
            'role' => ['required', Rule::in($roles)],
            'division_id' => ['nullable', 'integer', 'exists:divisions,id'],
            'is_active' => ['boolean'],
        ];

        if (! $this->editingId) {
            $rules['password'] = ['required', 'string', 'min:8'];
        } elseif ($this->password !== '') {
            $rules['password'] = ['string', 'min:8'];
        }

        $data = $this->validate($rules);

        // Manager and HoD require division; Director/Admin do not by default.
        if (in_array($data['role'], [UserRole::Manager->value, UserRole::Hod->value], true) && ! $data['division_id']) {
            $this->addError('division_id', __('Division is required for this role.'));

            return;
        }

        if (! $this->editingId) {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'role' => $data['role'],
                'division_id' => $data['division_id'],
                'is_active' => $data['is_active'] ?? true,
            ]);
        } else {
            $user = User::findOrFail($this->editingId);

            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->role = $data['role'];
            $user->division_id = $data['division_id'];
            $user->is_active = $data['is_active'] ?? true;

            if (! empty($this->password)) {
                $user->password = bcrypt($this->password);
            }

            $user->save();
        }

        $this->resetForm();
        $this->editingId = null;
    }

    private function resetForm(): void
    {
        $this->name = '';
        $this->email = '';
        $this->role = UserRole::Manager->value;
        $this->division_id = null;
        $this->is_active = true;
        $this->password = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('pages.admin.users', [
            'users' => User::with('division')->orderBy('name')->get(),
            'divisions' => Division::orderBy('name')->get(),
            'roles' => UserRole::cases(),
        ])->layout('layouts.app');
    }
}
