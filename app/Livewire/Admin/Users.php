<?php

namespace App\Livewire\Admin;

use App\Enums\UserRole;
use App\Models\Division;
use App\Services\BulkImport\UserImportService;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Users extends Component
{
    use WithFileUploads;
    use WithPagination;

    public ?int $editingId = null;

    public string $name = '';
    public string $email = '';
    public string $role = 'manager';
    public ?int $division_id = null;
    public bool $is_active = true;
    public string $password = '';

    public bool $showImportModal = false;

    public $importFile;

    /** @var array<int, array<string, mixed>> */
    public array $importRows = [];

    public int $importTotal = 0;

    public int $importCreates = 0;

    public int $importUpdates = 0;

    public int $importErrors = 0;

    public bool $importCommitted = false;

    public ?string $filterRole = null;

    public ?int $filterDivisionId = null;

    public ?string $filterStatus = null;

    public string $search = '';

    protected $queryString = [
        'filterRole' => ['except' => null],
        'filterDivisionId' => ['except' => null],
        'filterStatus' => ['except' => null],
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

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

    public function updatedFilterRole(): void
    {
        $this->resetPage();
    }

    public function updatedFilterDivisionId(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openImport(): void
    {
        $this->resetImportState();
        $this->showImportModal = true;
    }

    public function previewImport(UserImportService $service): void
    {
        $this->resetErrorBag();

        $this->validate([
            'importFile' => ['required', 'file'],
        ]);

        $path = $this->importFile->getRealPath();

        $result = $service->preview($path);

        $this->importRows = $result->rows;
        $this->importTotal = $result->total;
        $this->importCreates = $result->creates;
        $this->importUpdates = $result->updates;
        $this->importErrors = $result->errors;
        $this->importCommitted = false;
    }

    public function commitImport(UserImportService $service): void
    {
        if (! $this->importFile) {
            return;
        }

        $service->commit($this->importFile->getRealPath());

        $this->importCommitted = true;

        $this->importRows = [];
        $this->importTotal = 0;
        $this->importCreates = 0;
        $this->importUpdates = 0;
        $this->importErrors = 0;

        $this->dispatch('users-imported');
    }

    public function closeImport(): void
    {
        $this->resetImportState();
        $this->showImportModal = false;
    }

    private function resetImportState(): void
    {
        $this->importFile = null;
        $this->importRows = [];
        $this->importTotal = 0;
        $this->importCreates = 0;
        $this->importUpdates = 0;
        $this->importErrors = 0;
        $this->importCommitted = false;
        $this->resetErrorBag();
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
        $usersQuery = User::with('division');

        if ($this->filterRole) {
            $usersQuery->where('role', $this->filterRole);
        }

        if ($this->filterDivisionId) {
            $usersQuery->where('division_id', $this->filterDivisionId);
        }

        if ($this->filterStatus === 'active') {
            $usersQuery->where('is_active', true);
        } elseif ($this->filterStatus === 'inactive') {
            $usersQuery->where('is_active', false);
        }

        if ($this->search !== '') {
            $search = $this->search;

            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'ILIKE', '%'.$search.'%')
                    ->orWhere('email', 'ILIKE', '%'.$search.'%');
            });
        }

        $users = $usersQuery
            ->orderBy('name')
            ->paginate(20);

        return view('pages.admin.users', [
            'users' => $users,
            'divisions' => Division::orderBy('name')->get(),
            'roles' => UserRole::cases(),
        ])->layout('layouts.app');
    }
}
