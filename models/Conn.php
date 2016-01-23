<?php
/**
 * Created by PhpStorm.
 * User: Joe
 * Date: 06-Oct-15
 * Time: 4:26 PM
 */

/**
 * Creates a connection to the database
 */
function create_connection() {
    // the constants needed to connect to the database
    $database_server   = Constant::database_server;
    $database_username = Constant::database_username;
    $database_password = Constant::database_password;

    // create a connection to the database. I don't think it's safe to put
    // this piece of codehere, but whatevs
    $connection = mysqli_connect($database_server, $database_username, $database_password);

    if( $connection ) {
        return $connection;
    } else {
        return false;
    }
}

/**
 * Destroys the connection to the database if it is no longer needed
 * @param $connection mysqli The connection to destroy
 */
function close_connection( $connection ) {
    // awesome one liner!
    mysqli_close( $connection );
}

/**
 * Processes the query and returns the data.
 * @param $query string The SQL query to process
 * @param null $return_type How to return the result (MYSQLI_ASSOC, MYSQLI_NUM, etc.)
 */
function process_query( $query, $connection, $return_type=null) {
    // if the connection is broken, make a new one
    if( !$connection ) {
        unset( $connection );
        $connection = create_connection();
    }

    // the name of the database to connect to
    $database_name = Constant::database_name;

    // select the database to process the query in
    mysqli_select_db( $connection, $database_name );

    if( is_null( $return_type ) ) {
        // return type is null, so just "run" the query
        return mysqli_query( $connection, $query );
    } else {

        // the result when the query is processed
        $result = mysqli_query( $connection, $query );

        while( $row = mysqli_fetch_array( $result, $return_type ) ) {
            $row_results[] = $row;
        }

        mysqli_free_result( $result );

        // return the row results if it is set (i.e. the while loop was executed),
        // else just return an empty array
        return isset( $row_results ) ? $row_results : array();

    }
}

?>