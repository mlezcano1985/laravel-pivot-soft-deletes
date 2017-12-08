<?php
/**
 * Created by PhpStorm.
 * User: itopia
 * Date: 12/8/2017
 * Time: 1:46 PM
 */

namespace App\Lib;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

trait PivotSoftDeletes
{
    /**
     * SoftDelete for Pivot table.
     *
     * @param  $related BelongsToMany
     * @param $id int
     * @return int
     */
    public function detachSoftDelete(BelongsToMany $related, $id)
    {
        $relateKey =  $related->getQualifiedRelatedKeyName();
        $foreignKey =  $related->getQualifiedForeignKeyName();
        $tableName = $related->getTable();
        $deteteAt = $related->getTable().'.deleted_at';

        return DB::table($tableName)
            ->where($relateKey, $id)
            ->where($foreignKey, $this->id)
            ->update([
                $deteteAt => Carbon::now()
            ]);
    }

    /**
     * Delete the model from the database.
     * @return bool|null
     * */
    public function detach()
    {
        return $this->getRelation('pivot')->delete();
    }
}