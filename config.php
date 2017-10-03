<?php
if(!isset($CONFIG)) { die; }
include("captcha/simple-php-captcha.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

//backend error storage for front-end
$errors = array();

function addError($error, $color="#FFFFFF")
{
	global $errors;
	
	$group = array();
	$group []= $error;
	$group []= $color;
	
	$errors []= $group; 
}

function showErrors()
{
	global $errors;
	
	for($i = 0; $i < count($errors); $i++)
	{
		$group = $errors[$i];
		$error = $group[0];
		$color = $group[1];
		echo "<span style=\"color:$color\">$error</span>";
	}
}

function hasErrors()
{
	global $errors;
	
	return count($errors) > 0;
}

function redirect($page)
{
	header('Location: ' . $page);
}

$conn = mysqli_init();
function connect_sql()
{
	//$conn = mysqli_connect("127.0.0.1", "root", "password");
	global $conn;
	$conn = mysqli_connect("localhost", "root", "9494sTr@t3gy!", "cardgame");
	if(!$conn)
	{
		error_log("could not connect to the mysql database with error: " . mysqli_errno($conn));
		die;
	}
	return $conn;
}

function query($query)
{
	global $conn;
	$res = mysqli_query($conn, $query);
	if(!$res)
		error_log("query: {$query} returned error: " . mysqli_error($conn) . ":" . mysqli_errno($conn));
	return $res;
}

function fetch_assoc($query)
{
	return mysqli_fetch_assoc($query);
}

function fetch_array($query)
{
	return mysqli_fetch_array($query);
}

function fetch_row($query)
{
	return mysqli_fetch_row($query);
}

function num_rows($res)
{
	return mysqli_num_rows($res);
}

function insert_id()
{
	global $conn;
	return mysqli_insert_id($conn);
}

function close_sql()
{
	global $conn;
	if(isset($conn))
	{
		mysqli_close($conn);
	}
	unset($conn);
}

function escape($string)
{
	global $conn;
	return mysqli_real_escape_string($conn, $string);
}

function generateRandomString($length = 32) 
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}



?>