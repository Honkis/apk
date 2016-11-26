<?php
namespace apkCalculator;


class apkDatabase
{

  public function __construct() {
  }

  public function dbconnect() {

    // Create pdo object
    try {
      $pdo = new \PDO(
        'mysql:host=localhost;dbname=apk',
        'root',
        '',
        array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
      );
      $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      print "Error in pdo obj creation!: " . $e->getMessage() . "<br/>";
      die();
    }

    return $pdo;

  }
}
