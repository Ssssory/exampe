<?
/*
 * $arInit = host,db,user,pass
 *
 *
 *
 */
class DataBase {
    //
    private $arInit = array(
        'host'=>'localhost',
        'db'  =>'dbName',
        'user'=>'root',
        'pass'=>'',
    );

    public      $isConn;
    protected   $db;


    function __construct($arInit = '')
    {
        if (empty($arInit)){
            $arInit = $this->arInit;
        }
        $db = new PDO('mysql:host='.$arInit['host'].';dbname='.$arInit['db'], $arInit['user'], $arInit['pass']);
        if ($db){
            $this->isConn = true;
            $this->db     = $db;
        }
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return true;
    }




    #-------------------------------------------------------------------------------------------------------------------
    #   Добавление строки в таблицу
    #-------------------------------------------------------------------------------------------------------------------
    function insert($tableName,$arValue){
        if (is_array($arValue)){
                $queryStr = '';
                $fieldStr = '';
                $valueStr = '';
                foreach ($arValue as $key => $value){
                    $fieldStr .= $key.',';
                    $valueStr .= '"'.$value.'",';
                }
            $fieldStr = substr($fieldStr,0, -1);
            $valueStr = substr($valueStr,0, -1);
            $queryStr = 'INSERT INTO `'.$tableName.'` ('.$fieldStr.') VALUES ('.$valueStr.')';
//            echo $queryStr;
            $STH = $this->db->prepare($queryStr);
        }else{
           return false;
        }
        $STH->execute();
    }


    #-------------------------------------------------------------------------------------------------------------------
    #   Выбор из таблицы
    #-------------------------------------------------------------------------------------------------------------------
    function select($tableName,$value=''){
        if (empty($value)){
            $queryStr = 'SELECT * FROM `'.$tableName.'`';
        }
        if (is_array($value)){
            $whereStr = '';

            if (count($value) > 1){
                foreach ($value as $key => $val){
                    $whereStr .= '`'.$key.'` = "'.$val.'" AND ';
                }
                $whereStr = substr($whereStr,0, -4);

            }else{
                foreach ($value as $key => $val){
                    $whereStr .= '`'.$key.'` = "'.$val.'"';
                }

            }
            $queryStr = 'SELECT * FROM `'.$tableName.'` WHERE '.$whereStr;
        }
        if (!empry($queryStr)) {
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $STH = $this->db->prepare($queryStr);
            $STH->execute();
            return $STH->fetchAll();
        }else{
            return false;
        }
    }


    #-------------------------------------------------------------------------------------------------------------------
    #   Обновление записи
    #-------------------------------------------------------------------------------------------------------------------
    function update($tableName, $field, $value){
        if (empty($field) || empty($value)){
            return false;
        }
        if (!is_array($field) || !is_array($value)){
            return false;
        }else{
            $whereStr = '';
            $fieldStr = '';
            // собираем строку значений
            if (count($field) > 1){
                foreach ($field as $key => $val){
                    $fieldStr .= '`'.$key.'` = "'.$val.'",';
                }
                $fieldStr = substr($fieldStr,0, -1);
            }else{
                foreach ($field as $key => $val){
                    $fieldStr .= '`'.$key.'` = "'.$val.'"';
                }
            }

            // собираем строку условия
            if (count($value) > 1){
                foreach ($value as $key => $val){
                    $whereStr .= '`'.$key.'` = "'.$val.'" AND ';
                }
                $whereStr = substr($whereStr,0, -4);

            }else{
                foreach ($value as $key => $val){
                    $whereStr .= '`'.$key.'` = "'.$val.'"';
                }

            }
            $queryStr = 'UPDATE `'.$tableName.'` SET '.$fieldStr.' WHERE '.$whereStr;
            echo $queryStr;
            $STH = $this->db->prepare($queryStr);
            $STH->execute();
            return true;

        }
    }

    #-------------------------------------------------------------------------------------------------------------------
    #   Удаление записи
    #-------------------------------------------------------------------------------------------------------------------
    function delete($tableName,$value){
        if (empty($value)){
            return false;
        }else{
            if (is_array($value)){
                $whereStr = '';

                if (count($value) > 1){
                    foreach ($value as $key => $val){
                        $whereStr .= '`'.$key.'` = "'.$val.'" AND ';
                    }
                    $whereStr = substr($whereStr,0, -4);

                }else{
                    foreach ($value as $key => $val){
                        $whereStr .= '`'.$key.'` = "'.$val.'"';
                    }

                }
                $queryStr = 'DELETE FROM `'.$tableName.'` WHERE '.$whereStr;
                $STH = $this->db->prepare($queryStr);
                $STH->execute();
                return true;
            }else{
                return false;
            }
        }

    }

    #-------------------------------------------------------------------------------------------------------------------
    #   Выполнение произвольного запроса
    #-------------------------------------------------------------------------------------------------------------------
    public function query( $query, $params = [] ) {
        try {
            $stmt = $this->db->prepare( $query );
            $stmt->execute( $params );
            return TRUE;
        } catch ( PDOException $e ) {
            throw new Exception( $e->getMessage() );
        }
    }

    #-------------------------------------------------------------------------------------------------------------------
    #   Разрыв соединения
    #-------------------------------------------------------------------------------------------------------------------
    public function disconnect() {
        $this->db      =   null;
        $this->isConn  =   false;
    }



}
?>