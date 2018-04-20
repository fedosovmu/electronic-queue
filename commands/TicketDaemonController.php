<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.03.18
 * Time: 11:46
 */
namespace app\commands;

use app\models\Ticket;
use vyants\daemon\DaemonController;
use Yii;

class TicketDaemonController extends DaemonController
{
    /**
     * Daemon worker body
     *
     * @param $job
     * @return boolean
     */
    protected function doJob($job)
    {
        //Ticket::distributeTickets();
        // TODO: Implement doJob() method.
    }

    /**
     * Extract current unprocessed jobs
     * You can extract jobs from DB (DataProvider will be great), queue managers (ZMQ, RabbiMQ etc), redis and so on
     *
     * @return array with jobs
     */
    protected function defineJobs()
    {
        Ticket::distributeTickets();
        $localsocket = 'tcp://127.0.0.1:1234';
        $message = json_encode(Ticket::workingTicketsWindow());
        $instance = stream_socket_client($localsocket);
        fwrite($instance, $message);
        return [];
        // TODO: Implement defineJobs() method.
    }
}