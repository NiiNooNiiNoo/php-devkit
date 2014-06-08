<?php
/*
 * PHP Basic Development Kit by xSplit
 */
 
 class db extends PDO
{
    private static $instance;
    private $error_handler;

    public static function get($host=null,$user=null,$pass=null,$db=null,array $options=array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ))
    {
        if(self::$instance==null)
        {
            try
            {
                self::$instance = new DB('mysql:dbname='.$db.';host='.$host.';charset=utf8',$user,$pass,$options);
            }
            catch(PDOException $e)
            {
                die('Connection Error: '.$e->getMessage());
            }
        }
        return self::$instance;
    }

    public function pquery($query,array $params=array())
    {
        try
        {
            $prepare = $this->prepare($query);
            $prepare->execute($params);
            return $prepare;
        }
        catch(PDOException $e)
        {
            is_null($this->error_handler) ? die('Query Error: '.$e->getMessage()) : call_user_func($this->error_handler,$e);
        }
    }

    public function setErrorHandler($error_handler)
    {
        $this->error_handler = $error_handler;
    }

    public function select($table,$select,$where='',array $data=array())
    {
        $query = 'SELECT '.$select.' FROM '.$table;
        if(!empty($where)) $query.=' WHERE '.$where;
        return $this->pquery($query,$data);
    }

    public function update($table,$set,$where='',array $data)
    {
        $query = 'UPDATE '.$table.' SET '.$set;
        if(!empty($where)) $query.=' WHERE '.$where;
        return $this->pquery($query,$data);
    }

    public function insert($table,array $keys,array $data)
    {
        $query = 'INSERT INTO '.$table.'('.implode(',',$keys).') VALUES('.substr(str_repeat('?,',count($data)),0,-1).')';
        return $this->pquery($query,array_values($data));
    }

    public function delete($table,$where,array $data)
    {
        $query = 'DELETE FROM '.$table.' WHERE '.$where;
        return $this->pquery($query,$data);
    }

    public function dropTable($name)
    {
        $this->exec("DROP TABLE IF EXISTS $name");
    }

    public function cleanTable($name)
    {
        $this->exec("CREATE TEMPORARY TABLE IF NOT EXISTS $name(base int); TRUNCATE TABLE $name");
    }

    public function createTable($name,array $data,$end='')
    {
        $this->exec("CREATE TABLE IF NOT EXISTS $name(".implode(',',$data).")$end;");
    }

    public function importSQLFile($file)
    {
        if(is_file($file))
        {
            $query = '';
            foreach(file($file) as $line)
            {
                if(substr($line,0,2)=='--') continue;
                $query.=$line;
                if(trim(substr($line,-3))==';')
                {
                    $this->exec($query);
                    $query='';
                }
            }
        }
    }
}
