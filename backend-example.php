<?php

include("../coordinator-select.php");
include("cdp_events.php");
include("messages.php");

$db_host = "localhost";
$db_user = "************";
$db_pass = "*************";
$db_name = "************";
$database = new PDO("mysql:host=". $db_host .";dbname=". $db_name .";charset=utf8", $db_user, $db_pass);

$event_id = $_GET['id'];
$event_info;
            
    if($event_id!=""&&strlen($event_id)==8)
    {
        $sql = "SELECT * FROM `salescache_events` WHERE `Council` = 'a1241000000bDoLAAU' and `Status` = 'Date Confirmed' and `isSFConnected` = '1' and `Date` >= CURDATE() and (`Registration` >= CURDATE() or `Registration`='')  ORDER BY `salescache_events`.`Date` ASC";
        $query = $database->query($sql);
        $events = $query->fetchAll(PDO::FETCH_ASSOC);

		foreach($events as $event)
		{
		    $eid = substr(hash('sha1', $event['sales_id']), 0, 8);
		    if($event_id==$eid)
		    {
		        $show = true;
		        $event_info = $event;
		        break;
		    }
		}
   
        
        if(!$show)
        {
            $txt = "Error displaying information. Please try again later";
        }
    }
    else
    {
        $txt = "Invalid website";
    }



?>

<html>
<head>
    <meta charset="utf-8">
    <title>Register - Higher Education/Govt/Nonprofit</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">

<style>
nav ul
{
    list-style: none;
    display: flex;
}
nav li
{
    font-size: 18px;
    margin: 15px;
    font-weight: bold;
}
@media (max-width: 1200px) 
{
	div#content 
	{
		margin: 120px 15% 0px 15% !important;
	}
}
@media (max-width: 700px) 
{
	div#content 
	{
		margin: 150px 5% 0px 5% !important;
	}
}
@media (max-width: 667px) 
{
    .logo 
    {
        padding-bottom: 0px !important;
    }
	header
	{
	    display: flex;
        flex-wrap: wrap;
        justify-content: center;
	}
	nav ul 
	{
        margin: 0px;
    }
}
body {
	margin: 0px;
	font-family: helvetica, arial, sans-serif;
	font-size: 10pt;
    background: #fdfdfd;
}
a:link {
	color: rgb(63, 146, 168);
	text-decoration: none;
}
a:visited {
	color: rgb(63, 146, 168);
	text-decoration:none;
}
a:hover {
	color: rgb(63, 146, 168);
	text-decoration: underline;
}
h1 {
	font-size: 1.8em;
	font-weight: 700;
	text-align: center;
}
header {
    background-color: #f8f9fb;
    border-bottom: 1px dotted #3a3a3c;
    margin-bottom: 15px;
    
    /*fix the div*/
    position: fixed;
    width: 100%;
    top: 0;
    right: 0;
}
.logo {
    float: left;
    padding: 10px;
}

.right {
    float: right;
}
/*div#header {
	background-color: #3F92A8;
	text-indent: 20%;
	border-bottom: 1px dotted white;
	height: 96px;
	position: relative;
	margin-left: -240px;
}
div#header div {
	font-size: 1.1em;
	color: #ffffff;
	position: absolute;
	top: 1em;
	float:right;
	left: 19%;
	white-space: nowrap;
}*/
div#content {
	margin: 0px 25%;
    
    /*fixed header */
    margin-top: 120px;
}
table.regtable {
	width: 100%;
}
table.regtable caption {
	font-weight: bold;
	font-size: 20px;
	border: 1px solid black;
	border-radius: 1ex;
	-moz-border-radius: 1ex;
	padding: 0.5ex;
	margin-top: 1.5ex;
	margin-bottom: 0.5ex;
}
table.regtable th {
	text-align: right;
	width: 40%;
	font-size: 12px;
}
.txindividualmembers {
	display: none;
}
.txbentable {
	display: none;
}
</style>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>

<script type="text/javascript">
function checkMember()
{
	var member = document.getElementById('member');
	var statecouncil = document.getElementById('statecouncil');  
	if(member.value=="YES")
	{
	    statecouncil.style.display = 'table-row';
	    document.getElementById('statecouncilselect').required = true;
	}
	else
	{
	    statecouncil.style.display = 'none';
	    document.getElementById('statecouncilselect').required = false;
	}
}
function togglemethod()
{
	var form = document.getElementById('registration');
	var checkinfo = document.getElementById('checkinfo');
	
	if(document.getElementById('cc_payby_cc') && document.getElementById('cc_payby_cc').checked)
	{
		if(checkinfo)
			checkinfo.style.display = 'none';
		ccfields = document.getElementsByClassName('ccfield');
		for(var i in ccfields)
		{
			if(ccfields[i].style) ccfields[i].style.display = 'table-row';
			ccfields[i].required = true;
		}
		invoicefields = document.getElementsByClassName('invoicefield');
		for(var i in invoicefields)
		{
			if(invoicefields[i].style) invoicefields[i].style.display = 'none';
			invoicefields[i].required = false;
		}
		ccfields1 = document.getElementsByClassName('creditfields');
		for(var i in ccfields1)
	    {
	        if(ccfields1[i].style) ccfields1[i].style.display = 'table-row';
			ccfields1[i].required = true;
	    }
		
		
	    //document.getElementById('payamount')[2].style.display = 'inherit';
	}
	else if(document.getElementById('cc_payby_ch') && document.getElementById('cc_payby_ch').checked)
	{
		if(checkinfo)
			checkinfo.style.display = 'table-row';
		ccfields = document.getElementsByClassName('ccfield');
		for(var i in ccfields)
		{
			if(ccfields[i].style) ccfields[i].style.display = 'none';
			ccfields[i].required = false;
		}
		
		invoicefields = document.getElementsByClassName('invoicefield');
		for(var i in invoicefields)
		{
			if(invoicefields[i].style) invoicefields[i].style.display = 'none';
			invoicefields[i].required = false;
		}
		ccfields1 = document.getElementsByClassName('creditfields');
		for(var i in ccfields1)
	    {
	        if(ccfields1[i].style) ccfields1[i].style.display = 'none';
			ccfields1[i].required = false;
	    }
		
		//set check to full amount and display none on installed payments
    	//document.getElementById('payamount').value="all";
    	//document.getElementById('payamount')[2].style.display = 'none';
    	checkAmount();
	
	}
	else if(document.getElementById('cc_payby_in') && document.getElementById('cc_payby_in').checked)
	{
		if(checkinfo)
			checkinfo.style.display = 'none';

		invoicefields = document.getElementsByClassName('invoicefield');
		for(var i in invoicefields)
		{
			if(invoicefields[i].style) invoicefields[i].style.display = 'table-row';
			invoicefields[i].required = true;
		}

		ccfields = document.getElementsByClassName('ccfield');
		for(var i in ccfields)
			if(ccfields[i].style) ccfields[i].style.display = 'none';
		
		ccfields1 = document.getElementsByClassName('creditfields');
		for(var i in ccfields1)
			ccfields1[i].required = false;
			
		ccfields1 = document.getElementsByClassName('creditfields');
		for(var i in ccfields1)
	    {
	        if(ccfields1[i].style) ccfields1[i].style.display = 'none';
			ccfields1[i].required = false;
	    }	
		//set invoice to full amount and display none on installed payments
    	//document.getElementById('payamount').value="all";
    	//document.getElementById('payamount')[2].style.display = 'none';
    	checkAmount();
	}
}
function autotab(element, length)
{
	if(element.value.length >= length && element.nextSibling != null)
		element.nextSibling.focus();
}
</script>  
    
<script>

$(function() 
{

  //copy and disable fields if user indicates billing is the same as registration address
   $('input[name=sameaddress]').click(function() 
   {
			var cb1 = $('input[name=sameaddress]').is(':checked');
        // $('input[name=cc_firstname], input[name=cc_lastname], input[name=cc_address], input[name=cc_city], select[name=cc_state], input[name=cc_zip]').prop('disabled',cb1);
         $('input[name=cc_firstname]').val($('input[name=firstname]').val());
		 $('input[name=cc_lastname]').val($('input[name=lastname]').val());
		 $('input[name=cc_address]').val($('input[name=address]').val());
		 $('input[name=cc_city]').val($('input[name=city]').val());
		 $('select[name=cc_state]').val($('select[name=state]').val());
		 $('input[name=cc_zip]').val($('input[name=zip]').val());
      });
	  
  //copy and disable fields if user indicates billing is the same as registration address
   $('input[name=samemailing]').click(function() 
   {
			var cb1 = $('input[name=sameaddress]').is(':checked');
        // $('input[name=si_firstname], input[name=si_lastname], input[name=si_address], input[name=si_city], select[name=si_state], input[name=si_zip]').prop('disabled',cb1);
         $('input[name=si_firstname]').val($('input[name=firstname]').val());
		 $('input[name=si_lastname]').val($('input[name=lastname]').val());
		 $('input[name=si_address]').val($('input[name=address]').val());
		 $('input[name=si_city]').val($('input[name=city]').val());
		 $('select[name=si_state]').val($('select[name=state]').val());
		 $('input[name=si_zip]').val($('input[name=zip]').val());
      });
});
</script>

</head>
<body>
<header>
   <div class="logo">
        <a href="*******" target="_blank" rel="noopener noreferrer"><img src="" title="" alt=""></a>
    </div>
    <div class="right">
		<nav>
        	<ul>
        		<li><a href="/terms-and-conditions/" target="_blank" rel="noopener noreferrer">Terms and Conditions</a></li>
        		<li><a href="/curriculum/" target="_blank" rel="noopener noreferrer">Curriculum</a></li>
        	</ul>
        </nav>
    </div>
    <div style="clear:both;"></div>
</header>

<div id="content">


<?php
$reg_debugmode = 0; // Set this to 1 to test new installations of this script, or 0 if the script is live.
if(isset($_GET['debug']))
	$reg_debugmode = 1;

