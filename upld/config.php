<?php
/////////////////////////////////////////////////////////
//Google Hack Honeypot v1.1
//Configuration File
//http://ghh.sourceforge.net - many thanks to SourceForge
/////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////
//Copyright (C) 2005 GHH Project
//
//This program is free software; you can redistribute it and/or modify 
//it under the terms of the GNU General Public License as published by 
//the Free Software Foundation; either version 2 of the License, or 
//(at your option) any later version.
//
//This program is distributed in the hope that it will be useful, 
//but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY 
//or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License 
//for more details.
//
//You should have received a copy of the GNU General Public License along 
//with this program; if not, write to the 
//Free Software Foundation, Inc., 
//59 Temple Place, Suite 330, 
//Boston, MA 02111-1307 USA
////////////////////////////////////////////////////////

////////////////////////////////////////////////////////
//Begin Global Configuration Section 
////////////////////////////////////////////////////////

////////////////////////////////////////////////////////
//Logging (CSV or MySQL?)
////////////////////////////////////////////////////////
//CSV, MySQL, or xml-rpc?
$LogType = 'xmlrpc'; //Enter 'CSV', 'xmlrpc', or 'MySQL', then complete the relevant configuration section below

	//CSV Config
	$Filename = ''; //yourORIGINALfilename.txt (this better be original!!!!!) This is where logs are being written to.

	//MySQL Config
	$Owner = ''; //There may be many people logging in the remote database, so who are you? This will determine which logs are yours.
	$Server = ''; //MySQL Server (IP, IP:port, IP:port/path/to/socket)
	$DBUser = ''; //MySQL Username
	$DBPass = ''; //DB Password
	$DBName = ''; //Default ghh (name of the database)

	//XML-rpc Config
	$XMLhost = ''; //the hostname for the site that has xmlrpc example ghh.sf.net
	$XMLport = ''; //the port that xmlrpc is running on this is most likely port 80, if you use something other then 80 we will try to connect with https.
	$XMLresource = ''; //the "path" to the xmlrpc server such as '/ghh/xmlrpc/server.php'
	$XMLident = ''; //the string that identfies this host to the xml server
	$XMLmagic = ''; //the magic string that goes along with the host like a password
	$XMLrpc = 'xml.inc'; //the file to include that has xmlrpc (name it something other then xmlrpc.inc or .php)
	$XMLhttps = false;
	
	$XMLproxy = false;  //if you are behind a proxy set this to true
	$XMLproxyHost = ""; //the host that you need to go through for the proxy
	$XMLproxyPort = 0; // the port for your proxy

////////////////////////////////////////////////////////
//End Global Configuration Section
////////////////////////////////////////////////////////

////////////////////////////////////////////////////////
//Begin Housekeeping Section
////////////////////////////////////////////////////////
$Signature = array();
$DateTime = date("m-d-Y h:i:s A");
$Attack = "";
$HoneypotName = "";
$Log = "";
error_reporting(0);
$downloadedFile = null;
////////////////////////////////////////////////////////
//End Housekeeping Section
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
//End Housekeeping Section
////////////////////////////////////////////////////////

////////////////////////////////////////////////////////
//Begin Basic Security Section (This makes the configuration file a honeypot itself to prevent fingerprinting, no transparent links to this file please.)
////////////////////////////////////////////////////////
//Checks for $RegisterGlobals so $Honeypot cannot be bypassed

if(ini_get("register_globals")==1)
{
	if (strstr($_SERVER["REQUEST_URI"], "config.php"))
		unset($Honeypot);
}

if(!isset($Honeypot)){
	//Set Config honeypot's name
	$HoneypotName = "CONFIG.PHP";
	//Attack Acquisition Section
	$Attack = getAttacker();
	//Determine Standard Signatures
	$Signature = standardSigs($Attack, "none");
	//Build Log
	writeLog($Owner, $HoneypotName, $DateTime, $Attack, $Signature, $LogType, $Filename, $DBName, $DBUser, $DBPass, $Server);
	exit;
}
////////////////////////////////////////////////////////
//End Basic Security Section
////////////////////////////////////////////////////////


