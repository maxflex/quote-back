<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Validation rules shorthand
     */
    protected $validationRules = [];

    protected $filters = [];

    protected $filterTablePrefix = [];

    protected $mapFilters = [];

    protected function filter(Request $request, &$query, array $filters = null)
    {
        if (isset($request->sort)) {
            // @говнокод
            // TODO: переделать чтоб фронт передавал asc/desc и здесь обрабатывать
            // TODO: добавить сортировку по умолчанию, если не указана и возможность есть
            $sortField = $this->getFieldName($request->sort);
            $sortOrder = strpos($sortField, 'asc') !== false ? '' : 'desc';

            $query->orderByRaw($sortField . ' ' . $sortOrder);
        }

        $filters = $filters ?? $this->filters;
        foreach ($filters as $type => $fields) {
            foreach ($fields as $key_field => $field) {
                $f = is_array($field) ? $key_field : $field;
                if (isset($request[$f])) {
                    $this->{'filter' . ucfirst($type)}($field, $request->{$f}, $query);
                }
            }
        }
    }

    protected function showBy(Request $request, $query)
    {
        return $query->paginate($request->paginate ?: 9999);
    }

    protected function showAll($query)
    {
        return $query->paginate(9999);
    }

    protected function handleIndexRequest(Request $request, $query, $resourceCollectionClass = null)
    {
        if ($request->has('count')) {
            return $query->count();
        }

        $result = $query->paginate($request->paginate ?: 9999);

        return $resourceCollectionClass === null ? $result : $resourceCollectionClass::collection($result);
    }

    protected function handleValidation(Request &$request, array $additionalValidationRules = [])
    {
        $request->validate(array_merge($this->validationRules, $additionalValidationRules));
    }

    protected function checkRelationsBeforeDestroy(Model $model, array $relations)
    {
        foreach ($relations as $relation) {
            if ($model->{$relation}()->count() > 0) {
                abort(409, 'Содержит данные');
            }
        }
    }

    /**
     * FILTERS TYPES
     */

    protected function filterMultiple(string $field, $value, &$query)
    {
        $values = explode(',', $value);
        $field = $this->getFieldName($field);

        $query->where(function ($query) use ($values, $field) {
            $query->whereIn($field, $values);

            // интерпретируем -2 как null
            if (in_array(-2, $values)) {
                $query->orWhereNull($field);
            }
        });
    }

    protected function filterEquals(string $field, $value, &$query)
    {
        $query->where($this->getFieldName($field), $value);
    }

    protected function filterNotNull(string $field, $value, &$query)
    {
        $query->whereNotNull($this->getFieldName($field));
    }

    /**
     * NULL если запись не существует
     * NOT NULL если существует
     */
    protected function filterExists(string $field, $value, &$query)
    {
        if ((int) $value === 1) {
            $query->whereNotNull($this->getFieldName($field));
        } else {
            $query->whereNull($this->getFieldName($field));
        }
    }

    protected function filterExclude(string $field, $value, &$query)
    {
        $query->where($field, '<>', $value);
    }

    protected function filterLike(string $field, $value, &$query)
    {
        $query->where($this->getFieldName($field), 'like', '%' . $value . '%');
    }

    protected function filterInterval(string $field, $value, &$query)
    {
        $value = json_decode($value);
        $field = $this->getFieldName($field);

        if (isset($value->start)) {
            $query->whereRaw("DATE({$field}) >= '{$value->start}'");
        }
        if (isset($value->end)) {
            $query->whereRaw("DATE({$field}) <= '{$value->end}'");
        }
    }

    protected function filterLikeMultiple(array $fields, $value, &$query)
    {
        $query->where(function ($query) use ($fields, $value) {
            foreach ($fields as $field) {
                $query->orWhere($field, 'like', '%' . $value . '%');
            }
        });
    }

    /**
     * Поиск в comma-separated values
     */
    protected function filterFindInSet(string $field, $value, &$query)
    {
        $query->whereRaw("FIND_IN_SET({$value}, {$field})");
    }


    protected function filterFindInSetMultiple(string $field, $values, &$query)
    {
        foreach (explode(',', $values) as $value) {
            $query->whereRaw("FIND_IN_SET({$value}, {$field})");
        }
    }

    private function getFieldName($field)
    {
        foreach ($this->mapFilters as $originalFieldName => $mappedFieldName) {
            if ($field === $originalFieldName) {
                $field = $mappedFieldName;
            }
        }

        foreach ($this->filterTablePrefix as $table => $fields) {
            if (in_array($field, $fields)) {
                $field = "{$table}.{$field}";
            }
        }

        return $field;
    }
}
