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
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('D2messages.D2mmMessages.Menu','0','Show D2messages on toolbar',NULL,'N;');            

            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('D2messages.Read','2','Read D2messages',NULL,'N;');                        
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('D2messages.ReadAll','2','Read All D2messages',NULL,'N;');                        
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('D2messages.Write','2','Write D2messages',NULL,'N;');   
            INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES('D2messages.Delete','2','Delete D2messages',NULL,'N;'); 
            
            INSERT INTO authitemchild VALUES ('D2messages.Read','D2messages.D2mmMessages.Menu');
            INSERT INTO authitemchild VALUES ('D2messages.Read','D2messages.D2mmMessages.View');


            INSERT INTO authitemchild VALUES ('D2messages.Write','D2messages.D2mmMessages.Menu');
            INSERT INTO authitemchild VALUES ('D2messages.Write','D2messages.D2mmMessages.View');
            INSERT INTO authitemchild VALUES ('D2messages.Write','D2messages.D2mmMessages.Create');
            INSERT INTO authitemchild VALUES ('D2messages.Write','D2messages.D2mmMessages.Update');

            INSERT INTO authitemchild VALUES ('D2messages.Delete','D2messages.D2mmMessages.Delete');

        ");
    }

    public function down()
    {
        $this->execute("
            DELETE FROM `authitemchild` WHERE `parent` like 'D2messages.%';
            DELETE FROM `authitem` WHERE `name` like 'D2messages.%';
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


