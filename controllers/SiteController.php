<?php

namespace app\controllers;

use app\additional\Daemon;
use app\models\Ticket;
use app\models\Window;
use app\models\WindowOperator;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'operator', 'enter-window', 'exit-window', 'complete-ticket'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['operator', 'enter-window', 'exit-window', 'complete-ticket'],
                        'allow' => true,
                        'roles' => ['operator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        if($model->load(Yii::$app->request->post()) && $model->login()){
            return $this->redirect(['/']);
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionOperator()
    {
        return $this->render('operator');
    }

    public function actionTicket()
    {
        echo Ticket::createTicket()->id;
    }

    public function actionEnterWindow()
    {
        WindowOperator::createWindowOperator(Yii::$app->request->post('window'),
            Yii::$app->user->identity->id);
        return $this->redirect(['/site/operator']);
    }

    public function actionExitWindow()
    {
        Ticket::completeTickerByOperId(Yii::$app->user->getId());
        WindowOperator::exitWindowByOperId(Yii::$app->user->getId());
        $localsocket = 'tcp://127.0.0.1:1234';
        $message = json_encode(Ticket::workingTicketsWindow());
        $instance = stream_socket_client($localsocket);
        fwrite($instance, $message);
        return $this->redirect(['/site/operator']);
    }

    public function actionCompleteTicket()
    {
        Ticket::completeTickerByOperId(Yii::$app->user->getId());
        $localsocket = 'tcp://127.0.0.1:1234';
        $message = json_encode(Ticket::workingTicketsWindow());
        $instance = stream_socket_client($localsocket);
        fwrite($instance, $message);
        return $this->redirect(['/site/operator']);
    }
}
