<?php

namespace Kouloughli\UserActivity\Http\Controllers\Api;

use Auth;
use Carbon\Carbon;
use Kouloughli\Http\Controllers\Api\ApiController;
use Kouloughli\UserActivity\Repositories\Activity\ActivityRepository;

/**
 * Class ActivityController
 * @package Kouloughli\Http\Controllers\Api\Users
 */
class StatsController extends ApiController
{
    /**
     * @var ActivityRepository
     */
    private $activities;

    public function __construct(ActivityRepository $activities)
    {
        $this->middleware('auth');

        $this->activities = $activities;
    }

    /**
     * Get activities for specified user.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        return $this->activities->userActivityForPeriod(
            Auth::user()->id,
            Carbon::now()->subWeeks(2),
            Carbon::now()
        );
    }
}
