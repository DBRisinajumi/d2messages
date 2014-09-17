<?php
 
class m140914_222909_init extends CDbMigration
{

    public function up()
    {
        $this->execute("
            CREATE TABLE `d2mm_messages` (
              `d2mm_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
              `d2mm_priority` enum('NORMAL','IMPORTANT','LOW') DEFAULT 'NORMAL',
              `d2mm_status` enum('DRAFT','SENT') DEFAULT 'DRAFT',
              `d2mm_model` varchar(50) DEFAULT NULL COMMENT 'message attached model',
              `d2mm_model_record_id` int(10) unsigned DEFAULT NULL COMMENT 'message attached model record',
              `d2mm_sender_pprs_id` smallint(10) unsigned DEFAULT NULL COMMENT 'sender id',
              `d2mm_thread_id` int(10) unsigned NOT NULL DEFAULT '0',
              `d2mm_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'message read',
              `d2mm_ds` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'deleted by sender',
              `d2mm_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'message created',
              `d2mm_subject` text,
              `d2mm_text` text,
              PRIMARY KEY (`d2mm_id`),
              KEY `ind_d2mm_thread_id` (`d2mm_thread_id`),
              KEY `ind_d2mm_ds` (`d2mm_ds`),
              KEY `fk_d2mm_sender_pprs_id` (`d2mm_sender_pprs_id`),
              KEY `ind_d2mm_model_record_id` (`d2mm_model_record_id`),
              CONSTRAINT `fk_d2mm_sender_pprs_id` FOREIGN KEY (`d2mm_sender_pprs_id`) REFERENCES `pprs_person` (`pprs_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
            
            CREATE TABLE `d2mr_recipient` (
              `d2mr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `d2mr_d2mm_id` int(10) unsigned NOT NULL,
              `d2mr_recipient_pprs_id` smallint(5) unsigned DEFAULT NULL COMMENT 'recipient person',
              `d2mr_recipient_role` varchar(20) DEFAULT NULL COMMENT 'recipient users with role',
              `d2mr_read_datetime` datetime DEFAULT NULL,
              `d2mr_deleted_datetime` datetime DEFAULT NULL,
              PRIMARY KEY (`d2mr_id`),
              KEY `ind_d2mr_recipient_pprs_id` (`d2mr_recipient_pprs_id`),
              KEY `ind_d2mr_recipient_role` (`d2mr_recipient_role`(4)),
              KEY `ind_d2mr_d2mm_id` (`d2mr_d2mm_id`),
              CONSTRAINT `fk_d2mr_d2mm_id` FOREIGN KEY (`d2mr_recipient_pprs_id`) REFERENCES `pprs_person` (`pprs_id`),
              CONSTRAINT `fk_d2mr_recipient_pprs_id` FOREIGN KEY (`d2mr_d2mm_id`) REFERENCES `d2mm_messages` (`d2mm_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8


        ");
    }

    public function down()
    {
        $this->execute("
            DROP TABLE d2mr_recipient;
            DROP TABLE d2mm_messages;
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


