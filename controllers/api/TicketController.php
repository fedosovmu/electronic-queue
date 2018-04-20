<?php

namespace app\controllers\api;

use app\models\Token;
use yii\base\Controller;
use yii\rest\ActiveController;
use Yii;
use app\models\Ticket;

class TicketController extends Controller
{
    public function actionGet()
    {
        if (Token::checkToken(Yii::$app->request->headers['Token']))
        {
            echo Ticket::createTicket()->id;
        }
        else {
            echo "Wrong token";
        }
    }
}