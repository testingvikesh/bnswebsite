<?php

namespace App\Services;

use App\Models\AdmissionApplication;
use App\Models\BusinessGrowthAdmission;
use App\Models\JobProfessionalAdmission;
use App\Models\StudentAdmission;
use App\Models\WomenAdmission;
use App\Models\WorkingWomenAdmission;
use App\Models\YouthAdmission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AdmissionFormsService
{
    /** @return array<string, array{label: string, model: class-string<Model>, reference: string}> */
    public function types(): array
    {
        return [
            'online' => [
                'label' => 'Book Your Spot Now (Online)',
                'model' => AdmissionApplication::class,
                'reference' => 'application_number',
            ],
            'youth' => [
                'label' => 'College Youth School',
                'model' => YouthAdmission::class,
                'reference' => 'registration_number',
            ],
            'student' => [
                'label' => 'School Student School',
                'model' => StudentAdmission::class,
                'reference' => 'registration_number',
            ],
            'women' => [
                'label' => 'Women Entrepreneur School',
                'model' => WomenAdmission::class,
                'reference' => 'registration_number',
            ],
            'working-women' => [
                'label' => 'Working Women School',
                'model' => WorkingWomenAdmission::class,
                'reference' => 'registration_number',
            ],
            'job-professional' => [
                'label' => 'Job Professional School',
                'model' => JobProfessionalAdmission::class,
                'reference' => 'registration_number',
            ],
            'business-growth' => [
                'label' => 'Business Growth School',
                'model' => BusinessGrowthAdmission::class,
                'reference' => 'registration_number',
            ],
        ];
    }

    /** @return array<string, int> */
    public function counts(): array
    {
        $counts = [];
        foreach ($this->types() as $key => $config) {
            $counts[$key] = $this->tableExists($config['model'])
                ? $config['model']::query()->count()
                : 0;
        }

        return $counts;
    }

    public function paginate(string $type, ?string $search = null, ?string $status = null, int $perPage = 20): LengthAwarePaginator
    {
        $config = $this->types()[$type] ?? abort(404);
        $model = $config['model'];

        if (! $this->tableExists($model)) {
            return new \Illuminate\Pagination\LengthAwarePaginator(
                [],
                0,
                $perPage,
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        $query = $model::query()->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search, $type, $config) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");

                $ref = $config['reference'];
                $q->orWhere($ref, 'like', "%{$search}%");

                if ($type === 'online') {
                    $q->orWhere('program', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                }
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function find(string $type, int $id): Model
    {
        $config = $this->types()[$type] ?? abort(404);
        $model = $config['model'];

        if (! $this->tableExists($model)) {
            abort(404);
        }

        return $model::query()->findOrFail($id);
    }

    public function updateStatus(string $type, int $id, string $status): Model
    {
        $record = $this->find($type, $id);
        $record->update(['status' => $status]);

        return $record->fresh();
    }

    public function delete(string $type, int $id): void
    {
        $record = $this->find($type, $id);
        $record->delete();
    }

    public function typeLabel(string $type): string
    {
        return $this->types()[$type]['label'] ?? $type;
    }

    public function referenceNumber(Model $record, string $type): string
    {
        $ref = $this->types()[$type]['reference'] ?? 'id';

        return (string) ($record->{$ref} ?? $record->getKey());
    }

    public function programLabel(Model $record, string $type): string
    {
        if ($type === 'online') {
            return trim(($record->program ?? '').' · '.($record->category ?? ''), ' ·');
        }

        return $this->typeLabel($type);
    }

    public function photoUrl(?string $path): ?string
    {
        return $path && Storage::disk('public')->exists($path)
            ? Storage::disk('public')->url($path)
            : null;
    }

    /** @return list<string> */
    public function statusOptions(): array
    {
        return ['pending', 'reviewing', 'approved', 'rejected', 'enrolled'];
    }

    public function formatLabel(string $key): string
    {
        return ucwords(str_replace('_', ' ', $key));
    }

    public function formatValue(mixed $value): string
    {
        if (is_array($value)) {
            return implode(', ', array_map(fn ($v) => is_string($v) ? $v : json_encode($v), $value));
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if ($value === null || $value === '') {
            return '—';
        }

        return (string) $value;
    }

    /** @return Collection<int, array{key: string, label: string, value: string}> */
    public function formDataFields(Model $record, string $type): Collection
    {
        if ($type === 'online') {
            return collect();
        }

        $data = $record->form_data ?? [];

        return collect($data)->map(fn ($value, $key) => [
            'key' => (string) $key,
            'label' => $this->formatLabel((string) $key),
            'value' => $this->formatValue($value),
        ])->values();
    }

    /** @param class-string<Model> $model */
    private function tableExists(string $model): bool
    {
        $instance = new $model;

        return Schema::hasTable($instance->getTable());
    }
}
