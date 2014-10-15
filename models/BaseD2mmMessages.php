<?php

/**
 * This is the model base class for the table "d2mm_messages".
 *
 * Columns in table "d2mm_messages" available as properties of the model:
 * @property string $d2mm_id
 * @property string $d2mm_priority
 * @property string $d2mm_status
 * @property string $d2mm_model
 * @property string $d2mm_model_record_id
 * @property string $d2mm_model_label
 * @property integer $d2mm_sender_pprs_id
 * @property string $d2mm_thread_id
 * @property integer $d2mm_read
 * @property integer $d2mm_ds
 * @property string $d2mm_created
 * @property string $d2mm_subject
 * @property string $d2mm_text
 *
 * Relations of table "d2mm_messages" available as properties of the model:
 * @property PprsPerson $d2mmSenderPprs
 * @property D2mrRecipient[] $d2mrRecipients
 */
abstract class BaseD2mmMessages extends CActiveRecord
{
    /**
    * ENUM field values
    */
    const D2MM_PRIORITY_NORMAL = 'NORMAL';
    const D2MM_PRIORITY_IMPORTANT = 'IMPORTANT';
    const D2MM_PRIORITY_LOW = 'LOW';
    const D2MM_STATUS_DRAFT = 'DRAFT';
    const D2MM_STATUS_SENT = 'SENT';
    
    var $enum_labels = false;  

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'd2mm_messages';
    }

    public function rules()
    {
        return array_merge(
            parent::rules(), array(
                array('d2mm_priority, d2mm_status, d2mm_model, d2mm_model_record_id, d2mm_model_label, d2mm_sender_pprs_id, d2mm_thread_id, d2mm_read, d2mm_ds, d2mm_created, d2mm_subject, d2mm_text', 'default', 'setOnEmpty' => true, 'value' => null),
                array('d2mm_sender_pprs_id, d2mm_read, d2mm_ds', 'numerical', 'integerOnly' => true),
                array('d2mm_priority', 'length', 'max' => 9),
                array('d2mm_status', 'length', 'max' => 5),
                array('d2mm_model', 'length', 'max' => 50),
                array('d2mm_model_record_id, d2mm_thread_id', 'length', 'max' => 10),
                array('d2mm_model_label', 'length', 'max' => 150),
                array('d2mm_created, d2mm_subject, d2mm_text', 'safe'),
                array('d2mm_id, d2mm_priority, d2mm_status, d2mm_model, d2mm_model_record_id, d2mm_model_label, d2mm_sender_pprs_id, d2mm_thread_id, d2mm_read, d2mm_ds, d2mm_created, d2mm_subject, d2mm_text', 'safe', 'on' => 'search'),
            )
        );
    }

    public function getItemLabel()
    {
        return (string) $this->d2mm_priority;
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
                'd2mmSenderPprs' => array(self::BELONGS_TO, 'PprsPerson', 'd2mm_sender_pprs_id'),
                'd2mrRecipients' => array(self::HAS_MANY, 'D2mrRecipient', 'd2mr_d2mm_id'),
            )
        );
    }

    public function attributeLabels()
    {
        return array(
            'd2mm_id' => Yii::t('D2messagesModule.model', 'D2mm'),
            'd2mm_priority' => Yii::t('D2messagesModule.model', 'D2mm Priority'),
            'd2mm_status' => Yii::t('D2messagesModule.model', 'D2mm Status'),
            'd2mm_model' => Yii::t('D2messagesModule.model', 'D2mm Model'),
            'd2mm_model_record_id' => Yii::t('D2messagesModule.model', 'D2mm Model Record'),
            'd2mm_model_label' => Yii::t('D2messagesModule.model', 'D2mm Model Label'),
            'd2mm_sender_pprs_id' => Yii::t('D2messagesModule.model', 'D2mm Sender Pprs'),
            'd2mm_thread_id' => Yii::t('D2messagesModule.model', 'D2mm Thread'),
            'd2mm_read' => Yii::t('D2messagesModule.model', 'D2mm Read'),
            'd2mm_ds' => Yii::t('D2messagesModule.model', 'D2mm Ds'),
            'd2mm_created' => Yii::t('D2messagesModule.model', 'D2mm Created'),
            'd2mm_subject' => Yii::t('D2messagesModule.model', 'D2mm Subject'),
            'd2mm_text' => Yii::t('D2messagesModule.model', 'D2mm Text'),
        );
    }

    public function enumLabels()
    {
        if($this->enum_labels){
            return $this->enum_labels;
        }    
        $this->enum_labels =  array(
           'd2mm_priority' => array(
               self::D2MM_PRIORITY_NORMAL => Yii::t('D2messagesModule.model', 'D2MM_PRIORITY_NORMAL'),
               self::D2MM_PRIORITY_IMPORTANT => Yii::t('D2messagesModule.model', 'D2MM_PRIORITY_IMPORTANT'),
               self::D2MM_PRIORITY_LOW => Yii::t('D2messagesModule.model', 'D2MM_PRIORITY_LOW'),
           ),
           'd2mm_status' => array(
               self::D2MM_STATUS_DRAFT => Yii::t('D2messagesModule.model', 'D2MM_STATUS_DRAFT'),
               self::D2MM_STATUS_SENT => Yii::t('D2messagesModule.model', 'D2MM_STATUS_SENT'),
           ),
            );
        return $this->enum_labels;
    }

    public function getEnumFieldLabels($column){

        $aLabels = $this->enumLabels();
        return $aLabels[$column];
    }

    public function getEnumLabel($column,$value){

        $aLabels = $this->enumLabels();

        if(!isset($aLabels[$column])){
            return $value;
        }

        if(!isset($aLabels[$column][$value])){
            return $value;
        }

        return $aLabels[$column][$value];
    }

    public function getEnumColumnLabel($column){
        return $this->getEnumLabel($column,$this->$column);
    }
    

    public function searchCriteria($criteria = null)
    {
        if (is_null($criteria)) {
            $criteria = new CDbCriteria;
        }

        $criteria->compare('t.d2mm_id', $this->d2mm_id, true);
        $criteria->compare('t.d2mm_priority', $this->d2mm_priority);
        $criteria->compare('t.d2mm_status', $this->d2mm_status);
        $criteria->compare('t.d2mm_model', $this->d2mm_model, true);
        $criteria->compare('t.d2mm_model_record_id', $this->d2mm_model_record_id, true);
        $criteria->compare('t.d2mm_model_label', $this->d2mm_model_label, true);
        $criteria->compare('t.d2mm_sender_pprs_id', $this->d2mm_sender_pprs_id);
        $criteria->compare('t.d2mm_thread_id', $this->d2mm_thread_id, true);
        $criteria->compare('t.d2mm_read', $this->d2mm_read);
        $criteria->compare('t.d2mm_ds', $this->d2mm_ds);
        $criteria->compare('t.d2mm_created', $this->d2mm_created, true);
        $criteria->compare('t.d2mm_subject', $this->d2mm_subject, true);
        $criteria->compare('t.d2mm_text', $this->d2mm_text, true);


        return $criteria;

    }

}
