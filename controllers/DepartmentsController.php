<?php

class DepartmentsController extends Controller {

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
                //'actions' => array('index', 'view', 'add'),
                'actions' => array('index', 'view', 'deptList'),
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

        try {
            $dept_id = $_POST['dept_id'];
            $department = $_POST['department'];

            //Save to DB
            $this->saveData($dept_id, $department);
            
            echo CJSON::encode(array('success' => true, 'msg' => 'You have successfully save department'));
        } catch (Exception $ex) {
            echo CJSON::encode(array('msg' => 'Error occurred : ' . $ex->getMessage()));
        }
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
                $this->saveData($data->dept_id, $data->department);
            }
            echo CJSON::encode(array('success' => true, 'msg' => 'You have successfully save department'));
        } catch (Exception $ex) {
            echo CJSON::encode(array('msg' => 'Error occurred : ' . $ex->getMessage()));
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
        $model = new Departments('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Departments']))
            $model->attributes = $_GET['Departments'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Departments the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Departments::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Departments $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'departments-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Custom Functions
     */
    public function search() {
        header("Content-Type: application/json");

        // search 
        $dept_id = isset($_POST['dept_id']) ? $_POST['dept_id'] : '';
        $department = isset($_POST['department']) ? $_POST['department'] : '';

        // pagging
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'DeptID';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
        $offset = ($page - 1) * $rows;

        $result = array();
        $row = array();

        // result
        $criteria = new CDbCriteria;

        $criteria->addSearchCondition('LOWER(DeptID)', strtolower($dept_id));
        $criteria->addSearchCondition('LOWER(Department)', strtolower($department));

        $result['total'] = count(Departments::model()->findAll($criteria));
        $criteria->offset = $offset;
        $criteria->limit = $rows;
        $criteria->order = $sort . ' ' . $order;

        foreach (Departments::model()->findAll($criteria) as $data) {
            $row[] = array(
                'dept_id' => $data->DeptID,
                'department' => $data->Department,
            );
        }
        $result = array_merge($result, array('rows' => $row));
        return CJSON::encode($result);
    }

    function saveData($dept_id, $department) {
        Yii::app()->db->createCommand("{CALL Shared_SaveDepartment(:DeptID, :Department)}")
                ->bindParam(':DeptID', $dept_id, PDO::PARAM_STR)
                ->bindParam(':Department', $department, PDO::PARAM_STR)
                ->execute();
    }
    
    /**
     * Custom functions
     */
    public function actionDeptList() {
        header("Content-Type: application/json");
        $json = array();

        foreach (Departments::model()->findAll(array('order' => 'Department')) as $data) {
            array_push($json, array(
                'dept_id' => $data['DeptID'],
                'department' => $data['Department']
            ));
        }
        echo CJSON::encode($json);
    }

}
