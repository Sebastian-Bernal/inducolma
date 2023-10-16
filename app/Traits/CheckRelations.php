<?php

namespace App\Traits;

trait CheckRelations
{
    public function hasAnyRelatedData(array $relations)
    {
        foreach ($relations as $relation) {
            if ($this->{$relation}()->exists()) {
                return true;
            }
        }

        return false;
    }
}
