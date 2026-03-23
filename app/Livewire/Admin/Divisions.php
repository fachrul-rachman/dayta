<?php

namespace App\Livewire\Admin;

use App\Models\Division;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\BulkImport\DivisionImportService;

class Divisions extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;

    public string $name = '';
    public bool $is_active = true;

    public bool $showImportModal = false;

    public $importFile;

    /** @var array<int, array<string, mixed>> */
    public array $importRows = [];

    public int $importTotal = 0;

    public int $importCreates = 0;

    public int $importUpdates = 0;

    public int $importErrors = 0;

    public bool $importCommitted = false;

    public function edit(int $id): void
    {
        $division = Division::findOrFail($id);
        $this->editingId = $division->id;
        $this->name = $division->name;
        $this->is_active = $division->is_active;
    }

    public function create(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        if ($this->editingId) {
            $division = Division::findOrFail($this->editingId);
            $division->update($data);
        } else {
            Division::create($data);
        }

        $this->create();
    }

    public function openImport(): void
    {
        $this->resetImportState();
        $this->showImportModal = true;
    }

    public function previewImport(DivisionImportService $service): void
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

    public function commitImport(DivisionImportService $service): void
    {
        if (empty($this->importRows)) {
            return;
        }

        $rows = array_filter($this->importRows, fn (array $row) => ($row['status'] ?? null) === 'ok');

        if (empty($rows)) {
            return;
        }

        $service->commit(array_values($rows));

        $this->importCommitted = true;

        $this->importRows = [];
        $this->importTotal = 0;
        $this->importCreates = 0;
        $this->importUpdates = 0;
        $this->importErrors = 0;

        $this->dispatch('divisions-imported');
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

    public function render()
    {
        return view('pages.admin.divisions', [
            'divisions' => Division::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
