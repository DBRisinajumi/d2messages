<?php
 
class m140915_081909_auth_D2mmMessages extends CDbMigration
{

    public function up()
    {
        $this->execute("
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('D2messages.D2mmMessages.*','0','D2messages.D2mmMessages',NULL,'N;');
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('D2messages.D2mmMessages.Create','0','D2messages.D2mmMessages module create',NULL,'N;');
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('D2messages.D2mmMessages.View','0','D2messages.D2mmMessages module view',NULL,'N;');
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('D2messages.D2mmMessages.Update','0','D2messages.D2mmMessages module update',NULL,'N;');
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('D2messages.D2mmMessages.Delete','0','D2messages.D2mmMessages module delete',NULL,'N;');
                
            INSERT INTO `authitem` VALUES('D2messages.D2mmMessagesCreate', 2, 'D2messages.D2mmMessages create', NULL, 'N;');
            INSERT INTO `authitem` VALUES('D2messages.D2mmMessagesUpdate', 2, 'D2messages.D2mmMessages update', NULL, 'N;');
            INSERT INTO `authitem` VALUES('D2messages.D2mmMessagesDelete', 2, 'D2messages.D2mmMessages delete', NULL, 'N;');
            INSERT INTO `authitem` VALUES('D2messages.D2mmMessagesView', 2, 'D2messages.D2mmMessages view', NULL, 'N;');
            
            INSERT INTO `authitemchild` VALUES('D2messages.D2mmMessagesCreate', 'D2messages.D2mmMessages.Create');
            INSERT INTO `authitemchild` VALUES('D2messages.D2mmMessagesUpdate', 'D2messages.D2mmMessages.Update');
            INSERT INTO `authitemchild` VALUES('D2messages.D2mmMessagesDelete', 'D2messages.D2mmMessages.Delete');
            INSERT INTO `authitemchild` VALUES('D2messages.D2mmMessagesView', 'D2messages.D2mmMessages.View');

        ");
    }

    public function down()
    {
        $this->execute("
            DELETE FROM `authitemchild` WHERE `parent` = 'D2messages.D2mmMessagesEdit';
            DELETE FROM `authitemchild` WHERE `parent` = 'D2messages.D2mmMessagesView';

            DELETE FROM `authitem` WHERE `name` = 'D2messages.D2mmMessages.*';
            DELETE FROM `authitem` WHERE `name` = 'D2messages.D2mmMessages.edit';
            DELETE FROM `authitem` WHERE `name` = 'D2messages.D2mmMessages.fullcontrol';
            DELETE FROM `authitem` WHERE `name` = 'D2messages.D2mmMessages.readonly';
            DELETE FROM `authitem` WHERE `name` = 'D2messages.D2mmMessagesEdit';
            DELETE FROM `authitem` WHERE `name` = 'D2messages.D2mmMessagesView';
        ");
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


