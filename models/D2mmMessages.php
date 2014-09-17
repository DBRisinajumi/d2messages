<?php

// auto-loading
Yii::setPathOfAlias('D2mmMessages', dirname(__FILE__));
Yii::import('D2mmMessages.*');

class D2mmMessages extends BaseD2mmMessages
{

    // Add your model-specific methods here. This file will not be overriden by gtc except you force it.
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function init()
    {
        return parent::init();
    }

    public function getItemLabel()
    {
        return parent::getItemLabel();
    }

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            array()
        );
    }

    public function rules()
    {
        return array_merge(
            parent::rules()
        /* , array(
          array('column1, column2', 'rule1'),
          array('column3', 'rule2'),
          ) */
        );
    }

    public function search($criteria = null)
    {
        if (is_null($criteria)) {
            $criteria = new CDbCriteria;
        }
        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $this->searchCriteria($criteria),
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
    } 

    public function delete(){
        
        //delete recipient records
        foreach($this->d2mrRecipients as $d2mr){
            $d2mr->delete();
        }
        return parent::delete();
    }
    
    /**
     * message count, where
     *   status = SENT
     *   read_datetime = null
     *   recipient = user_id or role in user_roles()
     * @return int
     */
    public static function getCountUnreadMessages(){
        $sql = "  
                SELECT 
                  COUNT(DISTINCT d2mr_d2mm_id) unread_messages 
                FROM
                    d2mm_messages 
                    INNER JOIN d2mr_recipient 
                        ON d2mm_id = d2mr_d2mm_id 
                        AND d2mm_status = 'SENT' 
                  INNER JOIN authassignment aa 
                    ON aa.userid = ".Yii::app()->user->id." 
                    AND d2mr_recipient_role = aa.itemname 
                    OR d2mr_recipient_pprs_id = ".Yii::app()->getModule('user')->user()->profile->person_id." 
                WHERE d2mr_read_datetime IS NULL             
                 ";
        
         return Yii::app()->db->createCommand($sql)->queryScalar();
    }

    /**
     * mark message as read:
     *  - find record by user person id or role
     *  - registre read time and user
     * @param int $d2mm_id  message id
     * @return none
     * @todo normalizēt roles list atgriezšanu
     */
    public static function markMessageAsRead($d2mm_id){
        $pprs_id = Yii::app()->getModule('user')->user()->profile->person_id;
        
        //meklee peec personas
        $d2mr = D2mrRecipient::model()->findByAttributes(array(
            'd2mr_d2mm_id' => $d2mm_id,
            'd2mr_recipient_pprs_id' =>$pprs_id,
        ));
        if(!empty($d2mr)){
            if(empty($d2mr->d2mr_read_datetime)){
                $d2mr->d2mr_read_datetime = new CDbExpression('NOW()');
                $d2mr->save();
            }          
            return;            
        }
        
        
        //get all user roles
        $sql = " 
            SELECT 
                a.name 
            FROM
                authassignment aa 
                INNER JOIN authitem a 
                  ON aa.itemname = a.name 
            WHERE userid = ".Yii::app()->user->id." 
                AND `type` = 2 
            ";
        $user_roles = Yii::app()->db->createCommand($sql)->queryAll();
        $ur = array();
        foreach($user_roles as $row){
            $ur[] = $row['name'];
        }
        
        
        //meklee peec roles
        $d2mr = D2mrRecipient::model()->findByAttributes(array(
                'd2mr_d2mm_id' => $d2mm_id,
                'd2mr_recipient_role' => $ur,
        ));
        
        //mark as read
        if(!empty($d2mr)){
            if(empty($d2mr->d2mr_read_datetime)){
                $d2mr->d2mr_recipient_pprs_id = $pprs_id;
                $d2mr->d2mr_read_datetime = new CDbExpression('NOW()');
                $d2mr->save();
            }
            return;
        }
        
        return;
    }

    /**
     * create new message and set model namea and model record id
     * @param string $model_name
     * @param int $record_id
     * @return int d2mm_id
     */
    public static function createMessageForModel($model_name,$record_id){
        $model = new D2mmMessages();
        $model->d2mm_model_record_id = $record_id;
        $model->d2mm_model = $model_name;
        //var_dump($model);exit;
        $model->save(false);
        return $model->d2mm_id;
    }
    
    /**
     * set reipient as user role
     * @param id $d2mm_id
     * @param string $role
     */
    public static function setRecipientRole($d2mm_id,$role){
        $d2mr = new D2mrRecipient;
        $d2mr->d2mr_d2mm_id = $d2mm_id;
        $d2mr->d2mr_recipient_role = $role;
        $d2mr->save();
    }
    
    /**
     * set message text
     * @param int $d2mm_id
     * @param type $text
     */
    public static function setText($d2mm_id,$text){
        $model = D2mmMessages::model()->findByPk($d2mm_id);
        $model->d2mm_text = $text;
        $model->save();
    }

    /**
     * set message subject
     * @param int $d2mm_id
     * @param type $subject
     */    
    public static function setSubject($d2mm_id,$subject){
        $model = D2mmMessages::model()->findByPk($d2mm_id);
        $model->d2mm_subject = $subject;
        $model->save();
    }
    
    /**
     * registre message as send. Set
     *  status = SENT
     *  sender = user person id
     *  created = now
     * @param int $d2mm_id
     */
    public static function send($d2mm_id){
        $model = D2mmMessages::model()->findByPk($d2mm_id);
        $model->d2mm_status = D2mmMessages::D2MM_STATUS_SENT;
        $model->d2mm_sender_pprs_id = Yii::app()->getModule('user')->user()->profile->person_id;
        $model->d2mm_created = new CDbExpression('NOW()');
        $model->save();
    }
    
    
    
    
}
