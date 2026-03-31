<?php

namespace App\Livewire\Manager;

use App\Enums\BigRockStatus;
use App\Enums\DailyPlanStatus;
use App\Enums\DailyRealizationStatus;
use App\Enums\WorkType;
use App\Models\BigRock;
use App\Models\DailyEntry as DailyEntryModel;
use App\Models\DailyEntryItem;
use App\Models\DailyEntryItemAttachment;
use App\Models\ReportSetting;
use App\Services\FlagEvaluationService;
use App\Services\ReportTimingService;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

class DailyEntry extends Component
{
    use WithFileUploads;

    public string $mode = 'plan';

    public ?DailyEntryModel $entry = null;

    /** @var array<int, array<string, mixed>> */
    public array $planItems = [];

    /** @var array<int, array<string, mixed>> */
    public array $realizationItems = [];

    /** @var array<int, array<int, mixed>> */
    public array $planUploads = [];

    /** @var array<int, array<int, mixed>> */
    public array $realizationUploads = [];

    public ?ReportSetting $settings = null;

    public function getTimingServiceProperty(): ReportTimingService
    {
        return app(ReportTimingService::class);
    }

    public function mount(ReportTimingService $timing): void
    {
        $user = auth()->user();

        $this->settings = $timing->currentSettings();

        if (! $this->settings) {
            $this->entry = null;

            return;
        }

        $today = $timing->now()->toDateString();

        $this->entry = DailyEntryModel::firstOrCreate(
            [
                'user_id' => $user->id,
                'entry_date' => $today,
            ],
            [
                'division_id' => $user->division_id,
            ],
        );

        $this->applyTimingRules();

        $this->loadItems();
    }

    public function switchMode(string $mode): void
    {
        if (! in_array($mode, ['plan', 'realization'], true)) {
            return;
        }

        $this->mode = $mode;
    }

    public function addItem(): void
    {
        if (! $this->canEditCurrentMode()) {
            return;
        }

        $target = $this->mode === 'plan' ? 'planItems' : 'realizationItems';

        $this->{$target}[] = [
            'description' => '',
            'work_type' => WorkType::Operational->value,
            'big_rock_id' => null,
            'planned_hours' => null,
            'realized_hours' => null,
            'notes' => null,
        ];
    }

    public function removeItem(int $index): void
    {
        $target = $this->mode === 'plan' ? 'planItems' : 'realizationItems';

        if (isset($this->{$target}[$index])) {
            unset($this->{$target}[$index]);
            $this->{$target} = array_values($this->{$target});
        }
    }

    public function saveDraft(): void
    {
        if (! $this->entry || ! $this->settings) {
            return;
        }

        if (! $this->canEditCurrentMode()) {
            return;
        }

        $this->persistItems(draft: true);

        $this->dispatch('toast', message: __('Draft saved successfully.'), type: 'success');
    }

    public function submit(): void
    {
        if (! $this->entry || ! $this->settings) {
            return;
        }

        if (! $this->canEditCurrentMode()) {
            return;
        }

        $this->persistItems(draft: false);

        app(FlagEvaluationService::class)->evaluateForEntry($this->entry);

        $this->dispatch('toast', message: __('Entry submitted successfully.'), type: 'success');
    }

    private function loadItems(): void
    {
        if (! $this->entry) {
            $this->planItems = [];
            $this->realizationItems = [];
            $this->planUploads = [];
            $this->realizationUploads = [];

            return;
        }

        $items = $this->entry->items()->with('attachments')->get()->groupBy(function (DailyEntryItem $item) {
            return $item->planned_hours !== null && $item->planned_hours >= 0 ? 'plan' : 'realization';
        });
        $this->entry->setRelation('items', $items->flatten());

        $this->planItems = $items->get('plan', collect())->map(fn (DailyEntryItem $item) => [
            'id' => $item->id,
            'description' => $item->description,
            'work_type' => $item->work_type->value,
            'big_rock_id' => $item->big_rock_id,
            'planned_hours' => $item->planned_hours,
            'notes' => $item->notes,
        ])->values()->all();

        $this->realizationItems = $items->get('realization', collect())->map(fn (DailyEntryItem $item) => [
            'id' => $item->id,
            'description' => $item->description,
            'work_type' => $item->work_type->value,
            'big_rock_id' => $item->big_rock_id,
            'realized_hours' => $item->realized_hours,
            'notes' => $item->notes,
        ])->values()->all();

        $this->planUploads = [];
        $this->realizationUploads = [];
    }

