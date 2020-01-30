<?php

namespace Kouloughli\UserActivity\Http\Controllers\Web;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Kouloughli\UserActivity\Repositories\Activity\ActivityRepository;
use Kouloughli\Http\Controllers\Controller;

/**
 * Class ActivityController
 * @package Kouloughli\Http\Controllers
 */
class ActivityController extends Controller
{
    /**
     * @var ActivityRepository
     */
    private $activities;

    /**
     * ActivityController constructor.
     * @param ActivityRepository $activities
     */
    public function __construct(ActivityRepository $activities)
    {
        $this->activities = $activities;
    }

    /**
     * Displays the page with activities for all system users.
     *
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $activities = $this->activities->paginateActivities($perPage = 20, $request->search);

        return view('user-activity::index', [
            'adminView' => true,
            'activities' => $activities
        ]);
    }
}
