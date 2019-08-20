<?php

class DokLevel3Controller extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    private $logfile = LOG_DIR . "doklevel3log.txt";
    private $action_by;

    public function beforeAction($action) {
        parent::beforeAction($action);
        $user_name = Yii::app()->session['loginSession']['userName'];
        $this->action_by = empty($user_name) ? '' : $user_name;
        return true;
    }

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
                'actions' => array('index', 'view', 'getLoginStatus', 'create', 'delete', 'update'),
                'users' => array('*'),
            ),
            /* array('allow', // allow authenticated user to perform 'create' and 'update' actions
              'actions' => array('create', 'update'),
              'users' => array('@'),
              ), */
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                //'actions' => array('admin', 'delete'),
                'actions' => array('admin'),
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
    public function actionView($dept_id) {
        if (isset($_GET['grid'])) {
            //$dept_id berasal dari dokLevel3/view.php
            echo $this->search($dept_id);
        } else {
            //$dept_id berasal dari dashboard
            $this->renderPartial('view', array('dept_id' => $dept_id));
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        header("Content-Type: application/json");

        if (!Yii::app()->request->isPostRequest)
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');

        $flagError = 0;
        $errorMsg = '';

        try {
            $no_dokumen = $_POST['no_dokumen'];
            $judul_dokumen = $_POST['judul_dokumen'];
            $jenis_dokumen = $_POST['jenis_dokumen'];
            $dept_id = $_POST['dept_id'];

            if (isset($_FILES['attachment'])) {
                $file_name = $_FILES['attachment']['name'];
                $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                
                $allowed_ext = array('pdf', 'doc', 'docx', 'xls', 'xlsx');

                if (in_array($file_ext, $allowed_ext) && $jenis_dokumen == 'Formulir') {
                    /*                     * ** STEPS ****
                     * 1. Upload File
                     * 2. Save data
                     * ************* */
                    $targetDir = Yii::getPathOfAlias('webroot') . "/documents/level3/$dept_id/Formulir/";
                    $targetFile = $targetDir . $file_name;

                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    //var_dump($targetFile);
                    if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetFile)) {
                        //-- Save to DB
                        if (!$this->saveData($no_dokumen, $judul_dokumen, $dept_id, $jenis_dokumen, $file_ext, 'create')) {
                            $flagError++;
                            $errorMsg = 'Sorry, the system could not save the data';
                        }
                    } else {
                        $flagError++;
                        $errorMsg = 'Sorry, the system could not move uploaded file';
                    }
                } elseif ($file_ext == 'zip' && $jenis_dokumen != 'Formulir') {    //zip file
                    /*                     * ** STEPS ****
                     * 1. Upload the file zip
                     * 2. Extract
                     * 3. Save Data
                     * ************* */
                    $uploadDir = Yii::getPathOfAlias('webroot') . "/documents/upload/";
                    $uploadFile = $uploadDir . $file_name;
                    $targetExtractDir = Yii::getPathOfAlias('webroot') . "/documents/level3/$dept_id/$jenis_dokumen";
                    if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadFile)) {
                        if (!is_dir($targetExtractDir)) {
                            mkdir($targetExtractDir, 0777, true);
                        }
                        //-- Extract the zip file
                        $zip = new ZipArchive();
                        $x = $zip->open($uploadFile);
                        if ($x === true) {
                            $zip->extractTo($targetExtractDir); // change this to the correct site path
                            $zip->close();
                            unlink($uploadFile);
                            //--Save to DB
                            if (!$this->saveData($no_dokumen, $judul_dokumen, $dept_id, $jenis_dokumen, 'zip', 'create')) {
                                $flagError++;
                                $errorMsg = 'Sorry, the system could not save the data';
                            }
                        } else {
                            $flagError++;
                            $errorMsg = 'Sorry, file cannot be extracted';
                        }
                    } else {
                        $flagError++;
                        //$errorMsg = 'Sorry, the system could not move uploaded file';
                        $errorMsg = "Not uploaded because of error #" . $_FILES["attachment"]["error"];
                    }
                } else {
                    $flagError++;
                    $errorMsg = 'Type file salah!! PDF, Word, Excel utk Formulir. ZIP untuk lainnya';
                }
            } else {
                $flagError++;
                $errorMsg = 'Error : no file uploaded';
            }

            if ($flagError == 0) {
                echo CJSON::encode(array('success' => true, 'msg' => 'You have successfully upload protap'));
            } else {
                echo CJSON::encode(array('msg' => 'Error occurred during processing. Message : ' . $errorMsg));
            }
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
        $rows = json_decode(stripslashes($_POST['row']));

        //var_dump($_POST);
        try {
            foreach ($rows as $row) {
                if (!$this->saveData($row->NoDokumen, $row->judul_dok, $row->dept_id, $row->JenisDokumen, 'update')) {
                    $errorMsg = 'Sorry, the system could not save the data';
                }
            }
            echo CJSON::encode(array('success' => true, 'msg' => 'You have successfully update the document'));
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
            $no_dok = $row['NoDokumen'];
            $dept = $row['dept_id'];
            $jenis_dok = $row['JenisDokumen'];
            $file_ext = $row['file_ext'];

            if ($jenis_dok == 'Formulir') {
                $targetDir = Yii::getPathOfAlias('webroot') . "/documents/level3/$dept/$jenis_dok/$no_dok.$file_ext";
                unlink($targetDir);
            } else {
                $targetDir = Yii::getPathOfAlias('webroot') . "/documents/level3/$dept/$jenis_dok/$no_dok";
                Yii::app()->gc->delTree($targetDir);
            }

            Yii::app()->db->createCommand("{CALL QA_DeleteDokLevel3(:NoDokumen)}")
                    ->bindParam(':NoDokumen', $no_dok, PDO::PARAM_STR)
                    ->execute();

            //-- Save into log file
            $content = "Delete Doc : $no_dok\r\n";
            $content .= "Date : " . date('d-M-Y') . "\r\n";
            $content .= "Deleted By : $this->action_by\r\n";
            $content .= "\r\n";
            file_put_contents($this->logfile, $content, FILE_APPEND | LOCK_EX);

            echo CJSON::encode(array('success' => true, 'msg' => 'You have successfully delete the document'));
        } catch (Exception $ex) {

            echo CJSON::encode(array('msg' => 'Error occurred : ' . $ex->getMessage()));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        if (isset($_GET['grid'])) {
            if (isset($_GET['summary']))
                echo $this->searchSummary();
            else
                echo $this->search('');
        } else {
            $this->renderPartial('index', array());
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new DokLevel3('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['DokLevel3']))
            $model->attributes = $_GET['DokLevel3'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return DokLevel3 the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = DokLevel3::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param DokLevel3 $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'dok-level3-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function search($dept) {
        header("Content-Type: application/json");

        // search 
        //$no_dok = isset($_POST['id']) ? $_POST['id'] : '';
        $jenis_dok = isset($_POST['jenis_dok']) ? $_POST['jenis_dok'] : '';
        $judul_dok = isset($_POST['judul_dok']) ? $_POST['judul_dok'] : '';

        if (empty($dept)) {
            $dept_id = isset($_POST['dept_id']) ? $_POST['dept_id'] : '';
        } else {
            $dept_id = $dept;
        }

        // pagging
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;

        $criteria = new CDbCriteria;

        if (!empty($dept_id)) {
            $criteria->addCondition('DeptID=:deptID');
            $criteria->params[':deptID'] = $dept_id;
        }

        if (!empty($jenis_dok)) {
            $criteria->addCondition('JenisDokumen=:jenisDok');
            $criteria->params[':jenisDok'] = $jenis_dok;
        }

        if (!empty($judul_dok)) {
            $criteria->addSearchCondition('JudulDokumen', $judul_dok);
        }

        $result = array();
        $row = array();

        $result['total'] = count(V_DokLevel3_Dept::model()->findAll($criteria));

        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'NoDokumen';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
        $offset = ($page - 1) * $rows;

        $criteria->offset = $offset;
        $criteria->limit = $rows;
        $criteria->order = $sort . ' ' . $order;

        foreach (V_DokLevel3_Dept::model()->findAll($criteria) as $data) {
            if( is_null($data->FileExt) ) {
                if( $data->JenisDokumen == 'Formulir' ) 
                    $file_ext = 'pdf';
                else 
                    $file_ext = 'zip';
            } else {
                $file_ext = $data->FileExt;
            }
            
            $row[] = array(
                'NoDokumen' => $data->NoDokumen,
                'judul_dok' => $data->JudulDokumen,
                'JenisDokumen'  => $data->JenisDokumen,
                'Department'    => $data->Department,
                'dept_id'   => $data->DeptID,
                'file_ext'  => $file_ext,
                'tgl_upload' => date('d-M-Y', strtotime($data->TglUpload)),
                'upload_by' => $data->UploadBy,
            );
        }

        $result = array_merge($result, array('rows' => $row));
        return CJSON::encode($result);
    }

    function searchSummary() {
        header("Content-Type: application/json");
        // pagging
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;

        $result = array();
        $row = array();

        $start_row = 1 + ($page - 1) * $rows;
        $end_row = $page * $rows;

        $data = Yii::app()->db->createCommand("{CALL QA_SumDokLevel3}")->queryAll();
        $result['total'] = count($data);

        foreach ($data as $item) {
            $row[] = array(
                'jml_dok' => $item['jml_dok'],
                'department' => $item['Department'],
                'dept_id' => $item['DeptID']
            );
        }

        //var_dump($data);
        $result = array_merge($result, array('rows' => $row));
        return CJSON::encode($result);
    }

    function saveData($no_dokumen, $judul_dokumen, $dept_id, $jenis_dokumen, $file_ext, $action_mode) {

        try {
            Yii::app()->db->createCommand("{CALL QA_SaveDokLevel3(:NoDokumen, :JudulDokumen, :DeptID, :JenisDokumen, :FileExt, :UploadBy)}")
                    ->bindParam(':NoDokumen', $no_dokumen, PDO::PARAM_STR)
                    ->bindParam(':JudulDokumen', $judul_dokumen, PDO::PARAM_STR)
                    ->bindParam(':DeptID', $dept_id, PDO::PARAM_STR)
                    ->bindParam(':FileExt', $file_ext, PDO::PARAM_STR)
                    ->bindParam(':JenisDokumen', $jenis_dokumen, PDO::PARAM_STR)
                    ->bindParam(':UploadBy', $this->action_by, PDO::PARAM_STR)
                    ->execute();

            //-- Save into log file
            $content = "";

            if ($action_mode === 'create') {
                $content = "Uploading File : $no_dokumen\r\n";
                $content .= "Upload By : $this->action_by\r\n";
                $content .= "Upload Date : " . date("d-M-Y") . "\r\n";
            } else {
                $content = "Modified Doc : $no_dokumen\r\n";
                $content .= "Updated By : $this->action_by\r\n";
                $content .= "Modified Date : " . date("d-M-Y") . "\r\n";
            }

            $content .= "Judul Dokumen : $judul_dokumen\r\n";
            $content .= "Dept ID : $dept_id\r\n";
            $content .= "Jenis Dokumen : $jenis_dokumen\r\n";
            $content .= "\r\n";

            file_put_contents($this->logfile, $content, FILE_APPEND | LOCK_EX);

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

}
