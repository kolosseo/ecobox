<?php

define("DBUSER","root");
define("DBPASS","123456");
define("DBNAME","my_little_eco_box");
define("DBHOST","localhost");

$bullet_chart_values = array(
	"title",
	"subtitle",
	"ranges",
	"measures",
	"markers",
);

//$this->customer_id="123456";

/*
class MysqlClass {
  // parametri per la connessione al database
  private $nomehost = "localhost";     
  private $nomeuser = "root";          
  private $password = "123456"; 
          
  // controllo sulle connessioni attive
  private $attiva = false;
 
  // funzione per la connessione a MySQL
  public function connetti()
  {
   if(!$this->attiva)
   {
    $connessione = mysql_connect($this->nomehost,$this->nomeuser,$this->password);
       }else{
        return true;
       }
    }
}  

public function disconnetti() {
        if($this->attiva)
        {
                if(mysql_close())
                {
         $this->attiva = false; 
             return true; 
                }else{
                        return false; 
                }
        }
 }


$data = new MysqlClass();
$data->connetti();
*/

function open_connection() {
	$my_sqli = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
 
	// verifica dell'avvenuta connessione
	if (mysqli_connect_errno()) {
           // notifica in caso di errore
        echo "Errore in connessione al DBMS: ".mysqli_connect_error();
           // interruzione delle esecuzioni i caso di errore
        exit();
 
	}
	else {
           // notifica in caso di connessione attiva
        //echo "Connessione avvenuta con successo".PHP_EOL;
	}
	return $my_sqli;
}

function do_query($settings = null) {
	if($settings == null){return false;}
	$content = $settings["dbconn"]->query($settings['query']);
	if (!$content) {
		echo $settings["query"];
		return false;
}
	$rows = $content->fetch_array(MYSQLI_NUM);
	return !empty($rows) ? $rows : array();
}
 
