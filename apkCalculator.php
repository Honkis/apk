<?php
namespace apkCalculator;
include_once('apkDatabase.php');

class apkCalculator
{

  private $pdo;

  public function __construct() {

    // Get pdo object
    $dbconn = new apkDatabase;
    $this->pdo = $dbconn->dbconnect();

  }

  /**
   * Parse the XML article feed and extract data
   * @return void
   */
  public function load_xml_data($src_location) {

    // Load the xml file
    $dom = new \DOMDocument();
    if ($dom->load($src_location) === false) {
      echo "something went wrong with loading the XML file.";
    }

    $this->article_parser($dom);
  }

  /**
   * Parse the XML article feed and extract data
   * @return void
   */
  public function article_parser($dom) {

    $xpath = new \DOMXPath($dom);
    $articles = $xpath->query("//artikel");
    echo $articles->length . "<br/>";
    $data = [];

    // If nodelist is empty break(assume no more ads)
    if ($articles->length === 0) {
      echo "Articles list is empty!";
      return;
    }

    // Loop through article and extract data
    foreach ($articles as $article) {
      $data['artikelid'] = $article->getElementsByTagName('Artikelid')->item(0)->nodeValue;
      $data['varunr'] = $article->getElementsByTagName('Varnummer')->item(0)->nodeValue;
      $data['namn'] = $article->getElementsByTagName('Namn')->item(0)->nodeValue;
      $data['namn2'] = $article->getElementsByTagName('Namn2')->item(0)->nodeValue;
      @$data['varugrupp'] = $article->getElementsByTagName('Varugrupp')->item(0)->nodeValue;
      $data['forpackning'] = $article->getElementsByTagName('Forpackning')->item(0)->nodeValue;
      $data['pris'] = $article->getElementsByTagName('Prisinklmoms')->item(0)->nodeValue;
      $data['alkoholhalt'] = $article->getElementsByTagName('Alkoholhalt')->item(0)->nodeValue;
      $data['volym'] = $article->getElementsByTagName('Volymiml')->item(0)->nodeValue;

      // Convert raw data to correct format
      $data = $this->convert_raw_data($data);

      // Get the apk and round the apk value to two decimal points
      $data['apk'] = $this->calculate_apk($data['pris'], $data['volym'], $data['alkoholhalt']);
      round($data['apk'] * 100.0) / 100.0;

      // Don't send to db if in these categories
  		if(stripos($data['varugrupp'], 'alkoholfritt') !== false ||
          stripos($data['varugrupp'], 'röda') !== false ||
          stripos($data['varugrupp'], 'vita') !== false
        ) {
  	      // Insert data into the database
          echo "continuing"; 
  	      continue;
  		}

      $this->article_db_insert($data);
    }
  }

  /**
   * Clean the data
   * @return double
   */
  public function convert_raw_data($data) {

    // Remove procent sign in alcohol level string
    $data['alkoholhalt'] = str_replace("%", "", $data['alkoholhalt']) / 100;

    // Remove commas from price
    $data['pris'] = str_replace(",", "", $data['pris']);

    return $data;
  }

  /**
   * Calculate the APK
   * @return double
   */
  public function calculate_apk($price, $volume, $alcohol_level) {
    return ($volume * $alcohol_level) / $price;
  }

  /**
   * Insert the article data into the database
   * @return boolean
   */
  public function article_db_insert($data) {

    $stmt = $this->pdo->prepare("INSERT INTO artiklar (artikelid, varunr, namn, namn2, varugrupp, forpackning, pris, alkoholhalt, volym, apk) VALUES (:artikelid, :varunr, :namn, :namn2, :varugrupp, :forpackning, :pris, :alkoholhalt, :volym, :apk)");

    $stmt->bindParam(':artikelid', $data['artikelid']);
    $stmt->bindParam(':varunr', $data['varunr']);
    $stmt->bindParam(':namn', $data['namn']);
    $stmt->bindParam(':namn2', $data['namn2']);
    $stmt->bindParam(':varugrupp', $data['varugrupp']);
    $stmt->bindParam(':forpackning', $data['forpackning']);
    $stmt->bindParam(':pris', $data['pris']);
    $stmt->bindParam(':alkoholhalt', $data['alkoholhalt']);
    $stmt->bindParam(':volym', $data['volym']);
    $stmt->bindParam(':apk', $data['apk']);

    $status = $stmt->execute();
    echo $status . "<br/>";
    if ($status === false) {
      echo "NÅTT GICK FEL!";
    }
    return $status;
  }

  /**
   * Insert the article data into the database
   * @return void
   */
  public function update_database() {

    // Get the live XML file as a DOMdocument containing the articles
    $status = $this->get_articles_file();

    if ($status === false) {
      echo " Won't insert into db since errors in getting live xml feed.";
      return;
    }

    // Truncate the artiklar table
    $stmt = $this->pdo->prepare("SET FOREIGN_KEY_CHECKS = 0; TRUNCATE apk.artiklar; SET FOREIGN_KEY_CHECKS = 1;)");

    $status = $stmt->execute();

    echo $status . "<br/>";
    if ($status === false) {
      echo "NÅTT GICK FEL!";
      return;
    }

    $stmt->closeCursor();

    // Load and insert the new xml data from today
    $this->load_xml_data(__DIR__.'/rawfeeds/artiklar-'.date('Y-m-d').'.xml');

  }


  /**
   * Get the articles file from systembolaget
   * @return void
   */
  private function get_articles_file() {
    $url = 'http://www.systembolaget.se/Assortment.aspx?Format=Xml';

    $ch = curl_init();
    $curl_log = fopen('curl_log.txt', 'a');

    curl_setopt_array($ch, array(
      CURLOPT_HEADER          => FALSE,
      CURLOPT_SSL_VERIFYPEER  => FALSE,
      CURLOPT_COOKIESESSION   => TRUE,
      CURLOPT_FOLLOWLOCATION  => TRUE,
      CURLOPT_RETURNTRANSFER  => TRUE,
      CURLOPT_VERBOSE         => 1,
      CURLOPT_STDERR          => $curl_log,
      CURLOPT_URL             => $url
      ));

    $results = curl_exec($ch);

    // Check if any error occurred
    if(!curl_errno($ch))
    {
      // Print stuff
      $info = curl_getinfo($ch);
      echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'] . ' with http code: ' . $info['http_code'];

      // Save the XML file to disk
      if (file_put_contents(__DIR__.'/rawfeeds/artiklar-'.date('Y-m-d').'.xml', $results) !== false) {
           echo ' Wrote curled xml to file.';
      } else {
           echo ' Failed to write curled xml to file';
           return false;
      }
    } else {
      echo " Curl error, couldnt get file.";
      return false;
    }

    curl_close($ch);

    return true;
  }
}