<?php

declare(strict_types=1);

namespace App\Http\Controllers\Traits;


use Illuminate\Http\Request;

trait DataProvider
{
    protected function prepareModel(Request $request, $query, bool $isJoin = false)
    {
        $this->filterModel($query, $isJoin, $request->filter);
        $this->sortModel($query, (string) $request->sort);

        return $query;
    }

    protected function filterModel($query, bool $isJoin, array $filter)
    {
        $isQueryBuilder = $isJoin && $query instanceof \Illuminate\Database\Query\Builder;

        if ($isQueryBuilder) {
            $columns = [];
            foreach ($query->columns as $column) {
                $parts = explode(' ', $column);
                $columns[$parts[count($parts) - 1]] = $parts[0];
            }
        }


        foreach ($filter as $filterKey => $param) {
            $col = $isQueryBuilder ? $columns[$filterKey] : $filterKey;
            $this->addCondition($query, $col, $param);
        }
    }

    protected function sortModel($query, string $sort)
    {
        if ($sort !== '') {
            if ($sort[0] === '-') {
                $sort = substr($sort, 1);
                $query->orderByDesc($sort);
            } else {
                $query->orderBy($sort);
            }
        }
    }

    protected function addCondition($query, string $col, $param)
    {
        if (is_string($param)) {
            $query->where($col, $param);
        } else if(is_array($param)) {
            foreach($param as $key => $value) {
                if (is_string($value)) {
                    if ($key === 'like') {
                        $value = "%$value%";
                    }
                    $query->where($col, $key, $value);
                }
            }
        }
    }
}