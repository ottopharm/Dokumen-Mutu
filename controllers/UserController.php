<?php

class UserController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        header("Content-Type: application/json");

        if (!Yii::app()->request->isPostRequest)
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');

        //$user = Yii::app()->session['loginSession']['userId'];
        $flagError = 0;

        $model = new User;
        $model->ID = $_POST['id'];
        $model->UserName = $_POST['user_name'];
        $model->Password = md5($_POST['password']);
        $model->Role = 'AdmQA';
        //$model->IsActive = true;
        
        if ( !$model->save()) {

            $flagError++;
            $errorMsg = 'Failed to save User data.';
        }
        
        //var_dump($model);
        
        if ($flagError == 0)
            echo CJSON::encode(array('success' => true, 'msg' => 'You have successfully added user.'));
        else
            echo CJSON::encode(array('msg' => $errorMsg));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate() {
        $data_array = json_decode(stripslashes($_POST['data']));

        try {
            foreach ($data_array as $data) {
                //Save to DB
                $this->saveData($data->id, $data->user_name);
            }
            echo CJSON::encode(array('success' => true, 'msg' => 'You have successfully changed user name'));
        } catch (Exception $ex) {
            echo CJSON::encode(array('msg' => 'Error occurred : ' . $ex->getMessage()));
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete() {
        $row = $_POST['row'];
        //var_dump($row);

        try {
            $user_id = $row['id'];
            
            Yii::app()->db->createCommand("{CALL QA_RemoveUser(:ID)}")
                    ->bindParam(':ID', $user_id, PDO::PARAM_STR)
                    ->execute();

            echo CJSON::encode(array('success' => true, 'msg' => 'You have successfully delete the user'));
        } catch (Exception $ex) {

            echo CJSON::encode(array('msg' => 'Error occurred : ' . $ex->getMessage()));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        if (isset($_GET['grid']))
            echo $this->search();
        else
            $this->renderPartial('index', array());
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return User the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    function search(){
        header("Content-Type: application/json");
        
        $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : '';
        
        $data = Yii::app()->db->createCommand("{CALL QA_SearchUser(:UserName)}")
                ->bindParam(':UserName', $user_name, PDO::PARAM_STR)
                ->queryAll();
        
        $result = array();
        $result['total'] = count($data);
        
        foreach ($data as $item) {
            $row[] = array(
                'id' => $item["ID"],
                'user_name' => $item["UserName"]
            );
        }
        
        //var_dump($row);
        
        $result = array_merge($result, array('rows' => $row));
        return CJSON::encode($result);
        
    }
    
    function saveData($dept_id, $department) {
        Yii::app()->db->createCommand("{CALL QA_UpdateUser(:ID, :UserName)}")
                ->bindParam(':ID', $dept_id, PDO::PARAM_STR)
                ->bindParam(':UserName', $department, PDO::PARAM_STR)
                ->execute();
    }

}
