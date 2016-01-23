<?php

class Connection {

    // define the constructor variables
    // I know this isn't very secure, but it's not like I'm putting
    // Friendbook in production or something
    private $database_name     = Constant::database_name;
    private $database_server   = Constant::database_server;
    private $database_username = Constant::database_username;
    private $database_password = Constant::database_password;

    // the database connection
    public $connection;

    // whether the connection is successful or not
    public $connection_successful;

    /**
     * Create a connection to the database and indicate whether the connection was fine
     * or not
     */
    public function __construct() {
        // just so I don't have to type long variable names each time
        $database_server   = $this->database_server;
        $database_username = $this->database_username;
        $database_password = $this->database_password;

        // the connection to the database is made
        $this->connection = mysqli_connect($database_server, $database_username, $database_password);

        // the number of times a connection to the database was attempted
        $connection_attemps = 0;

        // if the connection failed then try a maximum of max_connection_attempts times
        while( !$this->connection && $connection_attemps < Constant::max_connection_attempts ) {
            $connection_attemps += 1;
            $this->connection_successful = false;
        }

        if( $this->connection ) {
            $this->connection_successful = true;
        } else {
            $this->connection_successful = false;
        }
    }

    /**
     * @param $sql_query The SQL query to process
     * @param $return_type How the result should be given e.g. in a MYSQLI_ASSOC, etc.
     * @return bool|mysqli_result The result of executing the query
     */
    public function process_query( $sql_query, $return_type = null ) {
        // select the database to run the query
        mysqli_select_db( $this->connection, $this->database_name );

        if( is_null($return_type) ) {
            return mysqli_query( $this->connection, $sql_query );
        } else {
            $result = mysqli_query( $this->connection, $sql_query );

            while( $row = mysqli_fetch_array($result, $return_type )) {
                $row_results[] = $row;
            }

            mysqli_free_result( $result );

            if( isset( $row_results ) ) {
                return $row_results;
            } else {
                return array();
            }
        }
    }

    /**
     * Destroy the connection to the database
     */
    public function close_connection() {
        if($this->connection) {
            mysqli_close($this->connection);
        } else {
            return;
        }
    }

}

?>