/***********************************************************
*********************** Functions **************************
***********************************************************/
function euro_to_usd($val)
{
	return x_to_usd($val, "EUR");
}
function x_to_usd($val, $cur)
{
	global $fr;
	return $fr->convert($val, $cur, "usd");
}
function amounts_to_levels($a)
{
	global $reg_region;
	global $cursymbols;
	return $a['description'] ." - ". (isset($cursymbols[$reg_region])?$cursymbols[$reg_region]:"$") . sprintf("%01.2f", $a['amount']);
}
function checkinput($name, $data, $set)
{
	if($data == "")
		return true;
	
	if($set == "cc" && ($_POST['cc_payby'] == "ch" || $_POST['cc_payby'] == "in"))
	{
		return true;
	}
	else if(is_array($data))
	{
		if(is_bool($data[0]))
			$vals = array_shift($data);
		else
			$vals = false;
		if($vals && !isset($data[$_POST[$name]]) || !$vals && !in_array($_POST[$name], $data))
			return false;
	}
	else
	{
		if(!preg_match("/^". $data ."$/", $_POST[$name]))
			return false;
	}
	return true;
}
function checkcaptcha()
{
	global $captcha_checked;
	
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = array(
		'secret' => "6Lf0bBsUAAAAADUJQn7QJaPeU_5GLnr7TlBC7jMm",
		'response' => $_POST['g-recaptcha-response'],
		'remoteip' => $_SERVER['REMOTE_ADDR'],
	);
	// use key 'http' even if you send the request to https://...
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data),
		),
	);
	$context  = stream_context_create($options);
	$result = json_decode(file_get_contents($url, false, $context));
	$captcha_checked = $result->success;
	return $result->success;
}
if($_GET['action'] == "register")
{
    $sql = "SELECT * FROM `salescache_events` WHERE `Council` = 'a1241000000bDoLAAU' and `Status` = 'Date Confirmed' and `isSFConnected` = '1' and `Date` >= CURDATE() and (`Registration` >= CURDATE() or `Registration`='')  ORDER BY `salescache_events`.`Date` ASC";
    $query = $database->query($sql);
    $events = $query->fetchAll(PDO::FETCH_ASSOC);
	foreach($events as $event)
	{
	    $eid = substr(hash('sha1', $event['sales_id']), 0, 8);
	    if($_POST['event']==$eid)
	    {
	        $event_info = $event;
	        $event_id = $event['sales_id'];
	        break;
	    }
	}

    $sql2 = "SELECT * FROM `salescache_chapters` WHERE `sales_id` = '".$event['Chapter']."'";
    $query2 = $database->query($sql2);
    $chapter = $query2->fetch(PDO::FETCH_ASSOC);
    $city = $chapter['Location_City'];
    $state = ($event['Type_of_Location']=="Virtual") ? "Virtual" : $chapter['Location_State'];
    
    $year = date('Y', strtotime($event['Date']));

    $venue = $city.", ".$state;

    $reg_eventTitle = $year." ".$venue." NDC Certification Program - Higher Education/Govt/Nonprofit";
    $reg_description = "Individual Registration";
    $invoicealready = $_POST['invoiced'];
    if($invoicealready=="YES")
        $reg_description .= " - Organization already Invoiced";

	/************************/
	$opps = $_POST['corporateopportunities'];
		
	if($opps=="YES")
	{
		$coordinator_info = getCoordinatorInfo($year, $city, $state);
		list($coordinator_name, $coordinator_email) = $coordinator_info;
		
		$mailheaders = "From: NDC Certification Program <donotreply@ndc-registration.org>\r\n";
		$mailheaders .= "MIME-Version: 1.0\r\nContent-Type: text/html; charset=utf-8\r\n";
		$subject = "Automative Response - More Information about Corporate Partnership Opportunities";
		$msg = "Dear ".$coordinator_name.",\n";
		$msg .= "\n";
		$msg .= $_POST['firstname'] ." ". $_POST['lastname'] ." has signed up for the ".$reg_eventTitle." and would like more information about Corporate Partnership Opportunities.\n";
		$msg .= "\n";
		$msg .= "Please follow up and email ". $_POST['firstname'] ." ". $_POST['lastname']." about Corporate Partnership Opportunities!\n";
		$msg .= "\n";
		$msg .= $_POST['firstname'] ." ". $_POST['lastname'] ."'s email is ".$_POST['email']; 

		mail($coordinator_email, $subject, $msg, $mailheaders);
	}
	/************************/

    require_once("../FloatRates.php");
    $fr = new FloatRates("usd");
    
    /***********************************************************
    ********************** Page Display ************************
    ***********************************************************/
    $cursymbols = array( // use http://www.w3schools.com/charsets/ref_utf_currency.asp to find currency symbols
        "USD" => "$",
    	"EU" => "&euro;",
    	"EUR" => "&euro;",
    	"CAD" => "$",
    	"GBP" => "&pound;",
        "CHF" => "Fr",
    	/*"ARS" => "$",*/
    	"MXN" => "$",
    	"ZAR" => "R",
    	"AED" => "AED ",
    	/*"AED" => "د.إ",*/
    );
    /***********************************************************
    ********************* Configuration ************************
    ***********************************************************/
    
    /********* Database *********/
    $reg_db_host = "localhost";
    $reg_db_user = "***************";
    $reg_db_pass = "*******";  /**It used to be: boADD!!* but HG Admin did not update correctly**/
    $reg_db_name = "********";
    
    /********* Password **********/
    $firstname = strtolower(mysql_real_escape_string($_POST['firstname']));
    $lastname  = strtolower(mysql_real_escape_string($_POST['lastname']));
    $password = $firstname.$lastname.time();
    $md5password = md5($password);

    /********** Authnet *********/
    if($reg_merchant == "D&L")
    {
    	$auth_net_id = "****";
    	$auth_net_tran_key = "****";
    }
    else if($reg_merchant == "TXDC")
    {
    	$auth_net_id = "*****";
    	$auth_net_tran_key = "******";
    }
    else
    {
    	$auth_net_id = "*******";
    	$auth_net_tran_key = "**********";
    }
    
    $auth_net_url = "https://secure.authorize.net/gateway/transact.dll"; // No test account
    $auth_net_test_request = (bool)$reg_debugmode;
    $auth_net_merchant_email = "";
    $reg_other_emails = array(
    	"finance@ndc.org",
        "cdp@nndc.org"
    );
    if(is_array($reg_emails))
    	$reg_other_emails = array_merge($reg_other_emails, $reg_emails);
    	
    $reg_mysql_ok = true;
    
    
    $mysqlConnection = mysql_connect($reg_db_host, $reg_db_user, $reg_db_pass);
    if (!$mysqlConnection)
    {
    	$reg_mysql_ok = false;
    }
    else
    {
    	mysql_select_db($reg_db_name, $mysqlConnection);
    }

    $rmailheaders; 
    
    $accomendations = $_POST['specialaccommodations'];
	$dietary = $_POST['dietaryrestrictions'];
	$check = checkcaptcha();


	// Send the payment info to authorize.net
	// Everything in this section is static at this point. That means, the $reg_fields array will be expected to have certain values specified a certain way in order for this to work.
	$phone = $_POST['phone_0'] ."-". $_POST['phone_1'] ."-". $_POST['phone_2'];

    //$payamount = $_POST['payamount'];
    $payamount="all";
    if($payamount=="all")
        $price = 4500;
    else
    {
        $price = 2000;
        $reg_description .= " Installed Payments";
    }
    //SF connected price
    $price = $event['Extra_Price'];
        
    $fee = 0;
    $amount = $price+$fee;
	//$_POST['country'] = "United States";
	$usdamount = sprintf("%01.2f", $amount);
	$amount = sprintf("%01.2f", $amount);
	
	$date = time();
    $confirmationid = substr(strtoupper(hash('sha256', $date.$_POST['firstname'].$_POST['lastname'])), 0, 10);

	if($reg_free || $_POST['cc_payby'] == "ch" || $_POST['cc_payby'] == "in")
	{
		if($reg_mysql_ok)
		{
			$reg_insert_row = array
			(
				'event'		=> "'".mysql_real_escape_string($year." ".$city." NDC Certification Program - Higher Education/Govt/Nonprofit")."'",
				'userID'	=> "'1'",
				'venue'		=> "'".mysql_real_escape_string($venue)."'",
				'date'		=> time(),
				'ip'		=> "'".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."'",
				'url'		=> "'".mysql_real_escape_string($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'])."'",
				'confirmationID'	=> "'".mysql_real_escape_string($confirmationid)."'",
				'firstname'	=> "'".mysql_real_escape_string($_POST['firstname'])."'",
				'middlename'	=> "'".mysql_real_escape_string($_POST['middlename'])."'",
				'lastname'	=> "'".mysql_real_escape_string($_POST['lastname'])."'",
				'pronoun'	=> "'".mysql_real_escape_string($_POST['preferredgenderpronoun'])."'",
				'designations'	=> "'".mysql_real_escape_string($_POST['professionaldesignations'])."'",
				'salutation'	=> "'".mysql_real_escape_string($_POST['salutation'])."'",
				'title'		=> "'".mysql_real_escape_string($_POST['title'])."'",
				'company'	=> "'".mysql_real_escape_string($_POST['company'])."'",
				'email'		=> "'".mysql_real_escape_string($_POST['email'])."'",
				'address'	=> "'".mysql_real_escape_string($_POST['address'])."'",
				'address2'	=> "'".mysql_real_escape_string($_POST['address2'])."'",
				'city'		=> "'".mysql_real_escape_string($_POST['city'])."'",
				'state'		=> "'".mysql_real_escape_string($_POST['state'])."'",
				'zip'		=> "'".mysql_real_escape_string($_POST['zip'])."'",
				'country'	=> "'".mysql_real_escape_string($_POST['country'])."'",
				'how'       => "'".mysql_real_escape_string($_POST['howdidyouhearaboutus'])."'",
				'phone'		=> "'".mysql_real_escape_string($phone)."'",
				'accomendations'    => "'".mysql_real_escape_string($accomendations)."'",
				'dietaryrestrictions'    => "'".mysql_real_escape_string($dietary)."'",
				'level'		=> "'".mysql_real_escape_string($reg_description)."'",
				'amount'	=> "'".mysql_real_escape_string($amount)."'",
				'password'	=> "'".mysql_real_escape_string($md5password)."'",
				're_email'		=> "'".mysql_real_escape_string($_POST['re_email'])."'",
				'test'		=> ($reg_debugmode ? 1 : 0),
				'unique'	=> "'".mysql_real_escape_string(time().$_POST['firstname'].$_POST['lastname'])."'",
			);
			if($_POST['cc_payby'] == "in")
			{
				$reg_insert_row['level'] = "'".mysql_real_escape_string($reg_description)."'";
				$reg_insert_row['amount'] = $usdamount;
				$reg_insert_row['cc_type'] = "'INVOICE'";
				
				if($invoicealready)
				    $reg_insert_row['emailed'] = "'1'";

				$vendor = "NDC";
				$state = "";

            	//updated 10/28/2021 JMS
                //$count_existing = mysql_result(mysql_query("SELECT COUNT(*) FROM registration_invoices WHERE `vendor`='{$vendor}'". (!empty($state)?" AND `state`='{$state}'":"")), 0, 0);
				$count_existing = mysql_result(mysql_query("SELECT COUNT(*) FROM registration_invoices"), 0, 0);
				
				$buyer_data = 
				[
					'firstname' => $_POST['firstname'],
					'lastname' => $_POST['lastname'],
					're_email' => $_POST['re_email'],
					'address' => $_POST['address'],
					'address2' => $_POST['address2'],
					'city' => $_POST['city'],
					'state' => $_POST['state'],
					'zip' => $_POST['zip'],
					'company' => $_POST['company'],
				];
                    
				$details_data = 
				[
					'total' => $usdamount,
					'items' => 
					[
						[
							'html' => $venue ." - ". $reg_eventTitle ." - ". $reg_description,
							'description' => $reg_eventTitle ." - ". $reg_description,
							'price' => $usdamount,
						],
					],
				];
				$invoice_data = 
				[
				    //changed 10-28-2021
					'identifier' => "'". mysql_real_escape_string(hash('sha1', $vendor.'-0-'.$state.'-'.$count_existing.'-'.date('Ymdhis'))) ."'",
					'vendor' => "'{$vendor}'",
					'state' => "'{$state}'",
					'number' => (int)$count_existing + 1,
					'date' => time(),
					'buyer' => "'". mysql_real_escape_string(json_encode($buyer_data)) ."'",
					'details' => "'". mysql_real_escape_string(json_encode($details_data)) ."'",
				];
				$queryinv  = "INSERT INTO registration_invoices ";
				$queryinv .= "(`". implode("`,`", array_keys($invoice_data)) ."`) ";
				$queryinv .= "VALUES (". implode(",", $invoice_data) .")";
				
				mysql_query($queryinv);
				$mysql_inverror = mysql_error();
				if($mysql_inverror == "")
				{
					ob_start();
					include("../invoice-template.php");
					$invoice_html = ob_get_clean();
					
					$reg_insert_row['invoice_id'] = mysql_result(mysql_query("SELECT LAST_INSERT_ID()"), 0, 0);
				}
				else
				{
					$invoice_html = "";
					echo("Invoice could not be generated!");
				}
			}
			
			$query  = "INSERT INTO registrations ";
			$query .= "(`". implode("`,`", array_keys($reg_insert_row)) ."`) ";
			$query .= "VALUES (". implode(",", $reg_insert_row) .")";
			mysql_query($query);
			$reg_cust_id = mysql_result(mysql_query("SELECT LAST_INSERT_ID()"), 0, 0);
			$mysql_error = mysql_error();
			
			/**$councils = array();
			foreach($_POST['statecouncil'] as $council)
			    array_push($councils, $council); **/
			$statecouncil = implode(", ", $_POST['statecouncil']);
			
			//insert into registration mailing db
			$mail_insert_row = array
			(
				'registrationsID'	=> $reg_cust_id,
				'si_firstname'	=> "'".mysql_real_escape_string($_POST['si_firstname'])."'",
				'si_lastname'	=> "'".mysql_real_escape_string($_POST['si_lastname'])."'",
				'si_address'	=> "'".mysql_real_escape_string($_POST['si_address'])."'",
				'si_address2'	=> "'".mysql_real_escape_string($_POST['si_address2'])."'",
				'si_city'		=> "'".mysql_real_escape_string($_POST['si_city'])."'",
				'si_state'		=> "'".mysql_real_escape_string($_POST['si_state'])."'",
				'si_zip'		=> "'".mysql_real_escape_string($_POST['si_zip'])."'",
				'si_country'	=> "'".mysql_real_escape_string($_POST['country'])."'",
				'staff'	=> "'".mysql_real_escape_string($_POST['staff'])."'",	
				'member'	=> "'".mysql_real_escape_string($_POST['member'])."'",					
				'statecouncil'	=> "'".mysql_real_escape_string($statecouncil)."'",		
				'LearnPartnershipOpportunities'	=> "'".mysql_real_escape_string($opps)."'",				
			);
			
			
			$query1  = "INSERT INTO registration_cdp ";
			$query1 .= "(`". implode("`,`", array_keys($mail_insert_row)) ."`) ";
			$query1 .= "VALUES (". implode(",", $mail_insert_row) .")";
			mysql_query($query1);
			$mail_cust_id = mysql_result(mysql_query("SELECT LAST_INSERT_ID()"), 0, 0);
			$mysql_error1 = mysql_error();

			if($mysql_error == "" && $mysql_error1 == "")
			{
				$eventName = $reg_eventTitle;
				echo("Your registration for '". $eventName ."' has been recorded successfully! Thank you for registering! An email has been sent to your address as an additional record of this registration.");
				if($_POST['cc_payby'] == "in" && !empty($invoice_html))
				{
				    echo("<br/><br/>You will receive an invoice from our finance department, shortly.\n");
				}
				
				$to = implode(", ", $reg_other_emails);
				$subject = $eventName;
				$mailheaders = "From: ". $_POST['firstname'] ." ". $_POST['lastname'] ." <". $_POST['email'] .">\n";
				$mailheaders .= "MIME-Version: 1.0\r\nContent-Type: text/html; charset=ISO-8859-1\r\n";
				
				$msg  = $_POST['salutation'] ." ". $_POST['firstname'] ." ". $_POST['lastname'] ." is a ". $_POST['title'] ." at ". $_POST['company'] ." and has submitted their (". $_POST['preferredgenderpronoun'] .") registration for ". $reg_eventTitle." - ".$reg_description.".<br>";
				$msg .= "<br>";
				$msg .= "The contact information for them (". $_POST['preferredgenderpronoun'] .") is:<br>";
				$msg .= $_POST['email'] ."<br>";
				$msg .= $phone ."<br>";
				$msg .= $_POST['address'] .", ". $_POST['address2'] ."<br>";
				$msg .= $_POST['city'] .", ". $_POST['state'] .", ". $_POST['zip'] ."<br>";
				$msg .= "Their special accommodations are: (".$accomendations.")<br>";
				$msg .= "Their dietary restrictions are: (".$dietary.")<br>";
				$msg .= "<br>";
				
				if($_POST['cc_payby'] == "ch")
					$msg .= "The registrant has elected to send in a check in the amount of \$". $usdamount ." to pay for the registration.\n";
				if($_POST['cc_payby'] == "in"&&$invoicealready=="NO")
					$msg .= "The registrant has elected to be sent an invoice.\n";

				$msg .= "\n";
				$msg .= "A back-office entry has been added to the database for this registration.\n";
				$msg .= " ".$level;
				
				// Send some emails to notify those of interest
				if($_POST['cc_payby'] == "ch" || $_POST['cc_payby'] == "in")
					mail($auth_net_merchant_email, $subject, $msg, $mailheaders);
				mail("finance@ndc.org", $subject, $msg, $mailheaders);
    			mail("cdp@ndc.org", $subject, $msg, $mailheaders);
				
				if(!empty($invoice_html)&&$invoicealready=="NO")
				{
					$imailheaders  = "From: NDC Certification Program <donotreply@ndc-registration.org>\n";
					$imailheaders .= "MIME-Version: 1.0\r\nContent-Type: text/html; charset=ISO-8859-1\r\n";
					$imailheaders .= "Cc: ". implode(", ", $reg_other_emails) ."\r\n";
					
					mail("#", "Invoice For ". $eventName, $invoice_html, $imailheaders);
					//mail($_POST['email'], "Invoice For ". $eventName, $invoice_html, $imailheaders);
				}


				$rmailheaders  = "From: Certification Program <donotreply@ndc.org>\n";
				$rmailheaders .= "MIME-Version: 1.0\r\nContent-Type: text/html; charset=ISO-8859-1\r\n";
				$rmailheaders .= "Reply-To: donotreply@ndc.org\r\n";
				$rmailheaders .= "Cc: cdp@ndc.org\r\n";
				$rmsg  = "Hello ". $_POST['salutation'] ." ". $_POST['firstname'] ." ". $_POST['lastname']. ",<br>";
				$rmsg .= "This is an automated email to confirm your registration for '". $reg_eventTitle ." - ". $reg_description ."'. The registration was successful and no further action is required. This email is simply for your records.<br>";
				//$rmsg .= "If you did not register for this event, we apologize. Please reply to this email so we can correct the issue.<br>";
				$rmsg .= "Please access your pre-work <a href='https://drive.google.com/drive/folders/1av8j3betYxdyzpd6H8BmSu4Jr4CgLnP_?usp=sharing' target='_blank'>here</a> and contact <a href='mailto:'>virtual.learning@</a> if you encounter any issues.<br>";
				$rmsg .= "Reminder, Per the Terms and Conditions, there is a $500 fee to transfer to another session.<br><br>";
				$rmsg .= "Thank you!<br><br>";
				$rmsg .= "NDC<br>";
				$rmsg .= "*****<br>";
				
                ob_start();
                include("email_template.php");
                $email_html = ob_get_clean();
				
				mail($_POST['email'], $year." ".$city." NDC Certification Program - Higher Education/Govt/Nonprofit - Individual Registration - Registration Confirmation", $email_html, $rmailheaders);





			    /********/
			    //Salesforce Registration 1-24-2022
			    if($event_id!=NULL)//&&$row0['sales_id']=="a101K00000F8zVsQAJ"
			    {
				    require_once("../Event_Registration.php");
				    date_default_timezone_set('America/Chicago');  
				    
				    $full_name = $_POST['firstname'].' '.$_POST['lastname'];
				
				    $event_registration = new Event_Registration('Event_Submission__c');
                    $name = $full_name." ".date("m-d-Y h:iA", $reg_insert_row['date']);
                    
                    $event_registration->set_Info('Name', $name);
                    $event_registration->set_Info('Name__c', $full_name);
                    $event_registration->set_Info('Date_of_Registration__c', date("c", $reg_insert_row['date']));
                    $event_registration->set_Info('NDC_Event__c', $event_id);
                    $event_registration->set_Info('Confirmation_Number__c', $confirmationid);
                    $event_registration->set_Info('Amount_Due__c', $usdamount);
                    
                    $personal_info = [$_POST['firstname'], $_POST['middlename'], $_POST['lastname'], $_POST['salutation'], $_POST['professionaldesignations'], $_POST['preferredgenderpronoun'], $phone, $_POST['email'], $_POST['re_email'], $_POST['title'], $_POST['company'], $_POST['address'], $_POST['address2'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['country']];
                    $event_registration->set_Personal_Info($personal_info);

                    if(!empty($invoice_html))
                    {
                        $event_registration->set_Info('Invoice_Number__c', "NDC-0-".$invoice_data['number']);
                        $event_registration->set_Info('Payment_Type__c', "Invoice");
                        $invoiced = ($_POST['invoiced']=="Yes") ? "My Organization was already Invoiced" : "My Organization has not been Invoiced";
                        $event_registration->set_Info('Invoiced__c', $invoiced);
                    }
                    
                    //$event_registration->set_Info('State_Council__c', $_POST['statecouncil']);
                    $event_registration->set_Info('Registration_Type__c', "Corporate Partner");
                    $event_registration->set_Info('How_did_you_hear_about_us__c', $_POST['howdidyouhearaboutus']);
                    $event_registration->set_Info('Is_organization_a_Corporate_Partner__c', "Yes");
                    $event_registration->set_Info('Organization_A_Member__c', "Yes");
                    $event_registration->set_Info('Special_Accommodations__c', $_POST['specialaccommodations']);
                    

                    if($event_registration->login())
                    {
                        $request = $event_registration->perform_request();
                        
                        $mailheaders .= "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\n";
                        if($request)
                        {
                            //echo "API request successful";
                            mail("web@ndc.org", "SALESFORCE REST API - Individual Registration request successful", $event_registration->get_link(), $mailheaders);
                        }
                        else
                        {
                            //echo "API request failed - not successful";
                            
                            mail("web@ndc.org", "SALESFORCE REST API - Individual Registration request failed", $event_registration->get_sf_response(), $mailheaders);
                        }
                    }
			    }/************/

			}
			else
			{
				echo("An error has occurred and this registration was not successfully recorded. We're sorry for the inconvenience!");
			}
		}
	}
	else if($amount > 0 || $reg_debugmode)
	{
		$delim = "|";
		$expiration = urlencode($_POST['cc_expiration_month']) ."/". urlencode($_POST['cc_expiration_year']);
		$authnet_values	= array(
			"x_test_request"		."=". $auth_net_test_request,
			"x_version"				."=". "3.1",
			"x_delim_char"			."=". $delim,
			"x_delim_data"			."=". "TRUE",
			"x_type"				."=". "AUTH_CAPTURE",
			"x_method"				."=". "CC",
			"x_relay_response"		."=". "FALSE",
			"x_email_customer"		."=". "TRUE",
			"x_email"				."=". urlencode($_POST['email']),
			"x_merchant_email"		."=". $auth_net_merchant_email,
			"x_tran_key"			."=". $auth_net_tran_key,
			"x_login"				."=". $auth_net_id,
			"x_card_num"			."=". urlencode($_POST['cc_creditdebitcardnumber']),
			"x_exp_date"			."=". $expiration,
			"x_description"			."=". urlencode($reg_eventTitle ." - ". $reg_description),
			"x_amount"				."=". $usdamount,
			"x_card_code"			."=". urlencode($_POST['cc_cardverificationcode']),
			"x_first_name"			."=". urlencode($_POST['cc_firstname']),
			"x_last_name"			."=". urlencode($_POST['cc_lastname']),
			"x_company"				."=". urlencode($_POST['company']),
			"x_address"				."=". urlencode($_POST['cc_address']),
			"x_city"				."=". urlencode($_POST['cc_city']),
			"x_state"				."=". urlencode($_POST['cc_state']),
			"x_zip"					."=". urlencode($_POST['cc_zip']),
			"x_phone"				."=". $phone,
			"x_customer_ip"			."=". $_SERVER['REMOTE_ADDR'],
		//	"x_cust_id"				."=". (mysql_result(mysql_query("SELECT LAST_INSERT_ID()"), 0, 0)+1),
		//	"x_invoice_num"			."=". 0,
		);
		$ch = curl_init($auth_net_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $authnet_values_arr);
		curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&", $authnet_values));
		$reg_response = explode($delim, curl_exec($ch));
		curl_close($ch);
		
		// Parse the authorize.net response
		if($reg_response[0] == 1 || ($reg_response[0] == 4 && ($reg_response[2] == 252 || $reg_response[2] == 253)))
		{
			// Success! Enter the data into back-office

			if($reg_mysql_ok)
			{
				$reg_insert_row = array
				(
					'event'		=> "'".mysql_real_escape_string($year." ".$city." NDC Certification Program - Higher Education/Govt/Nonprofit")."'",
					'userID'	=> "'1'",
					'venue'		=> "'".mysql_real_escape_string($venue)."'",
					'date'		=> time(),
					'ip'		=> "'".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."'",
					'url'		=> "'".mysql_real_escape_string($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'])."'",
					'confirmationID'	=> "'".mysql_real_escape_string($confirmationid)."'",
					'firstname'	=> "'".mysql_real_escape_string($_POST['firstname'])."'",
					'middlename'	=> "'".mysql_real_escape_string($_POST['middlename'])."'",
					'lastname'	=> "'".mysql_real_escape_string($_POST['lastname'])."'",
					'pronoun'	=> "'".mysql_real_escape_string($_POST['preferredgenderpronoun'])."'",
					'designations'	=> "'".mysql_real_escape_string($_POST['professionaldesignations'])."'",
					'salutation'	=> "'".mysql_real_escape_string($_POST['salutation'])."'",
					'title'		=> "'".mysql_real_escape_string($_POST['title'])."'",
					'company'	=> "'".mysql_real_escape_string($_POST['company'])."'",
					'email'		=> "'".mysql_real_escape_string($_POST['email'])."'",
					'address'	=> "'".mysql_real_escape_string($_POST['address'])."'",
					'address2'	=> "'".mysql_real_escape_string($_POST['address2'])."'",
					'city'		=> "'".mysql_real_escape_string($_POST['city'])."'",
					'state'		=> "'".mysql_real_escape_string($_POST['state'])."'",
					'zip'		=> "'".mysql_real_escape_string($_POST['zip'])."'",
					'country'	=> "'".mysql_real_escape_string($_POST['country'])."'",
					'how'       => "'".mysql_real_escape_string($_POST['howdidyouhearaboutus'])."'",
					'phone'		=> "'".mysql_real_escape_string($phone)."'",
					'accomendations'    => "'".mysql_real_escape_string($accomendations)."'",
					'dietaryrestrictions'    => "'".mysql_real_escape_string($dietary)."'",
					'level'		=> "'".mysql_real_escape_string($reg_description)."'",
					'amount'	=> $reg_response[9],
					'password'	=> "'".mysql_real_escape_string($md5password)."'",
					'merchant'	=> "'".mysql_real_escape_string($auth_net_id)."'",
					'creditcard'	=> "'".mysql_real_escape_string(substr($reg_response[50], -4))."'",
					'cc_type'	=> "'".mysql_real_escape_string($reg_response[51])."'",
					'cc_expiration'	=> "'".mysql_real_escape_string($expiration)."'",
					'cc_firstname'	=> "'".mysql_real_escape_string($reg_response[13])."'",
					'cc_lastname'	=> "'".mysql_real_escape_string($reg_response[14])."'",
					'cc_address'	=> "'".mysql_real_escape_string($reg_response[16])."'",
					'cc_city'	=> "'".mysql_real_escape_string($reg_response[17])."'",
					'cc_state'	=> "'".mysql_real_escape_string($reg_response[18])."'",
					'cc_zip'	=> "'".mysql_real_escape_string($reg_response[19])."'",
					're_email'		=> "'".mysql_real_escape_string($_POST['re_email'])."'",
					'test'		=> ($auth_net_test_request ? 1 : 0),
					'unique'	=> "'".mysql_real_escape_string(time().$reg_response[51].$reg_response[50].$_POST['firstname'].$reg_response[9].$_POST['lastname'])."'",
				);
				
				
				$query  = "INSERT INTO registrations ";
				$query .= "(`". implode("`,`", array_keys($reg_insert_row)) ."`) ";
				$query .= "VALUES (". implode(",", $reg_insert_row) .")";
				mysql_query($query);
				$reg_cust_id = mysql_result(mysql_query("SELECT LAST_INSERT_ID()"), 0, 0);
				$mysql_error = mysql_error();
				
    			/**$councils = array();
    			foreach($_POST['statecouncil'] as $council)
    			    array_push($councils, $council); **/
    			$statecouncil = implode(", ", $_POST['statecouncil']);
    			
    			//insert into registration mailing db
    			$mail_insert_row = array
    			(
    				'registrationsID'	=> $reg_cust_id,
    				'si_firstname'	=> "'".mysql_real_escape_string($_POST['si_firstname'])."'",
    				'si_lastname'	=> "'".mysql_real_escape_string($_POST['si_lastname'])."'",
    				'si_address'	=> "'".mysql_real_escape_string($_POST['si_address'])."'",
    				'si_address2'	=> "'".mysql_real_escape_string($_POST['si_address2'])."'",
    				'si_city'		=> "'".mysql_real_escape_string($_POST['si_city'])."'",
    				'si_state'		=> "'".mysql_real_escape_string($_POST['si_state'])."'",
    				'si_zip'		=> "'".mysql_real_escape_string($_POST['si_zip'])."'",
    				'si_country'	=> "'".mysql_real_escape_string($_POST['country'])."'",
    				'staff'	=> "'".mysql_real_escape_string($_POST['staff'])."'",	
    				'member'	=> "'".mysql_real_escape_string($_POST['member'])."'",					
    				'statecouncil'	=> "'".mysql_real_escape_string($statecouncil)."'",		
					'LearnPartnershipOpportunities'	=> "'".mysql_real_escape_string($opps)."'",				
    			);
				
				
				$query1  = "INSERT INTO registration_cdp ";
				$query1 .= "(`". implode("`,`", array_keys($mail_insert_row)) ."`) ";
				$query1 .= "VALUES (". implode(",", $mail_insert_row) .")";
				mysql_query($query1);
				$mail_cust_id = mysql_result(mysql_query("SELECT LAST_INSERT_ID()"), 0, 0);
				$mysql_error1 = mysql_error();

    			if($mysql_error == "" && $mysql_error1 == "")
    			{
			
        			// Thank the registrant
        			if(!$reg_debugmode)
        			{
        				echo("Thank you! Your payment has been processed, and your registration has been recorded!<br/>\n");
        			}
        			else
        				echo("The test transaction was successful! If this were an actual registration, it would have been processed with the following details:<br/>\n");
        			echo("<table class='regtable'>\n");
        			echo("	<caption>Payment Details</caption>\n");
        			echo("	<tr>\n");
        			echo("		<th>Customer ID:</th>\n");
        			echo("		<td>". $reg_cust_id ."</td>\n");
        			echo("	</tr>\n");
        			echo("	<tr>\n");
        			echo("		<th>Description:</th>\n");
        			echo("		<td>". html_entity_decode($reg_response[8], ENT_QUOTES) ."</td>\n");
        			echo("	</tr>\n");
        			echo("	<tr>\n");
        			echo("		<th>Amount:</th>\n");
        			echo("		<td>$". $reg_response[9] . "</td>\n");
        			echo("	</tr>\n");
        			echo("	<tr>\n");
        			echo("		<th>Credit Card:</th>\n");
        			echo("		<td>". $reg_response[51] ." - ". $reg_response[50] ."</td>\n");
        			echo("	</tr>\n");
        			echo("	<tr>\n");
        			echo("		<th>Card Holder:</th>\n");
        			echo("		<td>". $reg_response[13] ." ". $reg_response[14] ."</td>\n");
        			echo("	</tr>\n");
        			echo("	<tr>\n");
        			echo("		<th>Billing Address:</th>\n");
        			echo("		<td>". $reg_response[16] ."<br/>". $reg_response[17] .", ". $reg_response[18] .", ". $reg_response[19] ."</td>\n");
        			echo("	</tr>\n");
        			echo("	<tr>\n");
        			echo("		<th>Mailing Address:</th>\n");
        			echo("		<td>". $_POST['si_address'] ."<br/>". $_POST['si_address2'] .", ". $_POST['si_city'] .", ". $_POST['si_state'] .", ". $_POST['si_zip'] ."</td>\n");
        			echo("	</tr>\n");
        			echo("	<tr>\n");
        			echo("		<th>Response Code:</th>\n");
        			echo("		<td>". $reg_response[0] .".". $reg_response[1] .".". $reg_response[2] .": ". $reg_response[3] ."</td>\n");
        			echo("	</tr>\n");
        			echo("</table>\n");
        			echo("You should receive an email receipt as well.\n");
        			
        			// Send some emails to notify those of interest
        			$to = implode(", ", $reg_other_emails);
        			$subject = html_entity_decode($reg_response[8], ENT_QUOTES);
        			$mailheaders = "From: ". $_POST['firstname'] ." ". $_POST['lastname'] ." <". $_POST['email'] .">\n";
        			$mailheaders .= "MIME-Version: 1.0\r\nContent-Type: text/html; charset=ISO-8859-1\r\n";
        			
        			$msg  = $_POST['salutation'] ." ". $_POST['firstname'] ." ". $_POST['lastname'] ." is a ". $_POST['title'] ." at ". $_POST['company'] ." and has submitted their (". $_POST['preferredgenderpronoun'] .") registration for ". html_entity_decode($reg_response[8], ENT_QUOTES) ." ($". $reg_response[9] .").<br>";
        			$msg .= "<br>";
        			$msg .= "The contact information for them (". $_POST['preferredgenderpronoun'] .") is:<br>";
        			$msg .= $_POST['email'] ."<br>";
        			$msg .= $phone ."<br>";
        			$msg .= $_POST['address'] .", ". $_POST['address2'] ."<br>";
        			$msg .= $_POST['city'] .", ". $_POST['state'] .", ". $_POST['zip'] ."<br>";
        			$msg .= "Their special accommodations are: (".$accomendations.")<br>";
					$msg .= "Their dietary restrictions are: (".$dietary.")<br>";
        			
        			$msg .= "<br>";
        			$msg .= "The following is the payment method that was used:<br>";
        			$msg .= $reg_response[51] ." - ". $reg_response[50] ."<br>";
        			$msg .= $reg_response[13] ." ". $reg_response[14] ."<br>";
        			$msg .= $reg_response[16] ."<br>";
        			$msg .= $reg_response[17] .", ". $reg_response[18] .", ". $reg_response[19] ."<br>";
        			$msg .= "<br>";
        			
        			if(!$reg_mysql_ok)
        			{
        				$msg .= "No back-office entry was added for this registration due to a problem with the script.\n";
        				$newMsg = $msg . "\n" . $query;
        				mail("web@dc.org", $subject, $msg, $mailheaders);
        			}
        			else if($mysql_error != "")
        			{
        				$msg .= "No back-office entry was added for this registration because the following error occurred:\n";
        				$msg .= $mysql_error ."\n";
        				$newMsg = $msg . "\n" . $query;
        				mail("web@ndc.org", $subject, $msg, $mailheaders);
        			}
        			else
        				$msg .= "A back-office entry has been added to the database for this registration.\n";
        			
    				mail("finance@ndc.org", $subject, $msg, $mailheaders);
        			mail("cdp@ndc.org", $subject, $msg, $mailheaders);
        			
        			$rmailheaders  = "From: NDC Certification Program <donotreply@ndc.org>\n";
        			$rmailheaders .= "MIME-Version: 1.0\r\nContent-Type: text/html; charset=ISO-8859-1\r\n";
        			$rmailheaders .= "Reply-To: donotreply@ndc.org\r\n";
        			$rmailheaders .= "Cc: cdp@ndc.org\r\n";
        			$rmsg  = "Hello ". $_POST['salutation'] ." ". $_POST['firstname'] ." ". $_POST['lastname']. ",<br>";
        			$rmsg .= "This is an automated email to confirm your registration for '". $reg_eventTitle ." - ". $reg_description ."'. The registration was successful and no further action is required. This email is simply for your records.<br>";
        			//$rmsg .= "If you did not register for this event, we apologize. Please reply to this email so we can correct the issue.<br>";
        			$rmsg .= "Please access your pre-work <a href='https://drive.google.com/drive/folders/1av8j3betYxdyzpd6H8BmSu4Jr4CgLnP_?usp=sharing' target='_blank'>here</a> and contact <a href='mailto:'>virtual.learning@</a> if you encounter any issues.<br>";
        			$rmsg .= "Reminder, Per the Terms and Conditions, there is a $500 fee to transfer to another session.<br><br>";
        			$rmsg .= "Thank you!<br><br>";
        			$rmsg .= "NDC<br>";
        			$rmsg .= "http://www.ndc.org<br>";
        			
                    ob_start();
                    include("email_template.php");
                    $email_html = ob_get_clean();
        			
        			mail($_POST['email'], $year." ".$city." NDC Certification Program - Higher Education/Govt/Nonprofit - Individual Registration - Registration Confirmation", $email_html, $rmailheaders);
        			
        			
        			
        			
        			
        			
        			
    				    /********/
    				    //Salesforce Registration 1-24-2022
    				    if($event_id!=NULL)//&&$row0['sales_id']=="a101K00000F8zVsQAJ"
    				    {
    					    require_once("../Event_Registration.php");
    					    date_default_timezone_set('America/Chicago');  
    					    
    					    $full_name = $_POST['firstname'].' '.$_POST['lastname'];
    					
    					    $event_registration = new Event_Registration('Event_Submission__c');
                            $name = $full_name." ".date("m-d-Y h:iA", $reg_insert_row['date']);
                            
                            $event_registration->set_Info('Name', $name);
                            $event_registration->set_Info('Name__c', $full_name);
                            $event_registration->set_Info('Date_of_Registration__c', date("c", $reg_insert_row['date']));
                            $event_registration->set_Info('NDC_Event__c', $event_id);
                            $event_registration->set_Info('Confirmation_Number__c', $confirmationid);
                            $event_registration->set_Info('Amount_Due__c', $usdamount);
                            
                            $personal_info = [$_POST['firstname'], $_POST['middlename'], $_POST['lastname'], $_POST['salutation'], $_POST['professionaldesignations'], $_POST['preferredgenderpronoun'], $phone, $_POST['email'], $_POST['re_email'], $_POST['title'], $_POST['company'], $_POST['address'], $_POST['address2'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['country']];
                            $event_registration->set_Personal_Info($personal_info);
                            
                            $billing_info = [$reg_response[16], "", $reg_response[17], $reg_response[18], $reg_response[19], $_POST['country'], $_POST['email'], $reg_response[13], "", $reg_response[14]];
                            $event_registration->set_Billing_Info($billing_info);
                            
                            $payment_info = [$full_name, "", substr($reg_response[50], -4), $expiration];
                            $event_registration->set_Payment_Info($payment_info); 
                            $event_registration->set_Info('Payment_Type__c', "Credit Card Payment");
                            
                            $reg_type = "Individual Registration";
                            $pos = strpos(strtolower($reg_insert_row['level']), 'sponsor');
                            if ($pos !== false) 
                            {
                                $reg_type = "Sponsorship";
                            }
                            
                            //$event_registration->set_Info('State_Council__c', $_POST['statecouncil']);
                            $event_registration->set_Info('Registration_Type__c', "Corporate Partner");
                            $event_registration->set_Info('How_did_you_hear_about_us__c', $_POST['howdidyouhearaboutus']);
                            $event_registration->set_Info('Is_organization_a_Corporate_Partner__c', "Yes");
                            $event_registration->set_Info('Organization_A_Member__c', "Yes");
                            $event_registration->set_Info('Special_Accommodations__c', $_POST['specialaccommodations']);
                            
                            if($event_registration->login())
                            {
                                $request = $event_registration->perform_request();
                                
                                $mailheaders .= "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\n";
                                if($request)
                                {
                                    //echo "API request successful";
                                    mail("web@dc.org", "SALESFORCE REST API - Individual Registration request successful", $event_registration->get_link(), $mailheaders);
                                }
                                else
                                {
                                    //echo "API request failed - not successful";
                                    
                                    mail("web@ndc.org", "SALESFORCE REST API - Individual Registration request failed", $event_registration->get_sf_response(), $mailheaders);
                                }
                            }
    				    }/************/
        			
        			
    			}
    			else
    			{
    				echo("An error has occurred and this registration was not successfully recorded. We're sorry for the inconvenience!");
    			}
			}
		}
		else
		{
			// Fail! Give the registrant some details so they can figure out what went wrong.
			echo("<table class='regtable'>\n");
			echo("	<caption>Payment ". ($reg_response[0]==2?"Declined":"Error") ."</caption>\n");
			echo("	<tr>\n");
			echo("		<td colspan='2'>". $_POST['salutation'] ." ". $_POST['firstname'] ." ". $_POST['lastname'] .",<br/>We thank you for your ". html_entity_decode($reg_response[8], ENT_QUOTES) ." registration, however your payment of $". $reg_response[9] ." has <b>not</b> been processed by the credit card processing company. We're sorry for the inconvenience. The following info was given in response, which may help to determine where the error occurred:</td>\n");
			echo("	</tr>\n");
			echo("	<tr>\n");
			echo("		<th>Credit Card:</th>\n");
			echo("		<td>". $reg_response[51] ." - ". $reg_response[50] ."</td>\n");
			echo("	</tr>\n");
			echo("	<tr>\n");
			echo("		<th>Card Code Verification:</th>\n");
			echo("		<td>");
			switch($reg_response[38])
			{
				case "M":
					echo("Match");
					break;
				case "N":
					echo("No Match");
					break;
				case "P":
					echo("Not Processed");
					break;
				case "S":
					echo("Should have been present");
					break;
				case "U":
					echo("Issuer unable to process request");
					break;
				default:
					echo("N/A");
			}
			echo("</td>\n");
			echo("	</tr>\n");
			echo("	<tr>\n");
			echo("		<th>Card Holder:</th>\n");
			echo("		<td>". $reg_response[13] ." ". $reg_response[14] ."</td>\n");
			echo("	</tr>\n");
			echo("	<tr>\n");
			echo("		<th>Holder Verification:</th>\n");
			echo("		<td>");
			switch($reg_response[39])
			{
				case "0":
					echo("CAVV not validated because erroneous data was submitted");
					break;
				case "1":
					echo("CAVV failed validation");
					break;
				case "2":
					echo("CAVV passed validation");
					break;
				case "3":
					echo("CAVV validation could not be performed; issuer attempt incomplete");
					break;
				case "4":
					echo("CAVV validation could not be performed; issuer system error");
					break;
				case "7":
					echo("CAVV attempt – failed validation – issuer available (U.S.-issued card/non-U.S acquirer)");
					break;
				case "8":
					echo("CAVV attempt – passed validation – issuer available (U.S.-issued card/non-U.S. acquirer)");
					break;
				case "9":
					echo("CAVV attempt – failed validation – issuer unavailable (U.S.-issued card/non-U.S. acquirer)");
					break;
				case "A":
					echo("CAVV attempt – passed validation – issuer unavailable (U.S.-issued card/non-U.S. acquirer)");
					break;
				case "B":
					echo("CAVV passed validation, information only, no liability shift");
					break;
				default:
					echo("CAVV not validated");
			}
			echo("</td>\n");
			echo("	</tr>\n");
			echo("	<tr>\n");
			echo("		<th>Billing Address:</th>\n");
			echo("		<td>". $reg_response[16] ."<br/>". $reg_response[17] .", ". $reg_response[18] .", ". $reg_response[19] ."</td>\n");
			echo("	</tr>\n");
			echo("	<tr>\n");
			echo("		<th>Address Verification:</th>\n");
			echo("		<td>");
			switch($reg_response[5])
			{
				case "A":
					echo("Address (Street) matches, ZIP does not");
					break;
				case "B":
					echo("Address information not provided for AVS check");
					break;
				case "E":
					echo("AVS error");
					break;
				case "G":
					echo("Non-U.S. Card Issuing Bank");
					break;
				case "N":
					echo("No Match on Address (Street) or ZIP");
					break;
				case "P":
					echo("AVS not applicable for this transaction");
					break;
				case "R":
					echo("Retry—System unavailable or timed out");
					break;
				case "S":
					echo("Service not supported by issuer");
					break;
				case "U":
					echo("Address information is unavailable");
					break;
				case "W":
					echo("Nine digit ZIP matches, Address (Street) does not");
					break;
				case "X":
					echo("Address (Street) and nine digit ZIP match");
					break;
				case "Y":
					echo("Address (Street) and five digit ZIP match");
					break;
				case "Z":
					echo("Five digit ZIP matches, Address (Street) does not");
					break;
				default:
					echo("N/A");
			}
			echo("</td>\n");
			echo("	</tr>\n");
			echo("	<tr>\n");
			echo("		<th>Response Code:</th>\n");
			echo("		<td>". $reg_response[0] .".". $reg_response[1] .".". $reg_response[2] .": ". $reg_response[3] ."</td>\n");
			echo("	</tr>\n");
			echo("	<tr>\n");
			echo("		<td colspan='2'>If the error occurred due to an invalid entry on the registration form, please try again. Otherwise, try another credit card to process your payment.</td>\n");
			echo("	</tr>\n");
			echo("</table>\n");
		}
	}
	
    echo '</div>';
}
else
{

$venues = getVenues(); 
$dates = getDates();

?>
<script>
function checkAmount()
{
	var amount = document.getElementById('payamount');
	//amount.value=="all";
	
	var fullamount = document.getElementById('fullamount');  
	var monthlyamount = document.getElementById('monthlyamount'); 
	var m=0;
	
	/*if(amount.value=="all")
	{
	    fullamount.style.display = 'table-row';
	    monthlyamount.style.display = 'none';
	}
	else if (amount.value=="split")
	{
	    monthlyamount.style.display = 'table-row';
	    fullamount.style.display = 'none';
	    m=1;
	}*/

    
    var venueselect = document.getElementById("venueselect").value;
    if(venueselect=="")
        alert("Please Select a Session");
    if(venueselect!=""&&m==1)
    {
        var dates1 = <? echo $dates ?>;
    
        let abbrev = get_state(venueselect);
        
        var state = abbrev;
        var event_dates = [];
        
        for(let i=0;i<dates1.length;i++)
        {
            var ev = dates1[i].venue.toLowerCase();
            state = state.toLowerCase();
            
            var text = dates1[i].event_date;
            
            if(ev.includes(state))
                event_dates.push(dates1[i]);
        
        }
    
        
        var ed = event_dates[0].event_date;
        var onemb = event_dates[0].onemb;
        var twomb = event_dates[0].twomb;

        document.getElementById("monthlyamount").innerHTML = '<tr id="monthlyamount" style="display: table-row;"><th>* Price:</th><td style="font-size: 12px; font-weight:bold;">Individual Registration -<br>$2,000 immediately<br>$1,250 on '+onemb+'<br>$1,250 on '+twomb+'</td></tr>';
    }
    
}
function get_state(venues)
{
    state ="";
    
    // else if venue contains the state return that state
    //echo venues;
    lv = venues.toLowerCase();
    lv.replace(/\s/g, '')
    
    if(venues.includes('AZ')||venues.includes('arizona'))
    {
        state = "AZ";   
    }
    else if(venues.includes('AR')||venues.includes('arkansas'))
    {
        state = "AR";   
    }
    else if(venues.includes('CA')||venues.includes('california'))
    {
        state = "CA";   
    }
    else if(venues.includes('CO')||venues.includes('colorado'))
    {
        state = "CO";   
    }
    else if(venues.includes('FL')||venues.includes('florida'))
    {
        state = "FL";   
    }
    else if(venues.includes('GA')||venues.includes('georgia'))
    {
        state = "GA";   
    }
    else if(venues.includes('IL')||venues.includes('illinois'))
    {
        state = "IL";   
    }
    else if(venues.includes('LA')||venues.includes('louisiana'))
    {
        state = "LA";   
    }
    else if(venues.includes('MI')||venues.includes('michigan'))
    {
        state = "MI";   
    }
    else if(venues.includes('NC')||venues.includes('northcarolina'))
    {
        state = "NC";   
    }
    else if(venues.includes('OH')||venues.includes('ohio'))
    {
        state = "OH";   
    }
    else if(venues.includes('PA')||venues.includes('pennsylvania'))
    {
        state = "PA";   
    }
    else if(venues.includes('NY')||venues.includes('newyork'))
    {
        state = "NY";   
    }
    else if(venues.includes('NJ')||venues.includes('newjersey'))
    {
        state = "NJ";   
    }
    else if(venues.includes('CT')||venues.includes('connecticut'))
    {
        state = "CT";   
    }
    else if(venues.includes('TX')||venues.includes('texas'))
    {
        state = "TX";   
    }
    else if(venues.includes('PNW')||venues.includes('pacific'))
    {
        state = "PNW";   
    }
    
    return state;
}
</script>

<?php
if($show)
{
?>    
    	<h1>Certification Program - Higher Education/Govt/Nonprofit</h1>
    
  
    <p style="text-align: center;">Select a session from the drop-down menu (if applicable).</p>
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    
    <form id="registration" action="?action=register" method="post" onsubmit="return checkform(true)">
    <table id="_set" class="regtable" cellpadding="5">
    	<caption>Personal Information</caption>
    	<tbody>
    <?
        $sql2 = "SELECT * FROM `salescache_chapters` WHERE `sales_id` = '".$event['Chapter']."'";
        $query2 = $database->query($sql2);
        $chapter = $query2->fetch(PDO::FETCH_ASSOC);
        $city = $chapter['Location_City'];
        $state = ($event['Type_of_Location']=="Virtual") ? "Virtual" : $chapter['Location_State'];
        

        $year = date('Y', strtotime($event['Date']));
        $venue = $year." ".$city.", ".$state." NDC Certification Program";
		$pos = strpos($venue, 'Virtual');

    	echo '<tr><th>Program:</th><td style="font-size: 12px; font-weight: bold;">NDC Certification Program - Higher Education/Govt/Nonprofit</td></tr>';
    	echo '<tr><th>* Session:</th><td style="font-size: 12px; font-weight: bold;">'.$venue.'</td></tr>';
    	echo '<tr style="display:none"><th>* Event:</th><td><input style="display:none" value="'.$event_id.'" id="event" name="event" title="event"></input></td></tr>';
    	
    	echo '<tr><th>* Company:</th><td><input required name="company" title="company"></td></tr>';
    	echo '<tr><th>* Title:</th><td><input required name="title" title="title"></td></tr>';
    	echo '<tr><th>Salutation:</th><td><input name="salutation" title="salutation"></td></tr>';
    	echo '<tr><th>* First Name:</th><td><input required name="firstname" title="firstname"></td></tr>';
    	echo '<tr><th>Middle Name:</th><td><input name="middlename" title="middlename"></td></tr>';
    	echo '<tr><th>* Last Name:</th><td><input required name="lastname" title="lastname"></td></tr>';
    	echo '<tr><th>* Personal Pronoun:</th><td><input required name="preferredgenderpronoun" title="preferredgenderpronoun"></td></tr>';
    	echo '<tr><th>Professional Designations, if any:</th><td><input name="professionaldesignations" title="professionaldesignations"></td></tr>';
    	echo '<tr><th>* Email:</th><td><input required type="email" name="email" title="email"></td></tr>';
    	echo '<tr><th>* Address:</th><td><input required name="address" title="address"></td></tr>';
    	echo '<tr><th>Address 2:</th><td><input name="address2" title="address2"></td></tr>';
    	echo '<tr><th>* City:</th><td><input required name="city" title="city"></td></tr>';
    	echo '<tr><th>* State:</th><td><select required name="state" title="state"><option value="">Select One</option><option value="AL">AL</option><option value="AK">AK</option><option value="AZ">AZ</option><option value="AR">AR</option><option value="CA">CA</option><option value="CO">CO</option><option value="CT">CT</option><option value="DE">DE</option><option value="DC">DC</option><option value="FL">FL</option><option value="GA">GA</option><option value="HI">HI</option><option value="ID">ID</option><option value="IL">IL</option><option value="IN">IN</option><option value="IA">IA</option><option value="KS">KS</option><option value="KY">KY</option><option value="LA">LA</option><option value="ME">ME</option><option value="MD">MD</option><option value="MA">MA</option><option value="MI">MI</option><option value="MN">MN</option><option value="MS">MS</option><option value="MO">MO</option><option value="MT">MT</option><option value="NE">NE</option><option value="NV">NV</option><option value="NH">NH</option><option value="NJ">NJ</option><option value="NM">NM</option><option value="NY">NY</option><option value="NC">NC</option><option value="ND">ND</option><option value="OH">OH</option><option value="OK">OK</option><option value="OR">OR</option><option value="PA">PA</option><option value="RI">RI</option><option value="SC">SC</option><option value="SD">SD</option><option value="TN">TN</option><option value="TX">TX</option><option value="UT">UT</option><option value="VT">VT</option><option value="VA">VA</option><option value="WA">WA</option><option value="WV">WV</option><option value="WI">WI</option><option value="WY">WY</option></select></td></tr>';
    	echo '<tr><th>* Zip:</th><td><input required name="zip" title="zip"></td></tr>';
		echo '<tr><th>* Country:</th><td><input required name="country" title="country"></td></tr>';
    	echo '<tr><th>* Phone:</th><td><input required type="hidden" name="phone"><input required name="phone_0" title="phone_0" size="4" maxlength="3" onkeyup="autotab(this, 3)"><input required name="phone_1" title="phone_1" size="4" maxlength="3" onkeyup="autotab(this, 3)"><input required name="phone_2" title="phone_2" size="4" maxlength="4" onkeyup="autotab(this, 4)"></td></tr>';
    	echo '<tr><th>* How did you hear about us?:</th><td><select required name="howdidyouhearaboutus" title="howdidyouhearaboutus"><option value="">Select One</option><option value="Email Blast">Email Blast</option><option value="Press Release">Press Release</option><option value="Social Media Facebook">Social Media Facebook</option><option value="Social Media Instagram">Social Media Instagram</option><option value="Social Media LinkedIn">Social Media LinkedIn</option><option value="Social Media Twitter">Social Media Twitter</option><option value="Website">Website</option><option value="Other">Other</option></select></td></tr>';
        echo '<tr><th>* If you discussed or received additional information regarding the certification program from a NDC staff member, please state their name below:<br>N/A if not applicable</th><td><input required name="staff" title="staff"></td></tr>';
    	echo '<tr><th>* Is your organization a member of the NDC or a member of a state council under the NDC umbrella?:</th><td><select required onchange="checkMember()" id="member" name="member" title="member"><option value="">Select One</option><option value="YES">Yes</option><option value="NO">No</option></select></td></tr>';
    	echo '<tr id="statecouncil" style="display: none;"><th>* Select a State Council<br>Select all that apply:<br>(Use ctrl for multiple)</th><td>
    		    <select id="statecouncilselect" name="statecouncil[]" title="statecouncil" multiple>
    		    <option value="">Select One</option>
    		    <option value="Arizona">AZ</option>
    		    <option value="California">CA</option>
    		    <option value="Colorado">CO</option>
    		    <option value="Florida">FL</option>
    		    <option value="Georgia">GA</option>
    		    <option value="Illinois">IL</option>
    		    <option value="Michigan">MI</option>
    		    <option value="Ohio">OH</option>
    		    <option value="Pennsylvania">PA</option>
    		    <option value="Texas">TX</option>
    		    <option value="Tristate">Tri-State (NY, NJ, CT)</option>
    		    <option value="NDC">National</option>
    		   </select>
    	    </td></tr>';
		echo '<tr id="opportunities"><th>* Would you like to learn more about corporate partnership opportunities?</th><td>
			<select id="opportunities" name="corporateopportunities" title="opportunities" required>
			<option value="">Select One</option>
			<option value="YES">Yes</option>
			<option value="NO">No</option>
		   </select>
		</td></tr>';
    	echo '<tr><th>Special Accommodations:</th><td><input name="specialaccommodations" title="specialaccommodations"></td></tr>';
    	if ($pos == false){
			echo '<tr><th>* Dietary Restrictions: </th><td><input required name="dietaryrestrictions" title="dietaryrestrictions"></td></tr>';
			}
    ?>
    </tbody></table>
    
    
    <table id="si_set" class="regtable" cellpadding="5">
	    <?
		
		 if ($pos !== false){
		 echo'<caption>Mailing Address for Program Books</caption>';
		 echo'<caption style="font-size: 12px;border: none;margin: 0px;">Please note: An adult signature will be required at the time of delivery.</caption>';}
    	else{
		 echo'<caption>Mailing Address for Certificates</caption>';	
		 }
		?>
    	
    <tbody>
    	<tr class="sifield"><th>Mailing Address Same as Above:</th><td><input type="checkbox" name="samemailing" title="Mailing Address Same as Above"></td></tr>
    <?
    	echo '<tr><th>* First Name:</th><td><input required name="si_firstname" title="si_firstname"></td></tr>';
    	echo '<tr><th>* Last Name:</th><td><input required name="si_lastname" title="si_lastname"></td></tr>';
    	echo '<tr><th>* Address:</th><td><input required name="si_address" title="si_address"></td></tr>';
    	echo '<tr><th>Address 2:</th><td><input name="si_address2" title="si_address2"></td></tr>';
    	echo '<tr><th>* City:</th><td><input required name="si_city" title="si_city"></td></tr>';
    	echo '<tr><th>* State:</th><td><select required name="si_state" title="si_state"><option value="">Select One</option><option value="AL">AL</option><option value="AK">AK</option><option value="AZ">AZ</option><option value="AR">AR</option><option value="CA">CA</option><option value="CO">CO</option><option value="CT">CT</option><option value="DE">DE</option><option value="DC">DC</option><option value="FL">FL</option><option value="GA">GA</option><option value="HI">HI</option><option value="ID">ID</option><option value="IL">IL</option><option value="IN">IN</option><option value="IA">IA</option><option value="KS">KS</option><option value="KY">KY</option><option value="LA">LA</option><option value="ME">ME</option><option value="MD">MD</option><option value="MA">MA</option><option value="MI">MI</option><option value="MN">MN</option><option value="MS">MS</option><option value="MO">MO</option><option value="MT">MT</option><option value="NE">NE</option><option value="NV">NV</option><option value="NH">NH</option><option value="NJ">NJ</option><option value="NM">NM</option><option value="NY">NY</option><option value="NC">NC</option><option value="ND">ND</option><option value="OH">OH</option><option value="OK">OK</option><option value="OR">OR</option><option value="PA">PA</option><option value="RI">RI</option><option value="SC">SC</option><option value="SD">SD</option><option value="TN">TN</option><option value="TX">TX</option><option value="UT">UT</option><option value="VT">VT</option><option value="VA">VA</option><option value="WA">WA</option><option value="WV">WV</option><option value="WI">WI</option><option value="WY">WY</option></select></td></tr>';
    	echo '<tr><th>* Zip:</th><td><input required name="si_zip" title="si_zip"></td></tr>';
    ?>
    </tbody></table>
    
    
    <table id="cc_set" class="regtable" cellpadding="5">
    	<caption>Billing Information</caption>
    	<tbody>
    <?
    
    	echo '<tr id="fullamount"><th>* Price:</th><td style="font-size: 12px; font-weight:bold;">Individual Registration - $'.number_format($event['Extra_Price']).'</td></tr>';
    	/*echo '<tr><th>* How would you like to pay the amount?:</th><td><select required onchange="checkAmount()" id="payamount" name="payamount" title="member">';
    	    echo '<option value="">Select One</option><option value="all">Pay Amount In Full</option>';
    	    echo '<option value="split">Installed Payments</option>';
    	echo '</select></td></tr>';*/
    	echo '<tr id="monthlyamount" style="display:none;"><th>* Price:</th><td style="font-size: 12px; font-weight:bold;"></td></tr>';
    
        echo '<tr><th>Pay By:</th><td>
        <input name="cc_payby" title="Pay by Credit Card" value="cc" id="cc_payby_cc" type="radio" onclick="togglemethod()" checked=""> Credit Card';
        /**<input name="cc_payby" title="Pay by Check" value="ch" id="cc_payby_ch" type="radio" onclick="togglemethod()">Check**/
        echo '<input name="cc_payby" title="Pay by Invoice" value="in" id="cc_payby_in" type="radio" onclick="togglemethod()"> Invoice</td></tr>';

    	echo '<tr class="invoicefield" id="invoiceinfo" style="display: none;"><th>* My organization was already invoiced: </th><td>
    		    <select class="invoicefield" name="invoiced" title="invoiced">
    		    <option value="">Select One</option>
    		    <option value="YES">Yes</option>
    		    <option value="NO">No</option>
    		   </select>
    	    </td></tr>';
		echo '<tr class="invoicefield" id="invoiceinfo" style="display: none;"><th>Remittance Email: </th><td><input class="invoicefield" name="re_email" title="re_email" type="email"></td></tr>';

    	echo '<tr id="checkinfo" style="display:none;"><td colspan="2" style="padding:1ex 5em;"><b>Make checks payable to:</b><br>NDC<br>NDC Certification Program<br><br><b>Mail checks to:</b><br> NDC<br> PO Box 590258<br> Houston, TX 77259-0258</td></tr>';
    	echo '<tr class="ccfield"><th>Billing Address Same as Above:</th><td><input type="checkbox" name="sameaddress" title="Billing Address Same as Above"></td></tr>';
    	echo '<tr class="ccfield"><th>* Credit/Debit Card Number:</th><td><input required class="creditfields" name="cc_creditdebitcardnumber" title="cc_creditdebitcardnumber"></td></tr>';
    	echo '<tr class="ccfield"><th>* Expiration:</th><td><input class="creditfields" required type="hidden" name="cc_expiration"><select required class="creditfields" name="cc_expiration_month" title="cc_expiration_month"><option value="">Select One</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select>';
    	echo '<select required class="creditfields" name="cc_expiration_year" title="cc_expiration_year"><option value="">Select One</option><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option><option value="2027">2027</option><option value="2028">2028</option><option value="2029">2029</option><option value="2030">2030</option></select></td></tr>';
    	echo '<tr class="ccfield"><th>* First Name:</th><td><input required class="creditfields" name="cc_firstname" title="cc_firstname"></td></tr>';
    	echo '<tr class="ccfield"><th>* Last Name:</th><td><input required class="creditfields" name="cc_lastname" title="cc_lastname"></td></tr>';
    	echo '<tr class="ccfield"><th>* Address:</th><td><input required class="creditfields" name="cc_address" title="cc_address"></td></tr>';
    	echo '<tr class="ccfield"><th>* City:</th><td><input required class="creditfields" name="cc_city" title="cc_city"></td></tr>';
    	echo '<tr class="ccfield"><th>* State:</th><td><select required class="creditfields" name="cc_state" title="cc_state"><option value="">Select One</option><option value="AL">AL</option><option value="AK">AK</option><option value="AZ">AZ</option><option value="AR">AR</option><option value="CA">CA</option><option value="CO">CO</option><option value="CT">CT</option><option value="DE">DE</option><option value="DC">DC</option><option value="FL">FL</option><option value="GA">GA</option><option value="HI">HI</option><option value="ID">ID</option><option value="IL">IL</option><option value="IN">IN</option><option value="IA">IA</option><option value="KS">KS</option><option value="KY">KY</option><option value="LA">LA</option><option value="ME">ME</option><option value="MD">MD</option><option value="MA">MA</option><option value="MI">MI</option><option value="MN">MN</option><option value="MS">MS</option><option value="MO">MO</option><option value="MT">MT</option><option value="NE">NE</option><option value="NV">NV</option><option value="NH">NH</option><option value="NJ">NJ</option><option value="NM">NM</option><option value="NY">NY</option><option value="NC">NC</option><option value="ND">ND</option><option value="OH">OH</option><option value="OK">OK</option><option value="OR">OR</option><option value="PA">PA</option><option value="RI">RI</option><option value="SC">SC</option><option value="SD">SD</option><option value="TN">TN</option><option value="TX">TX</option><option value="UT">UT</option><option value="VT">VT</option><option value="VA">VA</option><option value="WA">WA</option><option value="WV">WV</option><option value="WI">WI</option><option value="WY">WY</option></select></td></tr>';
    	echo '<tr class="ccfield"><th>* Zip:</th><td><input required class="creditfields" name="cc_zip" title="cc_zip"></td></tr>';
    	echo '<tr class="ccfield"><th>* Card Verification Code:</th><td><input required class="creditfields" name="cc_cardverificationcode" title="cc_cardverificationcode"></td></tr>';
    ?>
    </tbody></table>
    
    <div>
        <p style="font-weight: bold;text-align: center;"><a target="_blank" href="">Please read the NDC Terms and Conditions</a><br>
        I agree to the Payment, Refund, Transfer and Cancellation policy<br> noted in the NDC Terms and Conditions<input required type="checkbox" name="terms" title="Terms and Conditions"></p>
    </div>

    <?
    
    	echo("<table id='captchatable' class='regtable' style='display: flex;justify-content: center;'>\n");
    	echo("	<tr>\n");
    	echo("	    <td><div class='g-recaptcha' data-sitekey='6Lf0bBsUAAAAALo50XJYI9gSov2ObAsXLxhyjEql'></div></td>\n");
    	echo("	</tr>\n");
    	echo("</table>\n");

    	echo("<table class='regtable'>\n");
    	echo("<tr><th style='padding:10px 25%; text-align:center;'>The NDC is committed to making all events accessible to everyone.<br>We highly recommend submitting your request (4) four weeks before the event. </th></tr>\n");
    	echo("</table>\n");
	        
    	echo("<table class='regtable'>\n");
    	//echo("	<caption><input type='button' value='Submit' name='register' onClick='checkform(true)'/></caption>\n");
    	echo("	<caption><input type='submit' value='Submit' name='register' title='Submit'/></caption>\n");
    	echo("	<tr>\n");
    	echo("		<td style='font-size: 0.8em'><p>Fields marked with * are required. Click the submit button only once. If the registration goes through, you will be taken to a new page. If the transaction is successful (for credit card registrations), you will see the transaction details, and you should also receive a receipt by email.</p>");
    	echo("		<noscript><br/><b><u>Notice:</u></b> You do not have JavaScript enabled. This form uses JavaScript to validate form input and inform you of immediate problems with your input. Without JavaScript, if there is a problem with one of your inputs, the page will simply reload and show the form again.</noscript></td>\n");
    	echo("	</tr>\n");
    	echo("	<tr>\n");
    	echo("		<td>&nbsp;</td>\n");
    	echo("	</tr>\n");
    	echo("	<tr>\n");
    	echo("		<td style='font-size: 0.85em; text-align:center;'><a href='' target='_blank'>Privacy</a> &middot; Copyright &copy; 2009-".date("Y")." NDC. All rights reserved.</td>\n");
    	echo("	</tr>\n");
    	echo("	<tr>\n");
    	echo("		<td>&nbsp;</td>\n");
    	echo("	</tr>\n");
    	echo("</table>\n");
    	
    ?>
    
    
    </form>


</div>
<?
}
else
{
    
    echo "<h1>NDC Certification Program</h1><p>".$txt."</p>";
    
    
    
}

?>

</div> 


<?


}
?>




</body>