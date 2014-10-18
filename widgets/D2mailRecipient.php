<?php

/**
 * message input form
 */
class D2mailRecipient extends CWidget {

    public $recipient = false;

    public function init() {
        $persons = array();
        if (in_array('person_user', $this->recipient)) {
            $persons = PprsPerson::getPersonsUsers();
        }

        $roles = array();
        if (in_array('roles', $this->recipient)) {
            $roles = Yii::app()->getModule('user')->UserAdminRoles;
        }
    }

    public function run() {

        return $this->widget(
                        'booster.widgets.TbSelect2', array(
                    'name' => 'recipient',
                    'data' => array('RU' => 'Russian Federation', 'CA' => 'Canada', 'US' => 'United States of America', 'GB' => 'Great Britain'),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                    ),
                        ), true
        );
    }

}
