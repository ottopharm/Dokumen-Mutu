<?php

class DokLevel1Controller extends Controller {

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
                'actions' => array('create', 'update', 'delete'),
                'users' => array('@'),
            ),
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
            $jenis_dokumen = $_POST['jenis_dokumen'];
            $user_name = Yii::app()->session['loginSession']['userName']; 
            $upload_by = empty($user_name) ? '' : $user_name;

            if (isset($_FILES['attachment'])) {

                $uploadDir = Yii::getPathOfAlias('webroot') . "/documents/upload/";
                $uploadFile = $uploadDir . $_FILES['attachment']['name'];
                $targetExtractDir = Yii::getPathOfAlias('webroot') . "/documents/level1/$jenis_dokumen";

                if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadFile)) {
                    //Extract the file
                    if (!is_dir($targetExtractDir)) {
                        mkdir($targetExtractDir, 0777, true);
                    } else {
                        Yii::app()->gc->delTree($targetExtractDir);
                    }

                    $zip = new ZipArchive();
                    $x = $zip->open($uploadFile);

                    if ($x === true) {
                        $zip->extractTo($targetExtractDir); // change this to the correct site path
                        $zip->close();
                        unlink($uploadFile);

                        //Save to DB
                        Yii::app()->db->createCommand("{CALL QA_SaveDokLevel1(:NoDokumen, :JenisDokumen, :UploadBy)}")
                                ->bindParam(':NoDokumen', $no_dokumen, PDO::PARAM_STR)
                                ->bindParam(':JenisDokumen', $jenis_dokumen, PDO::PARAM_STR)
                                ->bindParam(':UploadBy', $upload_by, PDO::PARAM_STR)
                                ->execute();
                    } else {
                        $flagError++;
                        $errorMsg = 'Sorry, file cannot be extracted';
                    }
                } else {
                    $flagError++;
                    $errorMsg = 'Sorry, the system could not move uploaded file';
                }
            } else {
                $flagError++;
                $errorMsg = 'Error : no file zip selected';
            }

            if ($flagError == 0) {
                echo CJSON::encode(array('success' => true, 'msg' => 'You have successfully upload dokumen',
                    'data' => array('jenis_dokumen' => $jenis_dokumen, 'no_dokumen' => $no_dokumen)));
            } else {
                echo CJSON::encode(array('msg' => 'Error occurred during processing. Message : ' . $errorMsg));
            }
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
            $no_dok = $row['no_dok'];
            $jenis_dok = $row['jenis_dok'];
            
            //Delete Folder
            $targetDir = Yii::getPathOfAlias('webroot') . "/documents/level1/$jenis_dok/$no_dok";
            Yii::app()->gc->delTree($targetDir);
            
            //Delete Data
            Yii::app()->db->createCommand('{CALL QA_DeleteDokLevel1(:NoDokumen)}')
                    ->bindParam(':NoDokumen', $no_dok, PDO::PARAM_STR)
                    ->execute();

            echo CJSON::encode(array('success' => true, 'msg' => 'You have successfully delete the document'));
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
        $model = new DokLevel1('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['DokLevel1']))
            $model->attributes = $_GET['DokLevel1'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return DokLevel1 the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = DokLevel1::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param DokLevel1 $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'dok-level1-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * custom functions
     */
    public function search() {
        header("Content-Type: application/json");

        // search 
        $no_dok = isset($_POST['no_dok']) ? $_POST['no_dok'] : '';
        $jenis_dok = isset($_POST['jenis_dok']) ? $_POST['jenis_dok'] : '';
        $tgl_upload = isset($_POST['tgl_upload']) ? $_POST['tgl_upload'] : '';

        $result = array();
        $row = array();
        // result
        $criteria = new CDbCriteria;

        $criteria->addSearchCondition('LOWER(NoDokumen)', strtolower($no_dok));
        $criteria->addSearchCondition('LOWER(JenisDokumen)', strtolower($jenis_dok));
        $criteria->addSearchCondition('LOWER(TglUpload)', strtolower($tgl_upload));

        //$criteria->offset = $offset;
        //$criteria->limit = $rows;
        
        $result['total'] = count(DokLevel1::model()->findAll($criteria));

        foreach (DokLevel1::model()->findAll($criteria) as $data) {
            $row[] = array(
                'no_dok' => $data->NoDokumen,
                'jenis_dok' => $data->JenisDokumen,
                'tgl_upload' => date('d-M-Y', strtotime($data->TglUpload)),
                'upload_by' => $data->UploadBy,
            );
        }
        $result = array_merge($result, array('rows' => $row));
        return CJSON::encode($result);
    }

}
