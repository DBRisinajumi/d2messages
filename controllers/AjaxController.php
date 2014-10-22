<?php

class AjaxController extends Controller {
    #public $layout='//layouts/column2';

    public $defaultAction = "admin";
    public $scenario = "crud";
    public $scope = "crud";

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'actions' => array('list','write','messageInline','sent'),
                'roles' => array('D2messages.D2mmMessages.*'),
            ),
            array(
                'allow',
                'actions' => array('write'),
                'roles' => array('D2messages.D2mmMessages.Create'),
            ),
            array(
                'allow',
                'actions' => array('list','messageInline'), 
                'roles' => array('D2messages.D2mmMessages.View'),
            ),
            array(
                'allow',
                'actions' => array('update', 'editableSaver'),
                'roles' => array('D2messages.D2mmMessages.Update'),
            ),
            array(
                'deny',
                'users' => array('*'),
            ),
        );
    }

    public function beforeAction($action) {
        parent::beforeAction($action);
        if ($this->module !== null) {
            $this->breadcrumbs[$this->module->Id] = array('/' . $this->module->Id);
        }
        return true;
    }

    /**
     * sow and save email
     * @param int $aobj_id
     * @return execut widgets
     */
    public function actionWrite($model_name,$model_id,$add_data) {

        $subject = Yii::app()->request->getPost('subject');
        $message = Yii::app()->request->getPost('message');

        if (!empty($subject) || !empty($message)) {
            if(!empty($model_name)){
                $d2mm_id = D2mmMessages::createMessageForModel($model_name, $model_id);
            }else{
                $d2mm_id = D2mmMessages::createMessage();
            }
            
            //process recipients
            $recipients_csv = Yii::app()->request->getPost('recipient_list');
            if(!empty($recipients_csv)){
                $recipients = explode(',',$recipients_csv);
                foreach($recipients as $r){
                    var_dump($r);
                    D2mmMessages::setRecipientByName($d2mm_id, trim($r));
                }
            }

            //set default recipients
            $module_d2messages = Yii::app()->getModule('d2messages');
            if ($module_d2messages->write && isset($module_d2messages->write['default_recipient'])) {
                $dr = $module_d2messages->write['default_recipient'];
                if(isset($dr['person_user']) && $dr['person_user']){
                    foreach($dr['person_user'] as $pprs_id){
                        D2mmMessages::setRecipientPerson($d2mm_id,$pprs_id);
                    }
                }
                if(isset($dr['role']) && $dr['role']){
                    foreach($dr['role'] as $role){
                        D2mmMessages::setRecipientRole($d2mm_id,$role);
                    }
                }

            }              

            D2mmMessages::setSubject($d2mm_id, $subject);
            D2mmMessages::setText($d2mm_id, $message);
            D2mmMessages::send($d2mm_id);

            $this->redirect(array(
                'List', 
                'model_name' => $model_name, 
                'model_id' => $model_id, 
                'add_data' => $add_data,
                ));
            //$model = $this->loadModel($aobj_id);
            //$this->renderPartial('_d2mailList',array('modelMain'=>$model));            
            return;
        } else {
            $view_path = Yii::app()->params['theme_settings']['widgets_view_path'];
            $this->widget('D2mailForm', array(
                //'send_label' => 'Saglabāt',
                //'recipient' => false,
                'widgets_view_path' => $view_path,   
            ));
        }
    }

    /**
     * show message list
     * @param type $aobj_id
     */
    public function actionList($add_data,$search = false) {
        $view_path = Yii::app()->params['theme_settings']['widgets_view_path'];

        $add_data = json_decode($add_data, true);
        
        $criteria = D2mmMessages::createListCriteria($add_data,$search);

        unset($add_data['model_name']);
        unset($add_data['model_id']);
        unset($add_data['pprs_id']);
        
        $this->widget('D2mailList', array_merge(array(
            'data_model' => 'D2mmMessages',
            'search' => $search,
            'criteria' => $criteria,
            'widgets_view_path' => $view_path), $add_data)
        );

    }

    /**
     * show message list
     * @param type $aobj_id
     */
    public function actionSent($add_data,$search = false) {
        
        $view_path = Yii::app()->params['theme_settings']['widgets_view_path'];

        $add_data = json_decode($add_data, true);
        
        unset($add_data['pprs_id']);
        $add_data['to_pprs_id'] = Yii::app()->getModule('user')->user()->profile->person_id;
        
        $criteria = D2mmMessages::createListCriteria($add_data,$search);

        unset($add_data['model_name']);
        unset($add_data['model_id']);
        unset($add_data['to_pprs_id']);
        
        $this->widget('D2mailList', array_merge(array(
            'data_model' => 'D2mmMessages',
            'search' => $search,
            'criteria' => $criteria,
            'widgets_view_path' => $view_path), $add_data)
        );

    }

    /**
     * show message inline
     * @param type $d2mm_id
     */
    public function actionMessageInline($d2mm_id) {
        
        $d2mm = D2mmMessages::model()->findByPk($d2mm_id);
        if (!$d2mm){
            throw new CHttpException(404, Yii::t('D2messagesModule.crud', 'The requested page does not exist.'));
        }
        
        //reader must be sender or recipient
        if(!Yii::app()->user->checkAccess('D2messages.ReadAll')){
            $pprs_id = Yii::app()->getModule('user')->user()->profile->person_id;
            if($d2mm->d2mm_sender_pprs_id != $pprs_id){
                $b = false;
                foreach ($d2mm->d2mrRecipients as $d2mr) {
                    $b = $b || ($d2mr->d2mr_recipient_pprs_id == $pprs_id);
                }
                if(!$b){
                    throw new CHttpException(404, Yii::t('D2messagesModule.crud', 'The requested page does not exist.'));
                }
            }
        }
            
        
        D2mmMessages::markMessageAsRead($d2mm_id);
        $view_path = Yii::app()->params['theme_settings']['widgets_view_path'];
        $this->widget('D2mailShowInline', array(
            'model_name' => $d2mm->d2mm_model,
            'model_label' => $d2mm->d2mm_model_label,
            'model_record_id' => $d2mm->d2mm_model_record_id,
            'subject' => $d2mm->d2mm_subject,
            'sender' => $d2mm->d2mmSenderPprs->itemLabel,
            'message' => str_replace(PHP_EOL, '<br />', $d2mm->d2mm_text),
            'created' => $d2mm->d2mm_created,
            'widgets_view_path' => $view_path,
        ));

    }

    public function loadModel($id) {
        $m = D2mmMessages::model();
        // apply scope, if available
        $scopes = $m->scopes();
        if (isset($scopes[$this->scope])) {
            $m->{$this->scope}();
        }
        $model = $m->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, Yii::t('D2messagesModule.crud', 'The requested page does not exist.'));
        }
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'd2mm-messages-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
