<?php
//$sep = "/admin/";
//include_once('/admin/libs/db_pod.php');
//

include_once('admin/libs/db_config.php');
class db
{
    function get_all($sql)
    {
        try {
            $dbh = new PDO(DB_HOST, DB_USER, DB_PASS);
            $sth = $dbh->prepare($sql);
            $sth->execute();
            $dbh = null;
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print('Error:' . $e->getMessage());
            die();
        }
    }
    function get_select(){
        $result = $this->get_all("SELECT * FROM `select`");
        foreach ($result as $key => $value){
            $tp = $value['type'];
            $rows[$tp][] = $value;
        }
        return $rows;
    }
}