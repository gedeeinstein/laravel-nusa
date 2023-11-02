<?php

namespace Creasi\Nusa\Http\Requests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Http\FormRequest;

final class NusaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'with' => ['nullable', 'array'],
            'with.*' => ['string'],
            'codes' => ['nullable', 'array'],
            'codes.*' => ['numeric'],
            'search' => ['nullable', 'string'],
            'page' => ['nullable', 'numeric'],
            'per-page' => ['nullable', 'numeric'],
        ];
    }

    /**
     * @param  \Creasi\Nusa\Models\Model  $model
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function apply($model)
    {
        $model = $model instanceof HasMany
            ? $model
            : $model->load($this->relations($model))->query();

        $result = $model
            ->when($this->has('search'), function (Builder $query) {
                $query->search($this->query('search'));
            })
            ->when($this->has('codes'), function (Builder $query) {
                $query->whereIn($query->getModel()->getKeyName(), (array) $this->query('codes'));
            });

        return $result->paginate($this->query('per-page'));
    }

    /**
     * @param  \Creasi\Nusa\Models\Model  $model
     * @return string[]
     */
    public function relations($model): array
    {
        return \array_filter((array) $this->query('with', []), fn (string $relate) => \method_exists($model, $relate));
    }
}
