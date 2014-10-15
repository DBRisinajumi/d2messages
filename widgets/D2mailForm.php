<?php

/**
 * message input form
 */
class D2mailForm extends CWidget {
 
    /**
     * can set other label for send button
     * @var string 
     */
    public $send_label = false;
    
    /**
     * show recipient inputbox
     * @var boolean 
     */
    public $recipient = true;

    /**
     * show attachment panel
     * @var boolean 
     */    
    public $attachments = false;
    public $widgets_view_path; 
    public function init() {
        
        if (!$this->send_label){
            $this->send_label = Yii::t('AceModule.d2maillist', 'Send');
        }
        
    }
    
    
    public function run() {
        $this->render($this->widgets_view_path.'.D2mailForm',array(
            'send_label' => $this->send_label,
            'recipient' => $this->recipient,
            'attachments' => $this->attachments,
        ));
    }
 
}