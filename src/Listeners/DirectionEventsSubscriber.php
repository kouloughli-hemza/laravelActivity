<?php

namespace Kouloughli\UserActivity\Listeners;

use Kouloughli\Events\Direction\Created;
use Kouloughli\Events\Direction\Deleted;
use Kouloughli\Events\Direction\Updated;
use Kouloughli\UserActivity\Logger;

class DirectionEventsSubscriber
{
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function onCreate(Created $event)
    {
        $message = trans(
            'user-activity::log.new_direction',
            ['name' => $event->getDirection()->direc_name]
        );

        $this->logger->log($message);
    }

    public function onUpdate(Updated $event)
    {
        $message = trans(
            'user-activity::log.updated_direction',
            ['name' => $event->getDirection()->direc_name]
        );

        $this->logger->log($message);
    }

    public function onDelete(Deleted $event)
    {
        $message = trans(
            'user-activity::log.deleted_direction',
            ['name' => $event->getDirection()->direc_name]
        );

        $this->logger->log($message);
    }



    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $class = self::class;

        $events->listen(Created::class, "{$class}@onCreate");
        $events->listen(Updated::class, "{$class}@onUpdate");
        $events->listen(Deleted::class, "{$class}@onDelete");
    }
}
