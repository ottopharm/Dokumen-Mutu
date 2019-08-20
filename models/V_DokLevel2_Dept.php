<?php

/**
 * This is the model class for table "v_QA_DokLevel2_Dept".
 *
 * The followings are the available columns in table 'v_QA_DokLevel2_Dept':
 * @property string $NoDokumen
 * @property string $JudulDokumen
 * @property string $DeptID
 * @property string $TglUpload
 * @property string $UploadBy
 * @property string $Department
 */
class V_DokLevel2_Dept extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'v_QA_DokLevel2_Dept';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('NoDokumen, JudulDokumen, DeptID, TglUpload', 'required'),
			array('NoDokumen, Department', 'length', 'max'=>50),
			array('JudulDokumen, UploadBy', 'length', 'max'=>100),
			array('DeptID', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('NoDokumen, JudulDokumen, DeptID, TglUpload, UploadBy, Department', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'NoDokumen' => 'No Dokumen',
			'JudulDokumen' => 'Judul Dokumen',
			'DeptID' => 'Dept',
			'TglUpload' => 'Tgl Upload',
			'UploadBy' => 'Upload By',
			'Department' => 'Department',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('NoDokumen',$this->NoDokumen,true);
		$criteria->compare('JudulDokumen',$this->JudulDokumen,true);
		$criteria->compare('DeptID',$this->DeptID,true);
		$criteria->compare('TglUpload',$this->TglUpload,true);
		$criteria->compare('UploadBy',$this->UploadBy,true);
		$criteria->compare('Department',$this->Department,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return V_DokLevel2_Dept the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
