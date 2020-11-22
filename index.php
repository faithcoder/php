<?php

class UserController {
    private $input = array(
        2 => [
            'user_id' => 2,
            'user_name' => 'Mr. Abdul Kalam',
            'age' => 53,
        ]
    );
    private $user_id = 2;
    private $userName;
    private $age;
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    function input() {
        $this->userName = $this->input[$this->user_id]['user_name'];
        $this->age = $this->input[$this->user_id]['age'];
    }

    function add() {
        if (isset($this->$input[$this->user_id])) {
            $this->input();
            $this->database->insert(
                "users",
                ["id", "name", "age"],
                [$this->user_id, $this->userName, $this->age]
            );

        }
    }

    function update() {
        if (isset($this->$input[$this->user_id])) {
            $this->input();
            $this->database->update(
                "users",
                ["id", "name", "age"],
                [$this->user_id, $this->userName, $this->age]
            )

            // prepare sql and bind parameters
            /*$stmt = $connection->prepare("UPDATE users SET username=:username, age=:age where id=:id ");
            $stmt->bindParam(':id', $this->user_id);
            $stmt->bindParam(':username', $user_name);
            $stmt->bindParam(':age', $age);
            $stmt->execute();*/
        }
    }
}



class Database {
    private $connection;

    function __constructor() {
        $this->connect();
    }
// STEP ONE
    function connect() {
        $this->connection =  new PDO("mysql:host=localhost;dbname=userdatabase", 'root', '1234');
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
//STEP ONE
    function bindColumnNames($columns = []){
        $columnNames = "";
        foreach ($columns as $index ==> $column){
            $columnNames .= "$column ,";
        }

    return rtrim($columnNames, ",");

}
//STEP TWO
    function bindParameters($columns = []){
        $parameters = "";
        foreach ($columns as $index ==> $column){
            $parameters = ":$column ,";
        }

    return rtrim($parameters, ",");

}
//STEP THREE
    function bindValues($stmt, $column, $values){

        if(count($columns) != count($values)){
            throw new Exception("Invalid Data format");
        }
        $columns = ['id', 'name', 'age'];
        $values = ['2', 'Abrar', '12'];
        foreach ($columns as $index ==> $column){
            $stmt->bindParameters(":$column", $values[$index]);
        }

    return $stmt;
}

    //INSERT 
    function insert($tableName, $columns = "", $values = []) {

        $columnNames = $this->bindColumnNames($columns);
        $parameters = $this->bindParameters($columns);

        $query = "INSERT INTO $tableName $columns ($columnNames) VALUES ($parameters)";
        $stmt = $this->connection->prepare($query);
        $stmt = $this->bindValues($stmt, $columns, $values);
        return $stmt->execute();

    }
    //UPDATE
    function update($tableName, $column = "", $values =[]){
        $columnNames = $this->bindColumnNames($columns);
        $parameters = $this->bindValues($columns);
        $query = "UPDATE $tableName SET $column, $values where id=:id";
        $stmt = $this->connection->prepare($query);
        $stmt = $this->bindValues($stmt, $columns, $values);
        return $stmt->execute();

    }


}
