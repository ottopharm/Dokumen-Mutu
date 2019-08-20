<?php

class AdminController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function beforeAction($action) {
        parent::beforeAction($action);
        $loginSession = Yii::app()->session['loginSession'];
        if (!isset($loginSession) && empty($loginSession) && !in_array($action->id, array('login', 'logout'))) {
            $this->redirect(array('/admin/login'));
        } else {
            if (Yii::app()->user->authTimeout > time()) {
                Yii::app()->user->logout();
            } else {
                Yii::app()->user->setState('authTimeout', time() + Yii::app()->params['sessionTimeoutSeconds']);
                return true;
            }
            return true;
        }
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'   
        $this->layout = '//layouts/admin';
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {

        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->homeUrl);
        }
        // display the login form
        $this->renderPartial('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        // $this->redirect(Yii::app()->homeUrl);
        Yii::app()->session->destroy();
        $this->redirect(array('/admin/login'));
    }

    public function actionChangePassword() {
        $this->renderPartial('change_password');
    }

    public function actionSaveNewPassword() {
        header("Content-Type: application/json");

        if (!Yii::app()->request->isPostRequest)
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        $flagError = 0;
        $msg = '';
        $user_id = Yii::app()->session['loginSession']['userId'];
        $user = User::model()->findByPk($user_id);

        try {
            if (md5($_POST['old_password']) != $user->Password) {
                $flagError++;
                $msg = 'Old Password did not match with existing data.';
            } else {
                $command = Yii::app()->db->createCommand();
                $command->update('QA_DokMutuUsers', array(
                        'Password' => md5($_POST['new_password']),), 'ID=:id', array(':id' => $user_id));
                /*$user->Password = md5($_POST['new_password']);
                if (!$user->save()) {
                    $flagError++;
                    $msg = 'Failed to change password';
                }*/
                //$user->save();
            }

            if ($flagError == 0)
                echo CJSON::encode(array('success' => true, 'msg' => 'Password successfully changed.'));
            else
                echo CJSON::encode(array('msg' => $msg));
        } catch (Exception $ex) {
            echo CJSON::encode(array('msg' => 'Error occurred : ' . $ex->getMessage()));
        }
    }

}
