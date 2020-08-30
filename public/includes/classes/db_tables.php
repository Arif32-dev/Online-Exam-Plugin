<?php
namespace OE\includes\classes;

class DB_Tables
{
    private $connection;
    private $sql;
    public function __construct()
    {
        $this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        global $wpdb;
        $db_name = DB_NAME;
        $collate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . 'department';
        $this->sql = "CREATE TABLE `{$db_name}`.`{$table}` ( `ID` INT(255) NOT NULL AUTO_INCREMENT , `dept_id` INT(255) NOT NULL , `dept_name` TEXT NOT NULL , `dept_create_date` INT(255) NOT NULL , `dept_last_updated` INT(255) NOT NULL , `dept_author` INT(255) NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB $collate";
        $this->create_tables();

        $table = $wpdb->prefix . 'teacher';
        $this->sql = "CREATE TABLE `{$db_name}`.`{$table}` ( `ID` INT(255) NOT NULL AUTO_INCREMENT , `teacher_id` INT(255) NOT NULL , `teacher_name` TEXT NOT NULL , `teacher_dept` INT(255) NOT NULL , `teacher_phn` TEXT NOT NULL , `teacher_email` TEXT NOT NULL , `appoint_date` INT(255) NOT NULL , `status` BOOLEAN NOT NULL , `restriction` BOOLEAN NOT NULL , `restrict_date` INT(255) NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB $collate";
        $this->create_tables();

        $table = $wpdb->prefix . 'question_folder';
        $this->sql = "CREATE TABLE `{$db_name}`.`{$table}` ( `ID` INT(255) NOT NULL AUTO_INCREMENT , `dept_id` INT(255) NOT NULL , `exam_folder_name` TEXT NOT NULL , `exam_folder_id` INT(255) NOT NULL , `quantity` INT(255) NOT NULL , `exam_time` INT(255) NOT NULL , `remaining_time` INT(255) NOT NULL, `per_qus_mark` INT(255) NOT NULL, `total_mark` INT(255) NOT NULL, `pass_percentage` INT(255) NOT NULL, `publish_exam` BOOLEAN NOT NULL , `published_date` INT(255) NOT NULL , `terminate_exam` BOOLEAN NOT NULL , `termination_date` INT(255) NOT NULL , `examined_by` INT(255) NOT NULL , `exam_status` TEXT NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB $collate";
        $this->create_tables();

        $table = $wpdb->prefix . 'qustions';
        $this->sql = "CREATE TABLE `{$db_name}`.`{$table}` ( `ID` INT(255) NOT NULL AUTO_INCREMENT , `exam_folder_id` INT(255) NOT NULL , `qustion` LONGTEXT NOT NULL , `qustion_id` INT(255) NOT NULL , `a1` MEDIUMTEXT NOT NULL , `a1_id` INT(255) NOT NULL , `a2` MEDIUMTEXT NOT NULL , `a2_id` INT(255) NOT NULL , `a3` MEDIUMTEXT NOT NULL , `a3_id` INT(255) NOT NULL , `a4` MEDIUMTEXT NOT NULL , `a4_id` INT(255) NOT NULL , `correct_ans` INT(255) NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB $collate";
        $this->create_tables();

        $table = $wpdb->prefix . 'students';
        $this->sql = "CREATE TABLE `{$db_name}`.`{$table}` ( `ID` INT(255) NOT NULL AUTO_INCREMENT , `std_id` TEXT NOT NULL , `std_name` TEXT NOT NULL , `dept_id` INT(255) NOT NULL , `std_phone` TEXT NOT NULL , `std_email` TEXT NOT NULL , `std_password` TEXT NOT NULL , `std_user_name` TEXT NOT NULL , `std_reg_date` INT(255) NOT NULL , `status` BOOLEAN NOT NULL , `restriction` BOOLEAN NOT NULL , `restrict_date` INT(255) NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB $collate";
        $this->create_tables();

        $table = $wpdb->prefix . 'exam_routine';
        $this->sql = "CREATE TABLE `{$db_name}`.`{$table}` ( `ID` INT(255) NOT NULL AUTO_INCREMENT , `dept_id` INT(255) NOT NULL , `user_id` INT(255) NOT NULL,  `exam_name` TEXT NOT NULL , `exam_date` DATE NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB $collate";
        $this->create_tables();

        $table = $wpdb->prefix . 'result';
        $this->sql = "CREATE TABLE `{$db_name}`.`{$table}` ( `ID` INT(255) NOT NULL AUTO_INCREMENT , `std_id` INT(255) NOT NULL, `exam_folder_id` INT(255) NOT NULL, `qustion_id` INT(255) NOT NULL,`student_ans` INT(255) NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB $collate";
        $this->create_tables();

    }
    public function create_tables()
    {
        $this->connection->query($this->sql);
    }
}
