<?php
 
class m141014_142809_alter_d2mm extends CDbMigration
{

    public function up()
    {
        $this->execute("
            ALTER TABLE `d2mm_messages`   
                ADD COLUMN `d2mm_model_label` VARCHAR(150) CHARSET utf8 NULL AFTER `d2mm_model_record_id`;


        ");
    }

    public function down()
    {
//        $this->execute("
//        ");
    }

    public function safeUp()
    {
        $this->up();
    }

    public function safeDown()
    {
        $this->down();
    }
}


