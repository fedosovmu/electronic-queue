<?php

namespace app\commands;

use app\models\Ticket;

class WatcherDaemonController extends \vyants\daemon\controllers\WatcherDaemonController
{
    /**
    * @return array
    */
    protected function defineJobs()
    {
        sleep($this->sleep);
        //TODO: modify list, or get it from config, it does not matter
        $daemons = [
        ['className' => 'TicketDaemonController', 'enabled' => true],
//        ['className' => 'AnotherDaemonController', 'enabled' => false]
        ];
//        Ticket::distributeTickets();
        return $daemons;
    }

}