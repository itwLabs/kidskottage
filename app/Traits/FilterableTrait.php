<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait FilterableTrait
{
    public function scopeFilter(Builder $query, Request $request)
    {
        $safe = $request->validationData();
        $operator = $request->getOperator();
        $whereList = $request->where();
        $whereKeys = array_keys($whereList);
        foreach ($safe as $key => $value) {
            if (in_array($key, $whereKeys)) {
                $whereList[$key]($query, $operator[$key], $value);
            } elseif ($operator[$key] == "=") {
                if (method_exists($this, $scope = 'scope' . ucfirst($key))) {
                    $this->{$scope}($query, $value);
                } elseif ($this->isRelation($key)) {
                    $query->with([$key]);
                } else {
                    $query->where($key, $operator[$key], $value);
                }
            } elseif ($operator[$key] == "in") {
                $query->whereIn($key, $value);
            } else {
                $query->where($key, $operator[$key], $value);
            }
        }

        $size = $request->only(["_start", "_end"]);
        if (count($size) == 2) {
            if (isset($size[1])) {
                $query->limit($size['_end'] - $size['_start']);
                $query->offset($size['_start']);
            }
        }
        $sort = $request->only(["_order", "_sort"]);
        if (count($sort) == 2) {
            $query->orderBy($sort['_sort'], $sort['_order']);
        }
        return $query;
    }

    public function isRelation($key)
    {
        return method_exists($this, $key) && method_exists($this->{$key}(), 'getQuery');
    }

    protected function applyRelationFilter(Builder $query, $relation, $filters)
    {
        $query->whereHas($relation, function (Builder $q) use ($filters) {
            foreach ($filters as $key => $value) {
                if (method_exists($q->getModel(), $scope = 'scope' . ucfirst($key))) {
                    $q->{$scope}($value);
                } else {
                    $q->where($key, '', $value);
                }
            }
        });
    }
}
