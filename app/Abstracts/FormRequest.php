<?php

namespace App\Abstracts;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Support\Facades\Validator;

abstract class FormRequest extends BaseFormRequest
{
    private $operators = [];

    public function validateResolved()
    {
        return true;
    }

    public function getOperator()
    {
        return $this->operators;
    }
    public function validationData()
    {
        $allData = $this->filterKeys();
        $_newdata = [];
        foreach ($allData as $n => $val) {
            [$k, $v] = $val;
            switch ($k) {
                case "_like":
                case "_ilike":
                    $this->operators[$n] = "like";
                    $_newdata[$n] = "%$v%";
                    break;
                case "_neq":
                    $this->operators[$n] = "!=";
                    $_newdata[$n] = $v;
                    break;
                case "_gt":
                    $this->operators[$n] = ">";
                    $_newdata[$n] = $v;
                    break;

                case "_lt":
                    $this->operators[$n] = "<";
                    $_newdata[$n] = $v;
                    break;
                case "_gt":
                    $this->operators[$n] = ">=";
                    $_newdata[$n] = $v;
                    break;
                case "_gt":
                    $this->operators[$n] = "<=";
                    $_newdata[$n] = $v;
                    break;
                case "_in":
                    $this->operators[$n] = "in";
                    if (!is_array($v)) {
                        $_newdata[$n] = [$v];
                    } else {
                        $_newdata[$n] = $v;
                    }
                    break;
                default:
                    $this->operators[$n] = "=";
                    $_newdata[$n] = $v;
            }
        }
        return $_newdata;
    }


    private function filterKeys()
    {
        $arr1 = array_keys($this->rules());
        $arr2 = $this->all();
        $arr3 = [];
        $arr4 = [];
        foreach ($arr1 as $field) {
            if (isset($arr2[$field])) {
                $arr3[$field] = ["=", $arr2[$field]];
                $arr4[$field] = $arr2[$field];
                continue;
            }
            // Create a regex to match keys in $arr2 with patterns
            $patterns = ["_like", "_ilike", "_eq", "_gte", "_gt", "_neq", "_lt", "_lte", "_in"]; // Define the patterns you're looking for
            foreach ($patterns as $pattern) {
                $key = $field . $pattern; // Concatenate field and pattern
                if (isset($arr2[$key])) {
                    // Add the matching key and value to $arr3
                    $arr3[$field] = [$pattern, $arr2[$key]];
                    $arr4[$field] = $arr2[$key];
                }
            }
        }
        $valid = Validator::make($arr4, $this->rules());
        $validate = $valid->validated();

        // $validate = $arr4;
        return array_intersect_key($arr3, array_flip(array_keys($validate)));
    }

    public function where()
    {
        return [];
    }
}