////////////////////////////////////////////////////////
//Begin Functions Section
//Contains core functions of GHH which are shared by all honeypot files.
//Function list: getAttacker(),standardSigs(),sanitize(), writelog(), buildLog()
////////////////////////////////////////////////////////


function getFullHeaders() {
        return var_export($_SERVER, true) . var_export($_GET, true) . var_export($_POST, true);
}


//downloadHTTPfile($host, $port, $resorce)
//this downloads the first 500k of a file and then puts the base64 of that in the global varable downloadedFile
function downloadHTTPfile($host, $port, $resorce) {

	$connection = fsockopen($host, $port);
	$resorce = preg_replace($resorce, '//', '/?(.*)/');
	//set the headers to look like wget
	$request  = "GET $resorce HTTP/1.1\r\n";
	$request .= "Host: $host\r\n";
	$request .= "user-agent: Wget/1.10.2\r\n";
	$request .= "accept: */*\r\n";
	$request .= "content-length: 0\r\n";
	$request .= "Connection: Close\r\n\r\n";
	
	//did we fail to connect
	if (!$connection)
		return ;
	
	fwrite($connection, $request);
	$buffer = "";
	while (!feof($connection) && strlen($buffer) < 1024 * 500)
	{
			$in =  fgets($connection, 4096);
			$buffer .= $in;
	}
	preg_replace($buffer, '//', '/'.chr(13).chr(10).chr(0).chr(10).'/');
	if (strlen($buffer) > 0)
		$GLOBALS['downloadedFile'] = base64_encode($buffer);
}

