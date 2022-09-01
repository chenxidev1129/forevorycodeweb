<?php

namespace App\Repositories;

use App\Models\ProdigiOrderHistory;

class ProdigiOrderHistoryRepository{

    /**
     * Find one
     * @param array $where
     * @return  ProdigiOrderHistory
     */
    public static function findOne($where)
    {
        return ProdigiOrderHistory::where($where)->first();
    }

    /**
     * Find one order by
     * @param array $where
     * @return  ProdigiOrderHistory
     */
    public static function findOneOrderBy($where, $orderBy)
    {
        return ProdigiOrderHistory::where($where)->orderBy('id', $orderBy)->first();
    }

}