    private function persistItems(bool $draft): void
    {
        $user = auth()->user();
        $divisionId = $user->division_id;

        if ($this->mode === 'plan') {
            $this->validate([
                'planItems' => 'array|min:1',
                'planItems.*.description' => 'required|string',
                'planItems.*.work_type' => 'required|string',
                'planItems.*.big_rock_id' => 'nullable|integer',
                'planItems.*.planned_hours' => 'nullable|numeric|min:0',
                'planItems.*.notes' => 'nullable|string',
                'planUploads.*.*' => 'file|max:51200',
            ]);

            $this->entry->plan_status = $draft ? DailyPlanStatus::Draft : DailyPlanStatus::Submitted;
            $this->entry->division_id = $divisionId;

            if (! $draft) {
                $this->entry->plan_submitted_at = now();
            }

            $this->entry->save();

            $this->syncItems($this->planItems, true, $this->planUploads);
        } else {
            $this->validate([
                'realizationItems' => 'array|min:1',
                'realizationItems.*.description' => 'required|string',
                'realizationItems.*.work_type' => 'required|string',
                'realizationItems.*.big_rock_id' => 'nullable|integer',
                'realizationItems.*.realized_hours' => 'nullable|numeric|min:0',
                'realizationItems.*.notes' => 'nullable|string',
                'realizationUploads.*.*' => 'file|max:51200',
            ]);

            $this->entry->realization_status = $draft ? DailyRealizationStatus::Draft : DailyRealizationStatus::Submitted;
            $this->entry->division_id = $divisionId;

            if (! $draft) {
                $this->entry->realization_submitted_at = now();
            }

            $this->entry->save();

            $this->syncItems($this->realizationItems, false, $this->realizationUploads);
        }

        $this->loadItems();
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private function syncItems(array $items, bool $isPlan, array $uploads = []): void
    {
        $existing = $this->entry->items()
            ->when($isPlan, fn ($q) => $q->whereNotNull('planned_hours'))
            ->when(! $isPlan, fn ($q) => $q->whereNotNull('realized_hours'))
            ->get()
            ->keyBy('id');

        $seen = [];

        foreach ($items as $rowIndex => $row) {
            $id = $row['id'] ?? null;

            $plannedHours = $isPlan ? ($row['planned_hours'] ?? 0) : null;
            $realizedHours = $isPlan ? null : ($row['realized_hours'] ?? 0);

            $data = [
                'description' => $row['description'],
                'work_type' => $row['work_type'],
                'big_rock_id' => $row['big_rock_id'] ?? null,
                'planned_hours' => $plannedHours,
                'realized_hours' => $realizedHours,
                'notes' => $row['notes'] ?? null,
            ];

            $itemModel = null;

            if ($id && $existing->has($id)) {
                $existing[$id]->update($data);
                $itemModel = $existing[$id];
                $seen[] = $id;
            } else {
                $itemModel = $this->entry->items()->create($data);
                $seen[] = $itemModel->id;
            }

            if ($itemModel && isset($uploads[$rowIndex])) {
                foreach ((array) $uploads[$rowIndex] as $file) {
                    if (! $file) {
                        continue;
                    }

                    $disk = config('reporting.attachments_disk');
                    $basePath = trim(config('reporting.attachments_path'), '/');

                    $path = $file->storeAs(
                        $basePath.'/'.$this->entry->entry_date->toDateString().'/'.$this->entry->user_id,
                        uniqid('att_').'-'.$file->getClientOriginalName(),
                        $disk
                    );
                    $size = 0;
                    try {
                        $reportedSize = $file->getSize();
                        if (is_int($reportedSize) && $reportedSize >= 0) {
                            $size = $reportedSize;
                        }
                    } catch (\Throwable $e) {
                        $size = 0;
                    }

                    DailyEntryItemAttachment::create([
                        'daily_entry_item_id' => $itemModel->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_mime' => $file->getMimeType(),
                        'file_size' => $size,
                    ]);
                }
            }
        }

        $toDelete = $existing->keys()->diff($seen);

        if ($toDelete->isNotEmpty()) {
            $this->entry->items()->whereIn('id', $toDelete)->delete();
        }
    }

    public function getPlanEditableProperty(): bool
    {
        if (! $this->entry || ! $this->settings) {
            return false;
        }

        $now = $this->timingService->now();

        if (! $this->timingService->isWithinWindow($this->settings->plan_open_rule, $this->settings->plan_close_rule, $now)) {
            return false;
        }

        return in_array($this->entry->plan_status, [DailyPlanStatus::Open, DailyPlanStatus::Draft], true);
    }

    public function getRealizationEditableProperty(): bool
    {
        if (! $this->entry || ! $this->settings) {
            return false;
        }

        $now = $this->timingService->now();

        if (! $this->timingService->isWithinWindow($this->settings->realization_open_rule, $this->settings->realization_close_rule, $now)) {
            return false;
        }

        return in_array($this->entry->realization_status, [DailyRealizationStatus::Open, DailyRealizationStatus::Draft], true);
    }

    private function applyTimingRules(): void
    {
        if (! $this->entry || ! $this->settings) {
            return;
        }

        $now = $this->timingService->now();

        $planWindow = $this->timingService->windowState($this->settings->plan_open_rule, $this->settings->plan_close_rule, $now);
        $realWindow = $this->timingService->windowState($this->settings->realization_open_rule, $this->settings->realization_close_rule, $now);

        if (! in_array($this->entry->plan_status, [DailyPlanStatus::Draft, DailyPlanStatus::Submitted], true)) {
            $this->entry->plan_status = match ($planWindow) {
                'open' => DailyPlanStatus::Open,
                'closed' => DailyPlanStatus::Closed,
                default => DailyPlanStatus::Locked,
            };
        }

        if (! in_array($this->entry->realization_status, [DailyRealizationStatus::Draft, DailyRealizationStatus::Submitted], true)) {
            $this->entry->realization_status = match ($realWindow) {
                'open' => DailyRealizationStatus::Open,
                'closed' => DailyRealizationStatus::Closed,
                default => DailyRealizationStatus::Locked,
            };
        }

        $this->entry->save();
    }

    private function canEditCurrentMode(): bool
    {
        if ($this->mode === 'plan') {
            return $this->planEditable;
        }

        return $this->realizationEditable;
    }

    public function getAvailableBigRocksProperty(): Collection
    {
        $user = auth()->user();

        if (! $user || ! $user->division_id) {
            return collect();
        }

        return BigRock::query()
            ->where('division_id', $user->division_id)
            ->where('status', BigRockStatus::Active)
            ->orderBy('title')
            ->get();
    }

    public function render()
    {
        return view('pages.manager.daily-entry', [
            'availableBigRocks' => $this->availableBigRocks,
            'settings' => $this->settings,
        ])->layout('layouts.app');
    }
}
