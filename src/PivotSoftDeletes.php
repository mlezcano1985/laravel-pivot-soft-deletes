<?php

namespace Mlezcano1985\Database\Support;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait PivotSoftDeletes
{
    /**
     * SoftDelete for BelongsToMany relation.
     *
     * @param  $related BelongsToMany
     * @param $id int
     * @return int
     */
    public function detachBelongsToManySoftDelete(BelongsToMany $related, $id)
    {
        $relateKey =  $related->getQualifiedRelatedKeyName();
        $foreignKey =  $related->getQualifiedForeignKeyName();
        $tableName = $related->getTable();
        $deleteAt = $related->getTable().'.deleted_at';

        return DB::table($tableName)
            ->where($relateKey, $id)
            ->where($foreignKey, $this->id)
            ->update([
                $deleteAt => Carbon::now()
            ]);
    }

    /**
     * SoftDeletes pivot model if not use a custom pivot model
     * @param $related Pivot
     * @return bool|null
     * */
    public function detachPivotSoftDelete(Pivot $related)
    {
        $relateKey =  $related->getRelatedKey();
        $foreignKey =  $related->getForeignKey();
        $tableName = $related->getTable();
        $deleteAt = $related->getTable().'.deleted_at';
        $relatedId = $related->attributes[$relateKey];
        $foreignId = $related->attributes[$foreignKey];

        return DB::table($tableName)
            ->where($relateKey, $relatedId)
            ->where($foreignKey, $foreignId)
            ->update([
                $deleteAt => Carbon::now()
            ]);
    }

    /**
     * SoftDeletes pivot model.
     * @return bool|null
     * */
    public function detach()
    {
        $related = $this->getRelation('pivot');
        if($this->useSoftDeletes($related))
        {
            return $related->delete();
        }
        return $this->detachPivotSoftDelete($related);
    }

    /**
     * Determine if the current model implements soft deletes.
     * @param $related Pivot
     * @return bool
     */
    protected function useSoftDeletes(Pivot $related = null)
    {
        $instance = $this;
        if($related)
        {
            $instance = $related;
        }
        return method_exists($instance, 'runSoftDelete');
    }
}