//getAttacker() returns attacker profile
function getAttacker() {
	$Attacker = array();
	$Attacker['IP'] = isset($_SERVER['REMOTE_ADDR']) ? sanitize($_SERVER['REMOTE_ADDR'] . getProxy()) : null;
	$Attacker['request'] = isset($_SERVER['REQUEST_URI']) ? sanitize($_SERVER['REQUEST_URI']) : null;
	$Attacker['referer'] = isset($_SERVER['HTTP_REFERER']) ? sanitize($_SERVER['HTTP_REFERER']) : null;
	$Attacker['agent'] = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize($_SERVER['HTTP_USER_AGENT']) : null;
	$Attacker['accept'] = isset($_SERVER['HTTP_ACCEPT']) ? sanitize($_SERVER['HTTP_ACCEPT']) : null;
	$Attacker['charset'] = isset($_SERVER['HTTP_ACCEPT_CHARSET']) ? sanitize($_SERVER['HTTP_ACCEPT_CHARSET']) : null;
	$Attacker['encoding'] = isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? sanitize($_SERVER['HTTP_ACCEPT_ENCODING']) : null;
	$Attacker['language'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? sanitize($_SERVER['HTTP_ACCEPT_LANGUAGE']) : null;
	$Attacker['connection'] = isset($_SERVER['HTTP_CONNECTION']) ? sanitize($_SERVER['HTTP_CONNECTION']) : null;
	$Attacker['keep_alive'] = isset($_SERVER['HTTP_KEEP_ALIVE']) ? sanitize($_SERVER['HTTP_KEEP_ALIVE']) : null;
	$Attacker['headers'] = sanitize(getFullHeaders());
	return $Attacker;
}


//getProxy() Detects a proxy. If the real IP is available, it's logged.
function getProxy() {
	$proxy = array();
	
	if(isset($_SERVER['HTTP_CLIENT_IP']))
		$proxy = array_merge($proxy, explode(',', $_SERVER['HTTP_CLIENT_IP']));
	
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		$proxy = array_merge($proxy, explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
	
	if (!count($proxy) > 0) {
		if (isset($_SERVER["HTTP_PROXY_CONNECTION"]) || isset($_SERVER["HTTP_VIA"])) {
			return "::Proxy Detected";
		}
		return "";
	}
	$proxy = implode('::', $proxy);
	return '::' . $proxy;
}

//standardSigs() returns default signatures, if any are found.
function standardSigs($Attacker, $SafeReferer) {
	$results = array();

	//Was the site crawled?
	if($Attacker['referer'] == $SafeReferer && $SafeReferer != "") {
		$results[] = "Spider Detected"; 
	}
	//No referer found. The "only way" to reach the page is with a referer. Referers help us determine how we were attacked.
	if($Attacker['referer'] == "") {
		$results[] = "No Referer";
	}

	//Determine if an KNOWN engine was used
	$Engines = array ('lycos.com', 'google.com', 'yahoo.com', 'altavista.com', '209.202.248.202', '216.239.37.99', '216.109.112.135', '66.218.71.198');
	foreach ($Engines as $string) {
		if (strstr ($Attacker['referer'], $string)) {
			$results[] = "Known Search Engine: " . $string;
			break;
		}
	}
	return $results;
}

function sanitize_system_string($string, $min='', $max='')
{
  $pattern = '/(;|\||`|>|<|&|^|"|'."\n|\r|'".'|{|}|[|]|\)|\()/i'; // no piping, passing possible environment variables ($),
                               // seperate commands, nested execution, file redirection,
                               // background processing, special commands (backspace, etc.), quotes
                               // newlines, or some other special characters
  $string = preg_replace($pattern, '', $string);
  $string = preg_replace('/\$/', '\\\$', $string); //LART note removed " before and after
  $len = strlen($string);
  if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
    return FALSE;
  return $string;
}

//Sanitize returns a version of the string passed that does not have any charecters that could cause problems with sql or html.
function sanitizeHtmlandSql($string) {
	if (strtolower($LogType) != "xmlrpc") {
		$pattern[0] = '/\&/';
		$pattern[1] = '/</';
		$pattern[2] = "/>/";
		$pattern[3] = '/"/';
		$pattern[4] = "/'/";
		$pattern[5] = "/%/";
		$pattern[6] = '/\(/';
		$pattern[7] = '/\)/';
		$pattern[8] = '/\+/';
		$pattern[9] = '/-/';
		$replacement[0] = '&#26;';
		$replacement[1] = '&lt;';
		$replacement[2] = '&gt;';
		$replacement[3] = '&quot;';
		$replacement[4] = '&#39;';
		$replacement[5] = '&#37;';
		$replacement[6] = '&#40;';
		$replacement[7] = '&#41;';
		$replacement[8] = '&#43;';
		$replacement[9] = '&#2d;';
	} else {
		$pattern[0] = '/ /';
		$replacement[0] = ' ';
	}
	$clean = substr(preg_replace($pattern, $replacement, $string),0,3000);
	return $clean;
}


//sanitize() returns $_SERVER['REQUEST_URI'] stripped of any illegal chars that may corrupt the log when parsed into HTML.  500 character limit per field.
function sanitize($string, $maxlen=250) {
	if (strtolower($LogType) != "xmlrpc") {
		$pattern[0] = '/\&/';
		$pattern[1] = '/</';
		$pattern[2] = "/>/";
		$pattern[3] = '/\n/';
		$pattern[4] = '/"/';
		$pattern[5] = "/'/";
		$pattern[6] = "/%/";
		$pattern[7] = '/\(/';
		$pattern[8] = '/\)/';
		$pattern[9] = '/\+/';
		$pattern[10] = '/-/';
		$pattern[11] = '/,/';
		$replacement[0] = '&#26;';
		$replacement[1] = '&lt;';
		$replacement[2] = '&gt;';
		$replacement[3] = '';
		$replacement[4] = '&quot;';
		$replacement[5] = '&#39;';
		$replacement[6] = '&#37;';
		$replacement[7] = '&#40;';
		$replacement[8] = '&#41;';
		$replacement[9] = '&#43;';
		$replacement[10] = '&#45;';
		$replacement[11] = '&#44;';
	} else {
		$pattern[0] = '/ /';
		$replacement[0] = ' ';
	}
	$clean = substr(preg_replace($pattern, $replacement, $string),0,$maxlen);
	return $clean;
}

//writeLog() returns nothing.  Writes results of captured honeypot attack to disk or database
function writeLog($Owner, $HoneypotName, $DateTime, $Attack, $Signature, $LogType, $Filename, $DBName, $DBUser, $DBPass, $Server) {
$SigLog = '';

	if(strtolower($LogType) == "mysql") {
		//Loop out discovered signatures, separated by ";" to maintain CSV array sizes
		foreach ($Signature as $string)
			$SigLog .= $string . ';';
		//Host, username, password is pulled from configuration file.
		$link = mysql_connect($Server, $DBUser, $DBPass);
		if (!$link) {
			die();
		}
		//Database name is pulled from configuration file.
		$db = mysql_select_db($DBName);

		$query = "INSERT INTO logs ( `Owner`, `Tripped`, `TimeOfAttack`, `Host`, `RequestURI`, `Referrer`, `Accepts`, `AcceptsCharset`, `AcceptLanguage`, `Connection`, `keepalive`, `UserAgent`, `Signatures`, `Headers`)
VALUES ('" . $Owner . "', '" . $HoneypotName . "', NOW( ), '" . $Attack['IP'] . "', '" . $Attack['request'] . "' , '" . $Attack['referer'] . "', '" . $Attack['accept'] . "', '" . $Attack['charset'] . "', '" . $Attack['language'] . "', '" . $Attack['connection'] . "', '" . $Attack['keep_alive'] . "', '" . $Attack['agent'] . "', '" .$SigLog . "', '" . $Attack['headers'] . "');";
		
		$result = mysql_query($query, $link);
		mysql_close($link);

	}else if (strtolower($LogType) == "xmlrpc") {
		include($GLOBALS['XMLrpc']);
		//make a connection to the xmlrpc server
		$server = new xmlrpc_client($GLOBALS['XMLresource'], $GLOBALS['XMLhost'], $GLOBALS['XMLport']);
		//add xmlrpc debugging set to 1
		$server->setDebug(0);
		
		if ($GLOBALS['XMLproxy'])
			$server->setProxy($GLOBALS['XMLproxyHost'], $GLOBALS['XMLproxyPort']);
		
		//add ident and magic to the array
		$Attack['Ident'] = $GLOBALS['XMLident'];
		$Attack['Magic'] = $GLOBALS['XMLmagic'];
		
		//if we downloaded a file lets send it to our cental logging server
		$Attack['downloadedFile'] = $GLOBALS['downloadedFile'];
		
		//add the last few vars to the array
		foreach ($Signature as $string)
			$SigLog .= $string . ';';
		
		$Attack['SigLog'] = $SigLog;
		$Attack['Name'] = $HoneypotName;
		
		//convert our array and make a xmlrpc message to send out
		$XMLattack =new xmlrpcmsg('ghh.log', array(php_xmlrpc_encode($Attack)));
		//send the message
		if (!$GLOBALS['XMLhttps']){
			$responce = $server->send($XMLattack, 0, "http");
		} else {
			$server->setSSLVerifyPeer(false);
			$responce = $server->send($XMLattack, 0, "https");
		}
		
		
	}
	else { //Type is CSV
		$Log = "";
		$Log = $HoneypotName . "," . $DateTime . "," . $Attack['IP'] . "," . $Attack['request'] . "," . $Attack['referer'] . "," . $Attack['accept'] . "," . $Attack['charset'] . "," . $Attack['encoding'] . "," . $Attack['language'] . "," . $Attack['connection'] . "," . $Attack['keep_alive'] . "," . $Attack['agent'] . ",";
 
		//Loop out discovered signatures, separated by ";" to maintain CSV array sizes
		foreach ($Signature as $string)
			$Log .= $string . ';';
		//New line
		$Log .= "\n";

		if (is_writable($Filename)) {

			//Checks to see if $Filename exists, if not attempts to create file.
			if (!$handle = fopen($Filename, 'a')) {
				exit;
			}
			if (fwrite($handle, $Log) === FALSE) {
				exit;
			}
			fclose($handle);
		} 
		else {
			exit;
		}
	}
}
////////////////////////////////////////////////////////
//End Functions Section
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
//End of config.php
////////////////////////////////////////////////////////
?>

