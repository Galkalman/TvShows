<?php

require_once("Constants.php");

class DB
{
    private $pdo;
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    public $error;

    /**
     * DB constructor.
     * Connect to the database.
     */
    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;

        $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );

        try{
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        }
        catch(PDOException $e){
            $this->error = $e->getMessage();
        }
    }

    /**
     * @param $query -> A query to be executed by the DB
     * @return array -> Results of the wuery in array form
     */
    private function execute($query)
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        try {
            return $stmt->fetchAll();
        }
        catch (Exception $e) {
            return $stmt->rowCount();
        }
    }

    /**
     * @param $tblName -> The table name to select from
     * @param array $params
     * @return array -> All lines from $tblName
     */
    public function select($tblName, $params=array(0=>0))
    {
        $query = "SELECT * FROM `" . $tblName . "` WHERE ";
        foreach ($params as $key => $value)
        {
            $query .= $key . '="' . $value . '" ';
        }
        try {
            $res = $this->execute($query);
        }
        catch (Exception $e) {
            $res = "Nothing found.";
        }
        return $res;
    }

    /**
     * @return array -> Returns an array of the first letters of the TvShows from the table tvShows
     */
    public function firstLetter()
    {
        $query = "SELECT SUBSTRING(`Title`, 1, 1) FROM tvShows";
        $res = $this->execute($query);
        return $res;
    }

    /**
     * @param $tblName
     * @param array $params => An array of parameters, the keys are the columns names and the values are another array, the keys will be
     *                         type, size, null (0 if not null or 1 if null), default, primary (0 if not primary or 1 if primary), AI (auto increment, 0 if not or if yes)
     *                         the values will match the keys.
     * @return array|string
     */
    public function create($tblName, $params=array(0=>0))
    {
        $query = "CREATE TABLE `" . $tblName . "` (";
        $primaryKey = "";

        foreach ($params as $col => $value)
        {
            $query .= "`" . $col . "`";
            foreach ($value as $type => $typeValue)
            {
                if ($type == "size" and $typeValue != 0)
                {
                    $query .= "(" . $typeValue . ")";
                }
                elseif ($type == "null")
                {
                    $query .= $typeValue == 0 ? " NOT NULL" : " NULL";
                }
                elseif ($type == "default" and $typeValue != "")
                {
                    if ($value["type"] != "BIT")
                    {
                        $query .= " DEFAULT '" . $typeValue . "'";
                    }
                    else
                    {
                        $query .= " DEFAULT " . $typeValue;
                    }
                }
                elseif ($type == "primary" and $typeValue == 1)
                {
                    $primaryKey = $col;
                }
                elseif ($type == "AI" and $typeValue == 1)
                {
                    $query .= " AUTO_INCREMENT";
                }
                elseif ($type == "type")
                {
                    $query .= " " . $typeValue;
                }
            }
            $query .= ",";
        }

        if ($primaryKey != "")
        {
            $query .= " PRIMARY KEY (`" . $primaryKey . "`));";
        }
        else
        {
            $query = substr($query, 0, -1);
            $query .= ");";
        }

        try{
            $res = $this->execute($query);
            return $res;
        }
        catch (Exception $e){
            $this->error = "Something went wrong...";
            return $this->error;
        }
    }

    # TODO: Crete the delete function
    public function delete()
    {
        pass;
    }

    /**
     * @param $tblName
     * @param array $params
     * @return array|string
     */
    public function insert($tblName, $params=array(0=>0))
    {
        $query = "INSERT INTO `" . $tblName . "` (";
        $keys = "";
        $values = "";
        foreach ($params as $key => $value)
        {
            $keys .= "`" . $key . "`," ;
            $values .= "'" . $value . "',";
        }
        $query .= substr($keys, 0, -1) . ") VALUES (" . substr($values, 0, -1) . ")";
        try{
            $res = $this->execute($query);
            return $res;
        }
        catch (Exception $e){
            $this->error = "Something went wrong...";
            return $this->error;
        }
    }

    # TODO: Crete the update function
    public function update()
    {
        pass;
    }

    # TODO: Create the drop function
    public function drop()
    {
        pass;
    }

    /**
     * @param $tblName -> The show key for the table we want the count of.
     * @return int -> Number of lines in table, e.g: Number of episodes in the season.
     */
    public function lineCount($tblName)
    {
        $res = $this->select($tblName);
        return count($res);
    }

    public function getOverAllNumber($tblName)
    {
        $res = $this->select($tblName, array("EpisodeNo" => ($this->lineCount($tblName))));
        return $res[0]["OverAll"];
    }
}