class stat {
	var $dbconn;
	var $customer_id;
	function stat($customer_id = "") {
		$this->customer_id = $customer_id;
		$this->dbconn = open_connection();
	}
	function lowest_last_week() {
		// lowest last week
		$query = "SELECT customer_pa,reading_datetime FROM mleb_consumption WHERE customer_id=".$this->customer_id." AND DATEDIFF(CURDATE(), reading_datetime) < 7 ORDER BY customer_pa ASC LIMIT 1";
		$result = $this->dbconn->query($query);
		$rows = do_query(array("dbconn" => $this->dbconn, "query" => $query));
		return $rows[0];
//		$result->close();
	}
	function highest_last_week() {
		// highest last week
		$query = "SELECT customer_pa,reading_datetime FROM mleb_consumption WHERE customer_id=".$this->customer_id." AND DATEDIFF(CURDATE(), reading_datetime) < 7 ORDER BY customer_pa DESC LIMIT 1";
		$result = $this->dbconn->query($query);
		$rows = do_query(array("dbconn" => $this->dbconn, "query" => $query));
		return $rows[0];
	}
	function average_last_week() {
		// average last week
		$query = "SELECT AVG(customer_pa) FROM mleb_consumption WHERE customer_id=".$this->customer_id." AND DATEDIFF(CURDATE(), reading_datetime) < 7 ORDER BY customer_pa ASC LIMIT 1";
		$result = $this->dbconn->query($query);
		$rows = do_query(array("dbconn" => $this->dbconn, "query" => $query));
		return $rows[0];
	}
	function lowest_actual_month() {
		// lowest actual month
		$query = "SELECT customer_pa,reading_datetime FROM mleb_consumption WHERE customer_id=".$this->customer_id." AND (reading_datetime BETWEEN DATE_FORMAT(NOW(), '%Y-%m-01') AND NOW()) ORDER BY customer_pa ASC LIMIT 1";
		$result = $this->dbconn->query($query);
		$rows = do_query(array("dbconn" => $this->dbconn, "query" => $query));
		return $rows[0];
	}
	function highest_actual_month() {
		// highest actual month
		$query = "SELECT customer_pa,reading_datetime FROM mleb_consumption WHERE customer_id=".$this->customer_id." AND (reading_datetime BETWEEN DATE_FORMAT(NOW(), '%Y-%m-01') AND NOW()) ORDER BY customer_pa DESC LIMIT 1";
//		$result = $this->dbconn->query($query);
		$rows = do_query(array("dbconn" => $this->dbconn, "query" => $query));
		return $rows[0];
	}
	function average_actual_month() {
		// average actual month
		$query = "SELECT AVG(customer_pa) FROM mleb_consumption WHERE customer_id=".$this->customer_id." AND (reading_datetime BETWEEN DATE_FORMAT(NOW(), '%Y-%m-01') AND NOW()) ORDER BY customer_pa ASC LIMIT 1";
		$result = $this->dbconn->query($query);
		$rows = do_query(array("dbconn" => $this->dbconn, "query" => $query));
		return $rows[0];
	}
	function lowest_last_24h() {
		// lowest last 24h
		$query = "SELECT customer_pa,reading_datetime FROM mleb_consumption WHERE customer_id=".$this->customer_id." AND reading_datetime > DATE_SUB(NOW(), INTERVAL 24 HOUR) ORDER BY customer_pa ASC LIMIT 1";
		$result = $this->dbconn->query($query);
		$rows = do_query(array("dbconn" => $this->dbconn, "query" => $query));
		return $rows[0];
	}
	function highest_last_24h() {
		// highest last 24h
		$query = "SELECT customer_pa,reading_datetime FROM mleb_consumption WHERE customer_id=".$this->customer_id." AND reading_datetime > DATE_SUB(NOW(), INTERVAL 24 HOUR) ORDER BY customer_pa DESC LIMIT 1";
		$result = $this->dbconn->query($query);
		$rows = do_query(array("dbconn" => $this->dbconn, "query" => $query));
		return $rows[0];
	}
	function average_last_24h() {
		// average last 24h
		$query = "SELECT AVG(customer_pa) FROM mleb_consumption WHERE customer_id=".$this->customer_id." AND reading_datetime > DATE_SUB(NOW(), INTERVAL 24 HOUR) ORDER BY customer_pa ASC LIMIT 1";
//		$result = $this->dbconn->query($query);
		$rows = do_query(array("dbconn" => $this->dbconn, "query" => $query));
		return $rows[0];
	}
	function actual() {
		// average last 24h
		$query = "SELECT customer_pa FROM mleb_consumption WHERE customer_id=".$this->customer_id." ORDER BY reading_datetime DESC LIMIT 1";
//		$result = $this->dbconn->query($query);
		$rows = do_query(array("dbconn" => $this->dbconn, "query" => $query));
		return $rows[0];
	}
/*
if($result->num_rows >0 ) {
    while($row = $result->fetch_array(MYSQLI_NUM)) {
        echo $row[0];
        echo "<br />n";
    }
}
*/
// liberazione delle risorse occupate dal risultato
}

function get_chart($bullet_chart) {
	return json_encode($bullet_chart);
}

$mystat = new stat("123456");

echo get_chart(array(
	"title" => "Monthly consumption",
	"subtitle" => "foo",
	"ranges" => array(
		$mystat->lowest_actual_month(),
		$mystat->average_actual_month(),
		$mystat->highest_actual_month(),
	),
	"measures" => array(
		$mystat->actual(),
	),
	"markers" => array(
		round($mystat->average_actual_month() * 1.05),
	)
));

//$mystat->customer_id = "123456";
//echo $mystat->lowest_last_week();
//echo $mystat->highest_last_week();
//echo $mystat->average_last_week();
//echo $mystat->lowest_actual_month();
//echo $mystat->highest_actual_month();
//echo $mystat->average_actual_month();
//echo $mystat->lowest_last_24h();
//echo $mystat->highest_last_24h();
//echo $mystat->average_last_24h();
// chiusura della connessione
//$this->dbconn->close();
