<?php

namespace Kouloughli\UserActivity\Repositories\Activity;

use Kouloughli\UserActivity\Activity;
use Carbon\Carbon;
use DB;

class EloquentActivity implements ActivityRepository
{
    /**
     * {@inheritdoc}
     */
    public function log($data)
    {
        return Activity::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function paginateActivitiesForUser($userId, $perPage = 20, $search = null)
    {
        $query = Activity::where('ref_user', $userId);

        return $this->paginateAndFilterResults($perPage, $search, $query);
    }

    /**
     * {@inheritdoc}
     */
    public function getLatestActivitiesForUser($userId, $activitiesCount = 10)
    {
        return Activity::where('ref_user', $userId)
            ->orderBy('activ_date', 'DESC')
            ->limit($activitiesCount)
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function paginateActivities($perPage = 20, $search = null)
    {
        $query = Activity::with('user');

        return $this->paginateAndFilterResults($perPage, $search, $query);
    }

    /**
     * @param $perPage
     * @param $search
     * @param $query
     * @return mixed
     */
    private function paginateAndFilterResults($perPage, $search, $query)
    {
        if ($search) {
            $query->where('activ_desc', 'LIKE', "%$search%");
        }

        $result = $query->orderBy('activ_date', 'DESC')
            ->paginate($perPage);

        if ($search) {
            $result->appends(['search' => $search]);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function userActivityForPeriod($userId, Carbon $from, Carbon $to)
    {
        $result = Activity::select([
            DB::raw("DATE(activ_date) as day"),
            DB::raw('count(id) as count')
        ])
            ->where('ref_user', $userId)
            ->whereBetween('activ_date', [$from, $to])
            ->groupBy('day')
            ->orderBy('day', 'ASC')
            ->pluck('count', 'day');

        while (! $from->isSameDay($to)) {
            if (! $result->has($from->toDateString())) {
                $result->put($from->toDateString(), 0);
            }
            $from->addDay();
        }

        return $result->sortBy(function ($value, $key) {
            return strtotime($key);
        });
    }
}
