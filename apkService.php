<?php
namespace apkCalculator;
include_once('apkDatabase.php');

class apkService
{

  private $pdo;

  public function __construct() {

    // Get pdo object
    $dbconn = new apkDatabase;
    $this->pdo = $dbconn->dbconnect();
  }


  /**
  * Get articles depending on category and a limit
  * @param  [string] $category [öl, vin, ]
  * @param  [int] $limit    [description]
  * @return [array]           [description]
  */
  public function get_varugrupp($category, $limit) {

    $stmt = $this->pdo->prepare("SELECT * FROM artiklar WHERE varugrupp LIKE CONCAT('%', ?, '%') ORDER BY apk DESC LIMIT ?");

    $stmt->bindParam(1, $category, \PDO::PARAM_STR);
    $stmt->bindParam(2, $limit, \PDO::PARAM_INT);

    $status = $stmt->execute();
    if ($status === false) {
      echo "NÅTT GICK FEL!";
    }

    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    return $data;
  }
  
  /**
  * Get articles depending on category and a limit
  * @param  [array] options
  * @return [array]           [description]
  */
  public function get_articles($options) {

    $stmt = $this->pdo->prepare("SELECT * FROM artiklar WHERE varugrupp LIKE CONCAT('%', ?, '%') AND volym <= 3000 ORDER BY apk DESC LIMIT 10");

    // Check if category is toplist
    if (isset($options['category']) && $options['category'] == 'toplist') {
      $wildcard = "%";
      $stmt->bindParam(1, $wildcard, \PDO::PARAM_STR);
    } else {
      $stmt->bindParam(1, $options['category'], \PDO::PARAM_STR);      
    }

    $status = $stmt->execute();
    if ($status === false) {
      return "NÅTT GICK FEL!" . $this->pdo->errorInfo();
    }

    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $data = $this->construct_product_urls($data);

    return $data;
  }

  /**
  * Get more articles
  * @param  [array] options
  * @return [array]           [description]
  */
  public function get_more_articles($options) {

    $stmt = $this->pdo->prepare("SELECT * FROM artiklar WHERE varugrupp LIKE CONCAT('%', ?, '%') AND volym <= 3000 ORDER BY apk DESC LIMIT 10 OFFSET ?");

    // Check if category is toplist
    if (isset($options['category']) && $options['category'] == 'toplist') {
      $wildcard = "%";
      $stmt->bindParam(1, $wildcard, \PDO::PARAM_STR);
    } else {
      $stmt->bindParam(1, $options['category'], \PDO::PARAM_STR);      
    }

    // Set the offset param 2
    $stmt->bindParam(2, $options['offset'], \PDO::PARAM_INT);
    
    $status = $stmt->execute();
    if ($status === false) {
      return "NÅTT GICK FEL!" . $this->pdo->errorInfo();
    }

    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $data = $this->construct_product_urls($data);

    return $data;
  }

  /**
  * Get articles depending on category and a limit
  * @param  [array] options
  * @return [array]           [description]
  */
  public function get_other_articles($options) {

    $stmt = $this->pdo->prepare("SELECT * FROM artiklar WHERE varugrupp NOT LIKE '%öl%' AND varugrupp NOT LIKE '%cider%' AND varugrupp NOT LIKE '%rött vin%' AND varugrupp NOT LIKE '%vitt vin%' AND varugrupp NOT LIKE '%rosé%' AND varugrupp NOT LIKE '%mousserande vin%' AND varugrupp NOT LIKE '%whisky%' AND varugrupp NOT LIKE '%sake%' AND volym <= 3000 order by apk desc limit 10");

    $status = $stmt->execute();
    if ($status === false) {
      return "NÅTT GICK FEL!" . $this->pdo->errorInfo();
    }

    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $data = $this->construct_product_urls($data);

    return $data;
  }

  /**
  * Get articles with worst apk value
  * @param  [array] options
  * @return [array]           [description]
  */
  public function get_worst_apk_articles() {

    $stmt = $this->pdo->prepare("SELECT * FROM artiklar WHERE volym <= 3000 AND namn NOT LIKE '%Tomfat%' order by apk asc limit 10");

    $status = $stmt->execute();
    if ($status === false) {
      return "NÅTT GICK FEL!" . $this->pdo->errorInfo();
    }

    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $data = $this->construct_product_urls($data);

    return $data;
  }





  
    /**
   * Get articles depending on category and a limit
   * @param  [string] $package [box, burk, ]
   * @param  [int] $limit    [description]
   * @return [array]           [description]
   */
  public function get_articles_package($category, $limit) {

    $stmt = $this->pdo->prepare("SELECT * FROM artiklar WHERE forpackning LIKE CONCAT('%', ?, '%') ORDER BY apk DESC LIMIT ?");

    $stmt->bindParam(1, $package, \PDO::PARAM_STR);
    $stmt->bindParam(2, $limit, \PDO::PARAM_INT);

    $status = $stmt->execute();
    if ($status === false) {
      echo "NÅTT GICK FEL!";
    }

    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    return $data;
  }

