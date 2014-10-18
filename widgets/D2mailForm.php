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
    public $recipient = false;

    /**
     * show attachment panel
     * @var boolean 
     */
    public $attachments = false;
    public $widgets_view_path;

    public function init() {

        if (!$this->send_label) {
            $this->send_label = Yii::t('AceModule.d2maillist', 'Send');
        }
        
        //load module settings
        $module_d2messages = Yii::app()->getModule('d2messages');
        if ($module_d2messages->write) {
            if (isset($module_d2messages->write['recipient'])) {
                $this->recipient = $module_d2messages->write['recipient'];
                
            }
        }        

    }

    public function run() {

        $recipient_html = false;
        if ($this->recipient) {
            $recipient_html = $this->render($this->widgets_view_path . '.D2mailFormRecipient', array(
                'placeholder' => Yii::t('D2messagesModule.models','Enter name or group'),
            ),true);
            
        }

        $this->render($this->widgets_view_path . '.D2mailForm', array(
            'send_label' => $this->send_label,
            'recipient_html' => $recipient_html,
            'attachments' => $this->attachments,
        ));
    }

}
