<?php

/**
 * show message data inline
 */
class D2mailShowInline extends CWidget {
 
    public $model_name = '';
    public $model_label = '';
    public $model_record_id = '';
    public $subject = '';
    public $sender = '';
    public $sender_link = '#';
    public $message = '';
    public $created = '';
    public $widgets_view_path;     
    
    public function run() {
        $module_d2messages = Yii::app()->getModule('d2messages');
        $this->render($this->widgets_view_path.'.D2MailShowInlane',array(
            'model_name' => $this->model_name,
            'model_label' => $this->model_label,
            'model_link' => $module_d2messages->createUrlToModelRecord($this->model_name,$this->model_record_id),
            'subject' => $this->subject,
            'sender' => $this->sender,
            'sender_link' => $this->sender_link,
            'message' => $this->message,
            'created' => $this->created,
        ));
    }
 
}