      /**
   * Get articles depending on category and a limit
   * @param  [string] $category [Öl, vin, ]
   * @param  [string] $category2 [vitt, rött,Ale, ]
   * @param  [int] $limit    [description]
   * @return [array]           [description]
   */
  public function get_articles_category_x2($category, $limit) {

    $stmt = $this->pdo->prepare("SELECT * FROM artiklar WHERE varugrupp LIKE CONCAT('%', ?, '%') AND varugrupp LIKE CONCAT('%', ?, '%') ORDER BY apk DESC LIMIT ?");

    $stmt->bindParam(2, $category, \PDO::PARAM_STR);
    $stmt->bindParam(1, $category2, \PDO::PARAM_STR);
    $stmt->bindParam(2, $limit, \PDO::PARAM_INT);

    $status = $stmt->execute();
    if ($status === false) {
      echo "NÅTT GICK FEL!";
    }

    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    return $data;
  }
      /**
   * Get articles depending on category and a limit
   * @param  [string] $package [burk, box,flaska, ]
   * @param  [string] $volym [750, 500,330, ]
   * @param  [int] $limit    [description]
   * @return [array]           [description]
   */
  public function get_articles_package_volym($category, $limit) {

    $stmt = $this->pdo->prepare("SELECT * FROM artiklar WHERE volym LIKE CONCAT('%', ?, '%') AND forpackning LIKE CONCAT('%', ?, '%') ORDER BY apk DESC LIMIT ?");

    $stmt->bindParam(2, $volym, \PDO::PARAM_STR);
    $stmt->bindParam(1, $package, \PDO::PARAM_STR);
    $stmt->bindParam(2, $limit, \PDO::PARAM_INT);

    $status = $stmt->execute();
    if ($status === false) {
      echo "NÅTT GICK FEL!";
    }

    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    return $data;
  }
      /**
   * Get articles depending on category and a limit
   * @return [array]           [description]
   */
  public function get_articles_common_categorys() {

    $stmt = $this->pdo->prepare("SELECT LEFT(`varugrupp`, LOCATE(',', `varugrupp`)-1) AS category FROM `artiklar` WHERE LOCATE(',', `varugrupp`)>0 GROUP BY LEFT(`varugrupp`, LOCATE(',', `varugrupp`)-1)");

    $status = $stmt->execute();
    if ($status === false) {
      echo "NÅTT GICK FEL!";
    }

    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    return $data;
  }
      /**
   * THIS DOES NOT WORK YET... DO THIS MANUALY!!!
   * @return [array]           [description]
   */
  public function get_articles_specific_categorys($category, $limit) {

    $stmt = $this->pdo->prepare("SELECT RIGHT(`varugrupp`, LOCATE(",", `varugrupp`)+1) FROM `artiklar` WHERE LOCATE(",", `varugrupp`)>0 GROUP BY varugrupp");

    $status = $stmt->execute();
    if ($status === false) {
      echo "NÅTT GICK FEL!";
    }

    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    return $data;
  }
       /**
   * Get articles depending on category and a limit
   * @return [array]           [description]
   */
  public function get_articles_volym($category, $limit) {

    $stmt = $this->pdo->prepare("SELECT volym FROM artiklar GROUP BY volym");

    $status = $stmt->execute();
    if ($status === false) {
      echo "NÅTT GICK FEL!";
    }

    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    return $data;
  }

   /**
   * Get articles depending on category and a limit
   * @return [array]           [description]
   */
  public function get_articles_top() {

    $stmt = $this->pdo->prepare("SELECT * FROM artiklar WHERE volym <= 3000 ORDER BY apk DESC LIMIT 10");

    $status = $stmt->execute();
    if ($status === false) {
      echo "NÅTT GICK FEL!";
    }

    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    $data = $this->construct_product_urls($data);

    return $data;
  }

  /**
  * Construct article urls to systembolagets webpage
  * @return [array]           [description]
  */
  private function construct_product_urls($data) {

    foreach ($data as $product => $value) {
      $varunr = filter_var($value['varunr'], FILTER_SANITIZE_STRING);
      $data[$product]['producturl'] = "http://www.systembolaget.se/Sok-dryck/Dryck/?varuNr=". $varunr;
    }

    return $data;
  }

}