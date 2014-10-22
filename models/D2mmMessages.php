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
     * unread message count, where
     *   read_datetime = null
     *   recipient = pprs_id
     * @param int/false $pprs_id if false, get active user pprs_id
     * @return int
     */
    public static function getCountUnreadMessages($pprs_id = false){
        
        if(!$pprs_id){
            $pprs_id = Yii::app()->getModule('user')->user()->profile->person_id;
        }
        $sql = "  
                SELECT 
                  COUNT(DISTINCT d2mr_id) cnt
                FROM
                  d2mr_recipient 
                WHERE d2mr_recipient_pprs_id = :pprs_id 
                  AND d2mr_read_datetime IS NULL             
                 ";
        $rawData = Yii::app()->db->createCommand($sql);
        $rawData->bindParam(":pprs_id",$pprs_id , PDO::PARAM_INT);                

        return $rawData->queryScalar();

    }

    /**
     * mark message as read:
     *  - find record by user person id 
     *  - registre read time
     * @param int $d2mm_id  message id
     * @return none
     */
    public static function markMessageAsRead($d2mm_id){
        
        
        $user_id = Yii::app()->getModule('user')->user()->id;
        $pprs_id = Yii::app()->getModule('user')->user()->profile->person_id;
        
        //find recipient direct to person
        $d2mr = D2mrRecipient::model()->findByAttributes(array(
            'd2mr_d2mm_id' => $d2mm_id,
            'd2mr_recipient_pprs_id' =>$pprs_id,
        ));

        //message already are read
        if($d2mr && !empty($d2mr->d2mr_read_datetime)){
           return; 
        }
        
        //recipient is current person - only set read time
        if($d2mr &&  empty($d2mr->d2mr_read_datetime)){
            $d2mr->d2mr_read_datetime = new CDbExpression('NOW()');
            $d2mr->save();
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
        
        //get model recort item label
        $m = new $model_name;
        $m_item_lable = $m->findByPk($record_id)->itemLabel;
        
        //create record
        $model = new D2mmMessages();
        $model->d2mm_model_record_id = $record_id;
        $model->d2mm_model = $model_name;
        $model->d2mm_model_label = $m_item_lable ;
        //var_dump($model);exit;
        $model->save(false);
        return $model->d2mm_id;
    }

    /**
     * create new message and set model namea and model record id
     * @param string $model_name
     * @param int $record_id
     * @return int d2mm_id
     */
    public static function createMessage(){
        
        //create record
        $model = new D2mmMessages();
        $model->save(false);
        return $model->d2mm_id;
    }
    
    /**
     * set reipient as user role
     * @param id $d2mm_id
     * @param string $role
     */
    public static function setRecipientRole($d2mm_id,$role){
        
        //get roles users
        $pprs_list = Authassignment::getRoleUsers($role);
        
        foreach ($pprs_list as $pprs_id) {
            $d2mr = new D2mrRecipient;
            $d2mr->d2mr_d2mm_id = $d2mm_id;
            $d2mr->d2mr_recipient_pprs_id = $pprs_id;
            $d2mr->d2mr_recipient_role = $role;
            $d2mr->save();            
        }
        

    }

    /**
     * set reipient as person
     * @param id $d2mm_id
     * @param int $pprs_id
     */
    public static function setRecipientPerson($d2mm_id,$pprs_id){
        $d2mr = new D2mrRecipient;
        $d2mr->d2mr_d2mm_id = $d2mm_id;
        $d2mr->d2mr_recipient_pprs_id = $pprs_id;
        $d2mr->save();
    }
    
    public static function setRecipientByName($d2mm_id,$recipient){


        $roles = Yii::app()->getModule('user')->UserAdminRoles;
        if(in_array($recipient, $roles)){
            self::setRecipientRole($d2mm_id, $recipient);
            return true;
        }
        
        if($pprs_id = PprsPerson::getPersonsByFullName($recipient)){
            self::setRecipientPerson($d2mm_id, $pprs_id);
            return true;
        }

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
    
    /**
     * create search criterias for message list
     * @param array() $filter
     * @param type $search
     * @return \CDbCriteria
     */
    static public function createListCriteria($filter,$search = false){
        
        $criteria = new CDbCriteria;
        $criteria->distinct=true;
        
        if(isset($filter['model_name']) && $filter['model_name']){
            $criteria->compare('d2mm_model', $filter['model_name']);
        }
        
        if(isset($filter['model_id']) && $filter['model_id']){
            $criteria->compare('d2mm_model_record_id', $filter['model_id']);
        }    

        $criteria->join = " JOIN d2mr_recipient d2mr on d2mm_id = d2mr.d2mr_d2mm_id";

        //for user inbox must be set rcp_pprs_id = user pprs_id
        if(isset($filter['pprs_id']) && $filter['pprs_id']){
            //all direct messages to user
            $criteria->compare('d2mr_recipient_pprs_id', $filter['pprs_id']);
        }    

        //for user sent box must be set _pprs_id = user pprs_id
        if(isset($filter['to_pprs_id']) && $filter['to_pprs_id']){
            //all direct messages to user
            $criteria->compare('d2mm_sender_pprs_id', $filter['to_pprs_id']);
        }    
        
        //reader must be sender or recipient
        if(!Yii::app()->user->checkAccess('D2messages.ReadAll')){
            $pprs_id = Yii::app()->getModule('user')->user()->profile->person_id;
            $criteria_pprs = new CDbCriteria;
            $criteria_pprs->compare('d2mm_sender_pprs_id', $pprs_id,false,'OR');
            $criteria_pprs->compare('d2mr_recipient_pprs_id', $pprs_id,false,'OR');
            $criteria->mergeWith($criteria_pprs);
        }    
        
        //$criteria->with[] = 'd2mmSenderPprs';
        
        //user search
        if($search){
           $criteria_search = new CDbCriteria;

            $model_name_reverse_translation = Yii::app()->getModule('d2messages')->getModelReverseTranslation();
            
            if(isset($model_name_reverse_translation[$search])){

                $criteria_search->compare('d2mm_model',$search,false,'OR');
            }
            $criteria_search->compare('d2mm_model_label',$search,true,'OR');
            $criteria_search->compare('d2mm_created',$search,true,'OR');
            $criteria_search->compare('d2mm_subject',$search,true,'OR');
            $criteria_search->compare('d2mm_text',$search,true,'OR');
            $criteria_search->compare('pprs_first_name',$search,true,'OR');
            $criteria_search->compare('pprs_second_name',$search,true,'OR');
            
            $criteria->mergeWith($criteria_search); 
        }

        $criteria->order = 'd2mm_created desc';
        
        return $criteria;
        
    }
    
    
}
