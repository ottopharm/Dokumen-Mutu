<?php

class SiteController extends Controller {

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

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'      
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
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
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
                $this->redirect(Yii::app()->user->returnUrl);
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
        $this->redirect(array('/site/login'));
    }

    public function actionNotifications() {
        $criteria = new CDbCriteria;
        $criteria->condition = 'approval_status = :status AND apv_user_id = :approver';
        $criteria->params = array(
            ':status' => 0,
            ':approver' => Yii::app()->session['loginSession']['userId']
        );
        $data = array(
            'approval' => TrxApproval::model()->findAll($criteria)
        );
        $this->renderPartial('notifications', $data);
    }

    public function actionApprovalFormOld($id, $mode = null) {
        $model = TrxApproval::model()->findByPk($id);
        if ($model->is_read == 0) {
            $model->is_read = 1;
            $model->modified_date = date('Y-m-d H:i:s');
            $model->modified_by = Yii::app()->session['loginSession']['userId'];
            $model->save();
        }
        switch ($model->doc_type) {
            case 'PR':
                $this->redirect(array('/TrxPr/approvalForm' . $mode,
                    'prid' => $model->doc_id,
                    'apvid' => $id));
                break;
            case 'PRPO':
                $this->redirect(array('/TrxPrpo/approvalForm' . $mode,
                    'id' => $model->doc_id,
                    'apvid' => $id));
                break;
        }
    }

    public function actionApprovalForm($id, $mode = null) {
        $model = TrxApproval::model()->findByPk($id);
        if ($model->is_read == 0) {
            $model->is_read = 1;
            $model->modified_date = date('Y-m-d H:i:s');
            $model->modified_by = Yii::app()->session['loginSession']['userId'];
            $model->save();
        }
        switch ($model->doc_type) {
            case 'PR':
                $this->redirect(array('/TrxPr/approvalForm' . $mode,
                    'prid' => $model->doc_id,
                    'apvid' => $id));
                break;
            case 'PRPO':
                $this->redirect(array('/TrxPrpo/approvalForm' . $mode,
                    'id' => $model->doc_id,
                    'apvid' => $id));
                break;
            case 'PR1':
                $this->redirect(array('/PurchaseRequisition/approvalForm' . $mode,
                    'doc_id' => $model->doc_id,
                    'approval_id' => $id));
                break;
            case 'PRPO1':
                $this->redirect(array('/PurchaseRequisition/approvalPRPOForm' . $mode,
                    'doc_id' => $model->doc_id,
                    'approval_id' => $id));
                break;
        }
    }

    public function actionMyDocRequest($doc_id, $doc_type) {
        switch ($doc_type) {
            case 'PR':
                $this->redirect(array('TrxPr/docHistory', 'id' => $doc_id));
                break;
            case 'PRPO':
                $this->redirect(array('TrxPrpo/docHistory', 'id' => $doc_id));
                break;
            case 'PR1':
                $this->redirect(array('PurchaseRequisition/docHistoryPR', 'id' => $doc_id));
                break;
            case 'PRPO1':
                $this->redirect(array('PurchaseRequisition/docHistoryPRPO', 'id' => $doc_id));
                break;
        }
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
        $user = MstUser::model()->findByPk($user_id);
        if (isset($_POST['old_password']) && !empty($_POST['old_password'])) {
            if (md5($_POST['old_password']) != $user->user_password) {
                $flagError++;
                $msg = 'Old Password did not match with existing data.';
            } else {
                $user->user_password = md5($_POST['new_password']);
                $user->modified_date = date('Y-m-d H:i:s');
                $user->modified_by = $user_id;
                if (!$user->save()) {
                    $flagError++;
                    $msg = 'Failed to change password';
                }
            }
        } else {
            $flagError++;
            $msg = 'Old password is required';
        }
        if ($flagError == 0)
            echo CJSON::encode(array('success' => true, 'msg' => 'Password successfully changed.'));
        else
            echo CJSON::encode(array('msg' => $msg));
    }

    public function actionDisplayDokLvl1($jenisdok, $nodok) {
        $this->renderPartial('display_dok_lvl1', array(
            'jenisdok' => $jenisdok,
            'nodok' => $nodok));
    }

    public function actionDisplayDokLvl2($dept, $folder) {
        $this->renderPartial('display_dok_lvl2', array(
            'dept' => $dept,
            'folder' => $folder));
    }

    public function actionDisplayDokLvl3($dept, $jenisdok, $folder, $ext) {
        //$ext = is_null($file_ext) ? 'zip' : $file_ext;
        $this->renderPartial('display_dok_lvl3', array('dept' => $dept,
            'jenisdok' => $jenisdok,
            'filename' => $folder,
            'ext'      => $ext));
    }

    public function actionAdd($view) {
        $this->renderPartial("//$view/add", array());
    }
    
    public function actionAuthorization() {
        echo CJSON::encode(array('valid' => true));
    }

}
