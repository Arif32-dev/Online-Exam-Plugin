<?php

class Create_tables
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
        $this->sql = "CREATE TABLE `{$db_name}`.`{$table}` ( `ID` INT NOT NULL AUTO_INCREMENT , `dept_id` INT NOT NULL , `dept_name` TEXT NOT NULL , `dept_create_date` INT NOT NULL , `dept_last_updated` INT NOT NULL , `dept_author` INT NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB $collate";
        $this->create_tables($table);

        $table = $wpdb->prefix . 'teacher';
        $this->sql = "CREATE TABLE `{$db_name}`.`{$table}` ( `ID` INT NOT NULL AUTO_INCREMENT , `teacher_id` INT NOT NULL , `teacher_name` TEXT NOT NULL , `teacher_dept` INT NOT NULL , `teacher_phn` TEXT NOT NULL , `teacher_email` TEXT NOT NULL , `appoint_date` INT NOT NULL , `status` BOOLEAN NOT NULL , `restriction` BOOLEAN NOT NULL , `restrict_date` INT NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB $collate";
        $this->create_tables($table);

        $table = $wpdb->prefix . 'question_folder';
        $this->sql = "CREATE TABLE `{$db_name}`.`{$table}` ( `ID` INT NOT NULL AUTO_INCREMENT , `dept_id` INT NOT NULL , `exam_folder_name` TEXT NOT NULL , `exam_folder_id` INT NOT NULL , `quantity` INT NOT NULL , `exam_time` INT NOT NULL , `pass_percentage` INT NOT NULL , `publish_exam` BOOLEAN NOT NULL , `published_date` INT NOT NULL , `terminate_exam` BOOLEAN NOT NULL , `termination_date` INT NOT NULL , `examined_by` INT NOT NULL , `exam_status` TEXT NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB $collate";
        $this->create_tables($table);
    }
    public function create_tables($table)
    {
        global $wpdb;
        $table_check_sql = "SHOW TABLES LIKE '$table'";
        $results = $wpdb->get_results($table_check_sql);
        if (count($results) == 0) {
            $this->connection->query($this->sql);
        }
    }
}
new Create_tables();
