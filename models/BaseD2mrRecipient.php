<?php

/**
 * This is the model base class for the table "d2mr_recipient".
 *
 * Columns in table "d2mr_recipient" available as properties of the model:
 * @property string $d2mr_id
 * @property string $d2mr_d2mm_id
 * @property integer $d2mr_recipient_pprs_id
 * @property string $d2mr_recipient_role
 * @property string $d2mr_read_datetime
 * @property string $d2mr_deleted_datetime
 *
 * Relations of table "d2mr_recipient" available as properties of the model:
 * @property PprsPerson $d2mrRecipientPprs
 * @property D2mmMessages $d2mrD2mm
 */
abstract class BaseD2mrRecipient extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'd2mr_recipient';
    }

    public function rules()
    {
        return array_merge(
            parent::rules(), array(
                array('d2mr_d2mm_id', 'required'),
                array('d2mr_recipient_pprs_id, d2mr_recipient_role, d2mr_read_datetime, d2mr_deleted_datetime', 'default', 'setOnEmpty' => true, 'value' => null),
                array('d2mr_recipient_pprs_id', 'numerical', 'integerOnly' => true),
                array('d2mr_d2mm_id', 'length', 'max' => 10),
                array('d2mr_recipient_role', 'length', 'max' => 20),
                array('d2mr_read_datetime, d2mr_deleted_datetime', 'safe'),
                array('d2mr_id, d2mr_d2mm_id, d2mr_recipient_pprs_id, d2mr_recipient_role, d2mr_read_datetime, d2mr_deleted_datetime', 'safe', 'on' => 'search'),
            )
        );
    }

    public function getItemLabel()
    {
        return (string) $this->d2mr_d2mm_id;
    }

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(), array(
                'savedRelated' => array(
                    'class' => '\GtcSaveRelationsBehavior'
                )
            )
        );
    }

    public function relations()
    {
        return array_merge(
            parent::relations(), array(
                'd2mrRecipientPprs' => array(self::BELONGS_TO, 'PprsPerson', 'd2mr_recipient_pprs_id'),
                'd2mrD2mm' => array(self::BELONGS_TO, 'D2mmMessages', 'd2mr_d2mm_id'),
            )
        );
    }

    public function attributeLabels()
    {
        return array(
            'd2mr_id' => Yii::t('D2messagesModule.model', 'D2mr'),
            'd2mr_d2mm_id' => Yii::t('D2messagesModule.model', 'D2mr D2mm'),
            'd2mr_recipient_pprs_id' => Yii::t('D2messagesModule.model', 'D2mr Recipient Pprs'),
            'd2mr_recipient_role' => Yii::t('D2messagesModule.model', 'D2mr Recipient Role'),
            'd2mr_read_datetime' => Yii::t('D2messagesModule.model', 'D2mr Read Datetime'),
            'd2mr_deleted_datetime' => Yii::t('D2messagesModule.model', 'D2mr Deleted Datetime'),
        );
    }

    public function searchCriteria($criteria = null)
    {
        if (is_null($criteria)) {
            $criteria = new CDbCriteria;
        }

        $criteria->compare('t.d2mr_id', $this->d2mr_id, true);
        $criteria->compare('t.d2mr_d2mm_id', $this->d2mr_d2mm_id);
        $criteria->compare('t.d2mr_recipient_pprs_id', $this->d2mr_recipient_pprs_id);
        $criteria->compare('t.d2mr_recipient_role', $this->d2mr_recipient_role, true);
        $criteria->compare('t.d2mr_read_datetime', $this->d2mr_read_datetime, true);
        $criteria->compare('t.d2mr_deleted_datetime', $this->d2mr_deleted_datetime, true);


        return $criteria;

    }

}
