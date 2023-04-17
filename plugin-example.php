<?php
/**
 * Plugin Name: SF-WEB_API
 * Plugin URI: 
 * Description: Display content using a shortcode to insert in a page or post
 * Version: 1.0
 * Text Domain: ndc_salesforce_connection_wordpress_plugin
 * Author: Fargol Hasani
 */ 
include_once("salesforceeventviewer.php");
include_once("salesforcechapterviewer.php");
include_once("salesforcecouncilviewer.php");
include_once("salesforcesponsorshipviewer.php");
include_once("salesforceparticipantviewer.php");
include_once("salesforceorganizationviewer.php");
include_once("salesforceadvisoryboardviewer.php");
include_once("salesforceboardofdirectorsviewer.php");
include_once("salesforcecontactviewer.php");
include_once("salesforcestaffviewer.php");
include_once("salesforcepartnerviewer.php");
include_once("database.php");
include_once("past_events.php");
include_once("salesforcecalendar.php");

date_default_timezone_set('America/Chicago'); 
 
function getting_event_info($atts) 
{
	$id = $atts["id"];
	$type = $atts["type"];
	
	$df = new Database();
	//Salesforce Event
	$sfev = new SalesforceEventViewer($df, "salescache_events", array('sales_id'=>$id), "fetch");
	$event = $sfev->getEvents();
    
    $html = array();
    
	{

	
	
/******

event info
1 - name
2 - js date (F D y)
3 - Series
4 - theme 
5 - description
6 - address
7 - hashtag
8 - thumbnail
9 - time
10 - price
11 - registration
12 - header
13 - registration date
14 - status
15 - website
16 - event cancelled text

website setup
theme
jsdate
description
js date
event map
statecouncil participants
statecouncil organizations
registration
hashtag
-js section
    js date
    time
    event cancelled text
-header
event name
    date
    time

*****/
	
	$id = $sfev->getSalesforceInfo("sales_id");
	array_push($html, $id);
	
	$name = $sfev->getSalesforceInfo("Name");
	if($event['Type_of_Region']=="Regional")
    {
        $name.= "Regional ".$event['Series'];
    }
	array_push($html, $name);
	
	$fdate = $sfev->getFormattedDate();
	array_push($html, $fdate);
	$event_series = $sfev->getSalesforceInfo("Series");
	array_push($html, $event_series);
	$theme = $sfev->getTheme();
	array_push($html, $theme);
	
	$coordinatorInfo = $sfev->getEventCoordinatorInfo();
	$council_id = $sfev->getSalesforceInfo("Council");
	
	
	$sfcv = new SalesforceCouncilViewer($df, "salescache_councils", array('sales_id'=>$council_id), "fetch");
	$council_name = $sfcv->getCouncilName();

	$description = $sfev->getDescription($council_name, $sfev->getSeries(), $coordinatorInfo);
	array_push($html, $description);

	//$address = $sfev->getSalesforceInfo("Series");
	$address = "United States";
	array_push($html, $address);

	$hashtag = $sfev->getHashtags();
	array_push($html, $hashtag);
	$image_url = $sfev->getSalesforceInfo("thumbnail_url");
	array_push($html, $image_url);
	$timeHTML = $sfev->getTimeHTML();
	array_push($html, $timeHTML);
	$price = $sfev->getPrice();
	array_push($html, $price);

	$registration_section = $sfev->getRegistrationSection();
	array_push($html, $registration_section);

	$header = $sfev->getHeader();
	if($event['Type_of_Region']=="Regional")
    {
        $header.= "<br>".$event['Series'];
    }
	array_push($html, $header);
	$Registration = $sfev->getSpecificDate("Registration");
	array_push($html, $Registration);

	$status = $sfev->getSalesforceInfo("Status");
	array_push($html, $status);

	$website = $sfev->getSalesforceInfo("Website");
	array_push($html,  $website);

	$eventnothappening = $sfev->EventNotHappening($coordinatorInfo);
	array_push($html, $eventnothappening);

	}

	return $html[$type];

}
add_shortcode('getEventInformation', 'getting_event_info');

function getting_event_map($atts) 
{
	$id = $atts["id"];

	$df = new Database();
	//Salesforce Event
	$sfev = new SalesforceEventViewer($df, "salescache_events", array('sales_id'=>$id), "fetch");
	$council_id = $sfev->getSalesforceInfo("Council");
	//Salesforce Council
	$sfc = new SalesforceCouncilViewer($df, "salescache_councils", array('sales_id'=>$council_id), "fetch");
    $geocode_id = $sfev->getSalesforceInfo("geocode_id");

    $sql = "SELECT * FROM `geocoding_cache` WHERE `id` = '".$geocode_id."'";
    $database = $df->getDatabase();
    $query = $database->query($sql);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    $long  = $result['longitude'];
    $lat  = $result['longitude'];

    //$address = $result['address'];
    $address = $sfev->getSalesforceInfo("Locations");
    
    $venue = strtolower($sfev->getSalesforceInfo("Venue"));
    if (strpos($venue, 'virtual') !== false || strpos($venue, 'zoom') !== false||($sfev->getSalesforceInfo("Type_of_Location")=="Virtual"))
        $v=2;
    
    $council_name = $sfc->getSalesforceInfo("Name");
    $state = str_replace(" Diversity Council", "", $council_name);
    
    
    
    echo '<div data-parent="true" class="vc_row google-maps-section style-color-xsdn-bg row-container boomapps_vcrow" data-section="2"><div class="row limit-width row-parent" data-imgready="true"><div class="shift_y_neg_quad row-inner"><div class="pos-top pos-center align_center column_parent col-lg-12 boomapps_vccolumn double-internal-gutter"><div class="uncol style-dark"><div class="uncoltable"><div class="uncell  boomapps_vccolumn no-block-padding"><div class="uncont"><div class="vc_custom_1536110445824 row-internal row-container boomapps_vcrow"><div class="row unequal col-one-gutter row-child"><div class="row-inner cols-md-responsive" style="height: 472px; margin-bottom: -1px;"><div class="pos-top pos-center align_center column_child col-lg-12 boomapps_vccolumn col-md-33 half-internal-gutter"><div class="uncol style-light"><div class="uncoltable"><div class="uncell  vc_custom_1493988558049 boomapps_vccolumn single-block-padding style-color-xsdn-bg unshadow-mild"><div class="uncont">
    <div class="wpb_raw_code wpb_content_element wpb_raw_html">
    <div class="wpb_wrapper">';
    
    
    
    /***********************************************************/
    
    if($v==0)
    {
        //new version
        
        /**<!--//old key
    //AIzaSyAs37UxfHIBEddaVAFHYy9Y16d4WF6NZRQ-->**/
    
    ?>
    
    <script type="text/javascript" src="//maps.google.com/maps/api/js?key=AIzaSyDANHUJjZx3CCR0cAoZ-joeHGR3DNovUlA&libraries=places"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function()
    {
        var geocoder = new google.maps.Geocoder(); 
        geocoder.geocode({address: "<? echo $address ?>"}, function(results,status) 
        {
            if (status == google.maps.GeocoderStatus.OK && results.length)
            {
                var map = new google.maps.Map(document.getElementById("map"), 
                {
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControl: true,
                });
                var service = new google.maps.places.PlacesService(map);
                
                var viewport = {};
                viewport.north = results[0].geometry.viewport.getNorthEast().lat();
                viewport.east = results[0].geometry.viewport.getNorthEast().lng();
                viewport.south = results[0].geometry.viewport.getSouthWest().lat();
                viewport.west = results[0].geometry.viewport.getSouthWest().lng();
                
                for(var ri in results)
                {
                    if(ri > 0)
                    {
                        viewport.north = Math.max(viewport.north, results[ri].geometry.viewport.getNorthEast().lat());
                        viewport.east = Math.max(viewport.east, results[ri].geometry.viewport.getNorthEast().lng());
                        viewport.south = Math.min(viewport.south, results[ri].geometry.viewport.getSouthWest().lat());
                        viewport.west = Math.min(viewport.west, results[ri].geometry.viewport.getSouthWest().lng());
                    }
                    
                    service.getDetails({placeId:results[ri].place_id}, function(result,status2)
                    {
                        if(status2 == google.maps.places.PlacesServiceStatus.OK)
                        {
                            var marker = new google.maps.Marker(
                            {
                                place: 
                                {
                                    location: result.geometry.location,
                                    placeId: result.place_id,
                                },
                                map: map,
                                title: result.name +", "+ result.formatted_address,
                            });
                            
                            if(results.length > 1)
                            {
                                var infowindow = new google.maps.InfoWindow(
                                {
                                    content: "<b>" + result.name +", "+ result.formatted_address +"</b><br/><i>Copy/paste the above address into the location field if this is the correct location.</i>",
                                });
                                
                                marker.addListener('click', function() 
                                {
                                  infowindow.open(map,marker);
                                });
                            }
                        }
                    });
                    
                }
                map.fitBounds(viewport);
            }
            else
            {
                $v=1;
                
                <!-- //display genereic iframe map
                //$('#map').css({'height' : '15px'});
                //$('#map').html("Oops! {!NDCevents1__c.Name}'s address could not be found, please make sure the address is correct.");
                //resizeIframe();-->
            }
        });
      
        function resizeIframe()
        {
            var me = window.name;
            if(me)
            {
                var iframes = parent.document.getElementsByName(me);
                if (iframes && iframes.length == 1)
                {
                    height = document.body.offsetHeight;
                    iframes[0].style.height = height + "px";
                }
            }
        }
    });
    </script>
    <style>
    #map {
        font-family: Arial;
        font-size:12px;
        line-height:normal !important;
        height:500px;
        background:transparent;
        border:0;
        filter: url("data:image/svg+xml;utf8,<svg><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale"); /* Firefox 10+ */
        filter: gray; /* IE6-9 */
    	-webkit-filter: grayscale(99%); /* Chrome 19+ & Safari 6+ */
    }
    @media only screen and (min-width: 1500px)
    {
        .google-maps-section 
        {
            padding-top: 0px !important;
        }
    }
    @media only screen and (min-width: 1700px)
    {
        .google-maps-section .shift_y_neg_quad 
        {
            margin-top: -54px !important;
        }
    }
    </style>
    
    <?
    
    if($v==0)
        echo '<div id="map"></div>';
    else
        $v=1;
    
    }
    if($v==2)
    {
    echo '<style>
    .google-maps-section{ display: none !important; }
    </style>';
    echo '<script>
        var map = document.getElementsByClassName("google-maps-section");
        for(i=0;i<map.length;i++)
            map[i].style.display="none";
    </script>';
    }
    if($v==1)
    {
        //old generic version
        
        echo '<style>
          iframe
          {
            border:0;
            filter: url("data:image/svg+xml;utf8,<svg><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale"); /* Firefox 10+ */
            filter: gray; /* IE6-9 */
        	  -webkit-filter: grayscale(99%); /* Chrome 19+ & Safari 6+ */
          }
        </style>';
        
        $address = $state; 
    
        //php8 str_contains
        if((str_contains($state, "California"))||(str_contains($state, "CA")))
            echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6509916.957423575!2d-123.79759793610344!3d37.18430344877116!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x808fb9fe5f285e3d%3A0x8b5109a227086f55!2sCalifornia!5e0!3m2!1sen!2sus!4v1593016194422!5m2!1sen!2sus" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
        else if((str_contains($state, "Florida"))||(str_contains($state, "FL")))
            echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7245780.261213379!2d-88.29722151197348!3d27.53212261848994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c1766591562abf%3A0xf72e13d35bc74ed0!2sFlorida!5e0!3m2!1sen!2sus!4v1593016226675!5m2!1sen!2sus" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
        else if((str_contains($state, "Ohio"))||(str_contains($state, "OH")))
            echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3113819.0089130057!2d-84.91220234497172!3d40.34592015567373!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8836e97ab54d8ec1%3A0xe5cd64399c9fd916!2sOhio!5e0!3m2!1sen!2sus!4v1593016253289!5m2!1sen!2sus" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
        else if((str_contains($state, "Tri-State")))
            echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5971675.235239674!2d-78.19036480957492!3d43.0444391416935!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4cb376497183d8c7%3A0x844dca0fb4e234d0!2sNortheastern%20United%20States!5e0!3m2!1sen!2sus!4v1593016756525!5m2!1sen!2sus" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';   
        else if((str_contains($state, "Illinois"))||(str_contains($state, "IL")))
            echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3142438.314765886!2d-91.50948959721528!3d39.72195994893755!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x880b2d386f6e2619%3A0x7f15825064115956!2sIllinois!5e0!3m2!1sen!2sus!4v1593016802329!5m2!1sen!2sus" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
        else if((str_contains($state, "Michigan"))||(str_contains($state, "MI")))
            echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2889804.2768070567!2d-88.51325440466745!3d44.98279790414935!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4d4caa3dc7ca0411%3A0x97dd48597a62c9b3!2sMichigan!5e0!3m2!1sen!2sus!4v1593016827394!5m2!1sen!2sus" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
        else if((str_contains($state, "Pennsylvania"))||(str_contains($state, "PA")))
            echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1539050.2482327342!2d-78.72574948202472!3d41.11361889491783!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x882d80261e32e589%3A0xc24621475022b43d!2sPennsylvania!5e0!3m2!1sen!2sus!4v1593016854738!5m2!1sen!2sus" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
        else if((str_contains($state, "Georgia"))||(str_contains($state, "GA")))
            echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3439487.1244427706!2d-85.42162690989605!3d32.66279312522292!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88f136c51d5f8157%3A0x6684bc10ec4f10e7!2sGeorgia!5e0!3m2!1sen!2sus!4v1593016877255!5m2!1sen!2sus" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
        else if((str_contains($state, "Pacific Northwest")))
            echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d55565170.29301636!2d-132.08532758867793!3d31.786060306224!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54eab584e432360b%3A0x1c3bb99243deb742!2sUnited%20States!5e0!3m2!1sen!2sus!4v1592940796252!5m2!1sen!2sus" width="600" height="550" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
        else if((str_contains($state, "Arizona"))||(str_contains($state, "AZ")))
            echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3380999.27492953!2d-114.17278577421322!3d34.15255585532768!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x872b08ebcb4c186b%3A0x423927b17fc1cd71!2sArizona!5e0!3m2!1sen!2sus!4v1593017221167!5m2!1sen!2sus" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
        else if((str_contains($state, "Colorado"))||(str_contains($state, "CO")))
            echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3175951.792305031!2d-107.79358816668758!3d38.98073152085436!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x874014749b1856b7%3A0xc75483314990a7ff!2sColorado!5e0!3m2!1sen!2sus!4v1593017251152!5m2!1sen!2sus" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
        else if((str_contains($state, "Indiana"))||(str_contains($state, "IN")))
            echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3141197.0330176996!2d-88.68425822395199!3d39.74919149511395!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x886b50bcd9f81b1d%3A0x7e102fcecb32ec72!2sIndiana!5e0!3m2!1sen!2sus!4v1593017271739!5m2!1sen!2sus" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
        else if((str_contains($state, "Texas"))||(str_contains($state, "TX")))
            echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d949114.8003330363!2d-86.87200297283687!3d38.61192913436491!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864070360b823249%3A0x16eb1c8f1808de3c!2sTexas!5e0!3m2!1sen!2sus!4v1593017431543!5m2!1sen!2sus" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
        else
        {
            $address = "United States"; 
            echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d55565170.29301636!2d-132.08532758867793!3d31.786060306224!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54eab584e432360b%3A0x1c3bb99243deb742!2sUnited%20States!5e0!3m2!1sen!2sus!4v1592940796252!5m2!1sen!2sus" width="600" height="550" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
        }
    
    }
    /***********************************************************/
    
    
    echo'</div>
    </div>
    </div></div></div></div></div></div></div></div><div class="google-map-address-bar vc_custom_1537893748769 row-internal row-container boomapps_vcrow"><div class="row unequal col-one-gutter row-child"><div class="row-inner cols-md-responsive" style="height: 45px; margin-bottom: -1px;"><div class="pos-top pos-center align_center column_child col-lg-12 boomapps_vccolumn col-md-33 half-internal-gutter"><div class="uncol style-light"><div class="uncoltable"><div class="uncell  vc_custom_1537893125953 boomapps_vccolumn single-block-padding style-accent-bg"><div class="uncont">
    <div class="wpb_raw_code wpb_content_element wpb_raw_html">
    <div class="wpb_wrapper">';
    
    echo '<p style="color:#fff">'.$address.'</p>';
    
    echo '</div>
    </div>
    </div></div></div></div></div></div></div></div></div></div></div></div></div><script id="script-810763" data-row="script-810763" type="text/javascript">if ( typeof UNCODE !== "undefined" ) UNCODE.initRow(document.getElementById("script-810763"));</script></div></div></div>';
    
    echo '
    <style>
    
    .single-block-padding 
    {
        padding: 10px !important;
    }
    .row-inner .cols-md-responsive
    {
        height: auto !important;
    }
    .google-map-address-bar
    {
        margin-top: -15px !important;
    }
    </style>';
    
    	//return $html;
    
}
add_shortcode('getEventMap', 'getting_event_map');

function get_event_participants($atts) 
{
    $id = $atts["id"];
    
   	$df = new Database();
   	
   	$sfev = new SalesforceEventViewer($df, "salescache_events", array('sales_id'=>$id), "fetch");
   	$showAwardName = $sfev->checkAwardeeDate();
   	//$showAwardName = TRUE;
   	
   	//Salesforce Participants
	$sfpv = new SalesforceParticipantViewer($df, "salescache_event_participants", array('Event'=>$id, 'IsDeleted'=>'0'), "fetchAll");
    $participant_arrays = $sfpv->getParticipantArrays($showAwardName);

    if(sizeof($participant_arrays)>0)
        $sfpv->getStateCouncilParticipantSection($participant_arrays);
        
	/*****$sfpv = new SalesforceParticipantViewer($df, "salescache_event_participants", array('Event'=>$id, 'Type'=>'Panelist', 'IsDeleted'=>'0'), "fetchAll");
    $participant_arrays = $sfpv->getParticipantArrays();

    if(sizeof($participant_arrays)>0)
        $sfpv->getStateCouncilParticipantSection($participant_arrays);

    $sfpv = new SalesforceParticipantViewer($df, "salescache_event_participants", array('Event'=>$id, 'Type'=>'Speaker/Presenter', 'IsDeleted'=>'0'), "fetchAll");
    $participant_arrays = $sfpv->getParticipantArrays();

    if(sizeof($participant_arrays)>0)
        $sfpv->getStateCouncilParticipantSection($participant_arrays);*****/

}
add_shortcode('getStateCouncilParticipants', 'get_event_participants');

function get_event_sponsors($atts) 
{
    $id = $atts["id"];
    
    $df = new Database();
    //Salesforce Sponsors
	$sfsv = new SalesforceSponsorshipViewer($df, "salescache_sponsorships", array('Event'=>$id, 'IsDeleted'=>'0'), "fetchAll");
    $sponsor_arrays = $sfsv->getSponsorshipArrays();
    
    $size = sizeof($sponsor_arrays);

    if($size>0)
        $sfsv->getStateCouncilSponsorsSection($sponsor_arrays);
}
add_shortcode('getStateCouncilOrganizations', 'get_event_sponsors');

function get_bod_members($atts) 
{
    $id = $atts["id"];
    
    $df = new Database();
    //Salesforce Council 
	$sfbod = new SalesforceBoardofDirectorsViewer($df, "salescache_council_leaders", array('Council'=>$id, 'Active_Member'=>'1', 'IsDeleted'=>'0'), "fetchAll");
    
    //this should always be false until board of directors have the correct bod title positions
    $council_arrays = $sfbod->getCouncilArrays(FALSE);


    $size = 0;
    $size += sizeof($council_arrays);

    //echo "size equals "+$size;
    
    if($size>0)
        $sfbod->getBODStateCouncilSection($council_arrays);
}
add_shortcode('getStateCouncilBoardofDirectors', 'get_bod_members');

function get_our_partners($atts) 
{
    //this function returns our partners for the council id
    $id = $atts["id"];
    
    $df = new Database();
    //Salesforce Council
    $sfcv = new SalesforceCouncilViewer($df, "salescache_councils", array('sales_id'=>$id), "fetch");
    $council_name = $sfcv->getCouncilName();

    //Salesforce Partners 
    $sfpv = new SalesforcePartnerViewer($df, "salescache_organizations", array('Assigned_Council'=>$council_name, 'Current_Partner'=>'1', 'IsDeleted'=>'0'), "fetchAll");
    $html = $sfpv->getStateCouncilOurPartnerSection();
    echo $html;
}
add_shortcode('getStateCouncilPartners', 'get_our_partners');

function get_ab_members($atts) 
{
    $id = $atts["id"];
    
    $df = new Database();

    //Salesforce Chapters 
    $sf_chapter = new SalesforceChapterViewer($df, "salescache_chapters", array('Council'=>$id, 'Active_chapter'=>'1', 'IsDeleted'=>'0'), "fetchAll");
    $chapters = $sf_chapter->getChapters();   

    $count = $i = 0;
    foreach($chapters as $chapter)
    {
        $sfab = new SalesforceAdvisoryBoardViewer($df, "salescache_advisory_board", array('Chapter'=>$chapter['sales_id'], 'Active_Member'=>'1', 'IsDeleted'=>'0'), "fetchAll");
        $s = sizeof($sfab->getCouncilMembers());
        
        if($s==0)
            unset($chapters[$i]);
        
        $count += $s;
    	$ab_members[] = $sfab->getContacts($chapter['sales_id']);
    	$i++;
    }


    if(sizeof($chapters)>1)
    {//tab version
        if($count>0)
            echo $sfab->getAdvisoryBoardTabSection($chapters, $ab_members);
    }
    else
    {//single version
        if($count>0)
            echo $sfab->getAdvisoryBoardSection($ab_members);
    }
}
add_shortcode('getStateCouncilAdvisoryBoard', 'get_ab_members');

function get_staff_members($atts) 
{
    //this function returns council / staff members one at a time
    $id = $atts["id"];
    
    $df = new Database();
    //Salesforce Council 
	$sfsv = new SalesforceStaffViewer($df, "salescache_staff", array('sales_id'=>$id), "fetch");
    $html = $sfsv->getStateCouncilHTML();
    return $html;

}
add_shortcode('getStaffInfo', 'get_staff_members');

function get_contact_member($atts) 
{
    //this function returns council / staff members one at a time
    $id = $atts["id"];
    
    $df = new Database();
    //Salesforce Council 
	$sfsv = new SalesforceContactViewer($df, "salescache_contacts", array('sales_id'=>$id), "fetch");
    $html = $sfsv->getHTML();
    return $html;

}
add_shortcode('getContact', 'get_contact_member');

function get_sf_sidebar($atts) 
{
	$id = $atts["id"];
	$type = $atts["type"]; 
	
	$date = date("Y-m-d"); 
	
    $df = new Database();
    $sfc = new SalesforceCouncilViewer($df, "salescache_councils", array('sales_id'=>$id), "fetch");
    $council = $sfc->getCouncil();
	$council_name = $council['Name'];
    
    $sfev = new SalesforceEventViewer($df, "salescache_events", array('isSFConnected'=>'1', 'IsDeleted'=>'0', 'Status'=>'Date Confirmed'), "fetchAll", array('values'=>array('Date'), 'order'=>'ASC'));
	$events = $sfev->getEvents();
	
	//type0 section
	{
	$series = [];
	
	foreach($events as $event)
	{
	    if($event['Series']!='Other'&&$event['thumbnail_url']!='')
	    {
	        $bool=FALSE;
	        $pos = strpos($event['Event_Council'], $council_name);
	        if($pos !== false)
	            $bool=TRUE;
	        else if($event['Council']==$id)
	            $bool=TRUE;
	        
	        if($bool)
	        {
                if($event['Date']>$date)
                {
                    $s = $event['Series'];
                    if($event['Series']=="One Time Event Series")
                        $s = "Diversity";        
                    
                    if (!(in_array($s, $series)))
                        array_push($series, $s);
                }
	        }
	    }
	}
	sort($series);

    foreach($series as $result)
        $series_html .= '<li class="filterCat" class="cat-item"><a class="categories" href="javascript:void(0)">'.$result.'</a></li>';
        
    }
    if($type==0)
        return $series_html;
	
	//type1 section
	{
	$sf_chapter = new SalesforceChapterViewer($df, "salescache_chapters", array('Council'=>$id), "fetchAll");
	$chapters = $sf_chapter->getChapters();
	
    $chapter_locations = [];
    foreach($chapters as $chapter)
        if (!(in_array($chapter['Location_City'], $chapter_locations)))
            array_push($chapter_locations, $chapter['Location_City']);
	
	$location_events = [];
	foreach($events as $event)
	{
	    if($event['Series']!='Other'&&$event['thumbnail_url']!='')
	    {
	        $bool=FALSE;
	        $pos = strpos($event['Event_Council'], $council_name);
	        if($pos !== false)
	            $bool=TRUE;
	        else if($event['Council']==$id)
	            $bool=TRUE;
	        
	        if($bool)
				if($event['Date']>=$date)
					array_push($location_events, $event);
	    }
	     
	}
	
    $locations = [];
    $el = [];
	
	foreach($location_events as $event)
	{
	    //echo $event["Name"]."<br>";
        $venue = strtolower($event['Venue']);
        $toe = strtolower($event['Type_of_Event']);
        
        if (strpos($venue, 'virtual') !== false||($event['Type_of_Location']=="Virtual"))
        {
            if (!(in_array("Virtual", $el)))
    		    array_push($el, "Virtual");
        }
        else if (strpos($toe, 'national') !== false)
        {
            if (!(in_array("National", $el)))
    		    array_push($el, "National");
        }
        else if (strpos(strtolower($event['Type_of_Region']), 'regional') !== false)
        {
            if (!(in_array("Regional", $el)))
    		    array_push($el, "Regional");
        }
        else
        {
            //echo $event['Name']." - ".$event['Chapter']."<br>";
    	    if($event['Chapter']!="")
    	    {
    	        $sf_chapter1 = new SalesforceChapterViewer($df, "salescache_chapters", array('sales_id'=>$event['Chapter']), "fetch");
                $chapter = $sf_chapter1->getChapter();
    
        		if(in_array($chapter['Location_City'], $chapter_locations))
        		{
        		    if(!in_array($chapter['Location_City'], $locations))
        			    array_push($locations, $chapter['Location_City']);
        		}
        		else
        		{
                    if (!(in_array("Non-Local", $el)))//chapter['council']!= council[sales_id]
                        array_push($el, "Non-Local");//Non-Local
        		}
    	    } 
            else
            {
                if($event['Council']==$id)//council == council
                {
        		    if(!in_array($council['Location'], $locations))
        			    array_push($locations, $council['Location']);
                }
                else
                {
                    if (!(in_array("Non-Local", $el)))
                        array_push($el, "Non-Local");//Non-Local
                }
            }
        }
	}
    
    $locations = array_merge($locations, $el);
    $html_loc = "";
    
    foreach($locations as $location)
    	$html_loc .= '<li class="filterLoc" class="cat-item"><a class="locations" href="javascript:void(0)">'.$location.'</a></li>';
    }
    if($type==1)
        return $html_loc;
    
    //type2 section
    {
        
    $upcoming_years=[];
    $upcoming_months=[];
    $upcoming_set=[];
    $year_index;
    $upcoming="";
	foreach($location_events as $event)
	{
    	if($event['Date']>=$date)
    	{
            $event_year = date("Y", strtotime($event['Date']));
            $event_month = strtoupper(date("F", strtotime($event['Date'])));
    
    		if(!isset($year_index))
    		{
    			$year_index=$event_year;
    			$upcoming = '<ul class="upcoming_events_section"><li class="cat-item event_year"><a class="upcoming_events upcoming_year" href="javascript:void(0)">'.$event_year.'</a></li>';
    		}
    		
        	if($year_index!=$event_year)//2020!=2021
        	{
        		$upcoming .= '</ul><ul class="upcoming_events_section"><li class="cat-item event_year"><a class="upcoming_events upcoming_year" href="javascript:void(0)">'.$event_year.'</a></li>';
        		$year_index=$event_year;
    			$upcoming_months=[];
        	}
        
        	if(!(in_array($event_month, $upcoming_months)))
    		{
        		array_push($upcoming_months, $event_month);
    			$upcoming .= '<p id="filterMonth" class="tag-cloud-link tag-link-42 tag-link-position-4 upcoming_events upcoming_months">'.$event_month.'</p>';
    		}
    	}
	}
	
    $upcoming .= '</ul>';
    }
    if($type==2)
        return $upcoming;
	
	//type3 section
	{
    $sfev = new SalesforceEventViewer($df, "salescache_events", array('IsDeleted'=>'0', 'Status'=>'Date Confirmed'), "fetchAll", array('values'=>array('Date'), 'order'=>'ASC'));
	$events = $sfev->getEvents();

	$events1 = [];
	foreach($events as $event)
	{
	    if($event['Series']!='Other')
	    {
	        $bool=FALSE;
	        $pos = strpos($event['Event_Council'], $council_name);
	        if($pos !== false)
	            $bool=TRUE;
	        else if($event['Council']==$id)
	            $bool=TRUE;
	        
	        if($bool)
				array_push($events1, $event);
	    }
	     
	}
    
    //once old events are in SF remove this next 2 lines
    $old_events = getPastEvents();
    $new_events = array_merge($old_events, $events1);

    $past_events;
    $past_years = []; 
    
	foreach($new_events as $event)
	{
        $event_year = date("Y", strtotime($event['Date']));
        $event_date = date("Y-m-d", strtotime($event['Date'] . ' +1 day'));
    
        //echo "<script>console.log('event-date ".$event_date."')</script>";
        //echo "<script>console.log('todays-date ".$date."')</script>";
    
        if($event_date<=$date)
        {
            if(!(in_array($event_year, $past_years)))
                array_push($past_years, $event_year);
    	}
	}
	rsort($past_years);
	//echo "<script>console.log('".json_encode($past_years)."')</script>";
	
    $past = "";
    foreach($past_years as $year)
        $past .= '<li class="filterPast" class="cat-item event_year"><a href="javascript:void(0)" class="tag-cloud-link tag-link-39 tag-link-position-1 past_events" style="font-size: 22pt">'.$year.'</a></li>';
        
	}
    if($type==3)
        return $past;
        
}
add_shortcode('sf-sidebar', 'get_sf_sidebar');

function get_sf_events($atts) 
{
    $id = $atts["id"];
    
    $df = new Database();
    $sfc = new SalesforceCouncilViewer($df, "salescache_councils", array('sales_id'=>$id), "fetch");
    $council = $sfc->getCouncil();
	$council_name = $council['Name']; 
    
    
    $sf_chapter = new SalesforceChapterViewer($df, "salescache_chapters", array('Council'=>$id), "fetchAll");
    $chapters = $sf_chapter->getChapters();
    
    $chapter_locations = [];
    foreach($chapters as $chapter)
    {
        $city = str_replace(' Diversity Council', '', $chapter['Location_City']);
        if (!(in_array($city, $chapter_locations)))
            array_push($chapter_locations, $city);
    }

    $sfev = new SalesforceEventViewer($df, "salescache_events", array('isSFConnected'=>'1', 'IsDeleted'=>'0', 'Status'=>'Date Confirmed'), "fetchAll", array('values'=>array('Date'), 'order'=>'ASC'));
	$events = $sfev->getEvents();
	$events1 = array();
	
	foreach($events as $event)
	{
	    if($event['Series']!='Other'&&$event['thumbnail_url']!='')
	    {
	        $bool=FALSE;
	        $pos = strpos($event['Event_Council'], $council_name);
	        if($pos !== false)
	            $bool=TRUE;
	        else if($event['Council']==$id)
	            $bool=TRUE;
	        
	        if($bool)
                array_push($events1, $event);
	    }
	}
    
    
    $old_events = getPastEvents();
    $new_events = array_merge($events1, $old_events);
    $events = sf_calendar($df, $new_events, $chapters, $council, $id); 
    
    return $events;
}
add_shortcode('sf-calendar', 'get_sf_events');

/**************************************************************************************** */



/****************************************************************************************** */

function sf_event_slider($atts) 
{
    $id = $atts["id"];
	    
    $df = new Database();
    $sfc = new SalesforceCouncilViewer($df, "salescache_councils", array('sales_id'=>$id), "fetch");
    $council = $sfc->getCouncil();
	$council_name = $council['Name']; 
   
    $todays_date = date("F j, Y"); 
    $sfev = new SalesforceEventViewer($df, "salescache_events", array('isSFConnected'=>'1', 'IsDeleted'=>'0', 'Status'=>'Date Confirmed'), "fetchAll", array('values'=>array('Date'), 'order'=>'ASC'));
	$events = $sfev->getEvents();
	
    $upcoming_events = array();
	$a=0;
	
	foreach($events as $event)
	{
        $ec = explode(",", $event['Event_Council']);
        $ec = array_map('trim', $ec);
	    
        if(in_array($council_name, $ec)||$event['Council']==$id)
        {
            if($a<6)
            {
                $eventdate = date_create($event['Date']);
                $eventdate = $eventdate->format('F d, Y');
                $curtimestamp1 = strtotime($eventdate);
                $curtimestamp2 = strtotime($todays_date);
    	    
	            if($curtimestamp1>=$curtimestamp2)
                {
            	    if($event['Status']=='Date Confirmed'&&$event['thumbnail_url']!='')
            	    {
                        $upcoming_events[$a]['date'] = $event['Formatted_Date'];
                        
                        if($event['Series']=="One Time Event Series"||$event['Name_of_Event']!="")
                            $series = $event['Name_of_Event'];
                        else
                            $series = $event['Series'];
                            
                        $series = str_replace("<br>", " ", $series);
                        $upcoming_events[$a]['name'] = $series;
                        
                        $venue = strtolower($event['Venue']);
                        if (isVirtual($event))
                            $upcoming_events[$a]['virtual'] = "Virtual ";
                        else
                            $upcoming_events[$a]['virtual'] = "<br>";

                        $upcoming_events[$a]['thumbnail'] = $event['thumbnail_url'];             
							
					    if($event['Type_of_Region']=="Regional")
					    {
					        $upcoming_events[$a]['virtual'] = (isVirtual($event)) ? "Virtual ".$event['Series'] : "Regional ".$event['Series'];
					        $upcoming_events[$a]['name'] = $event['Series'];
					    }
                        else
                        {    
                            if($event['Name_of_Event']=="")
                            {
                                if($event['Chapter']!="")
                                {
                                    $sfch = new SalesforceChapterViewer($df, "salescache_chapters", array('sales_id'=>$event['Chapter']), "fetch");
                                    $chapter = $sfch->getChapter();
                                    
                                    if (isVirtual($event)&&$event['Series']!="DiversityFIRST Executive Certification Program")
                                        $upcoming_events[$a]['virtual'] .= "- ";
                                        
                                    if (strpos($upcoming_events[$a]['virtual'], '<br>') !== false)
                                        $upcoming_events[$a]['virtual'] = $chapter['Location_City'];
                                    else
                                        $upcoming_events[$a]['virtual'] .= $chapter['Location_City'];
                                }
                            }
                        }
                        
                        $price = $event['Price'];
                        if(empty($price))
                            $upcoming_events[$a]['price'] = "Contact for More Info";
                        else
                            $upcoming_events[$a]['price'] = "Individual Registration - $".$event['Price'];
                                                    
                        if($event['Series']=="DiversityFIRST Certification Program" || $event['Series']=="DiversityFIRST Executive Certification Program")
                            $upcoming_events[$a]['price'] = "Contact for More Info";
						
                        if($event['Series']=="DiversityFIRST Virtual Suite - Academy" || $event['Series']=="DiversityFIRST Virtual Suite - Workshop")
                        {
                            $upcoming_events[$a]['virtual'] = $event['Series'];  
                            $upcoming_events[$a]['name'] = $event['Name_of_Event'];
                        }
                        
                        if($event['Website']=="")
                            $upcoming_events[$a]['website'] = "javascript:void()";
                        else
                            $upcoming_events[$a]['website'] = $event['Website'];
                            
                        $upcoming_events[$a]['type'] = ($event['Type_of_Event']=="National") ? "National" : "";
                        
                        $a++;
            	    }
                }
            }
        }
	}

    if(sizeof($upcoming_events)!=0)
    {
    
        echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>';
        echo '<script type="text/javascript" src="'.$council['Website'].'/wp-content/themes/NDC-THEME/library/js/owl.carousel.js"></script>';
        
        echo '<div id="owl-carousel-event-slider">';
        echo '<div class="row">';
        echo '<div class="large-12 columns">';
        echo '<div class="loop owl-carousel owl-theme owl-carousel-event-slider" id="owl-carousel-event-slider-loop">';
        
        foreach($upcoming_events as $event)
        {
        	echo('
        	<div class="item">');
        	
        		if($event['website']=="javascript:void()")
        		{
        		    echo('<a href="javascript:void();">');
        		}
        		else
        		{
        		    echo('<a href="'); echo $event['website']; echo('" target="_blank">');
        		}
        		
        		if($event['type']=="National")
        		{
        		    echo ('<div class="national-ctn" style="padding: 2px 10px; top: 20px; left: 0px; position: absolute; z-index: 9999; background-color: #006cff;"><h3 class="national-h6 t-entry-title h6" style=" text-align: center; padding: 6px; text-transform: uppercase; margin: 0px; color: #fff; letter-spacing: 1px; text-align: left; font-weight: 700;">National Event</h3></div>');
        		}
        		
        		
        		echo('<img src="'); echo $event['thumbnail']; echo('" width="500" height="300" alt="'); echo $event['name']; echo('" title="'); echo $event['name']; echo('"></a>
        		<h4 class="t-entry-title h3 virtual" style="font-size: 20px;">'); echo $event['virtual']; echo('</a></h4>
        		<h4 class="t-entry-title h3 title-event" style="font-size: 20px">');
        		
        		if($event['website']=="javascript:void()")
        		{
        		    echo('<a href="javascript:void();">');echo $event['name']; echo('</a></h4>');
        		}
        		else
        		{
        		    echo('<a href="'); echo $event['website']; echo('" target="_blank">');echo $event['name']; echo('</a></h4>');
        		}
        		
        		echo('<div class="t-entry-excerpt">
        			<h4 class="t-entry-title date" style="font-size: 18px">'); echo $event['date']; echo('</h4>
        			<div class="t-entry-excerpt contact">
        			    <h4 style="font-size: 18px">'); 
        			    
            			    if($event['price']=="Contact for More Info")
            			        echo "<a href='http://".$_SERVER[HTTP_HOST]."/contact-us/' target='_blank'>Contact for More Info</a>";
            			    else
                			    echo $event['price']; 
        			    
        			    echo('</h4>
                    </div>
        		</div>
        	</div>');
        }
        echo '</div></div></div></div>';
    
    }
    else if (sizeof($upcoming_events)==0)
    {
        echo '
        <script>
        var list = document.getElementsByClassName("event-slider");
        for (index = 0; index < list.length; ++index) 
                    list[index].style.display ="none";
        </script>';
        
    }
}
add_shortcode('sf-event-slider', 'sf_event_slider');

function isVirtual($info)
{
    $isvirtual = false;
    
    if(strpos(strtolower($info['Type_of_Location']), 'virtual') !== false || strpos(strtolower($info['Venue']), 'virtual') !== false)
    {
        $isvirtual = true;
    }
    
    
    
    return $isvirtual;
}

/*****************************/

function getting_all_events1($atts) 
{
	$df = new Database();
	//Salesforce Event
    $sfev = new SalesforceEventViewer($df, "salescache_events", array('IsDeleted'=>'0', 'isSFConnected'=>'1', 'Status'=>'Date Confirmed'), "fetchAll", array('values'=>array('Date'), 'order'=>'ASC'));
	$events = $sfev->getEvents();
    $html_txt = array();
	$html = array();
	$eventsarray = array();
	$a=0;
    $i=0;

    foreach($events as $event)
	{
		$name = strtolower($event['Venue']);
		
		if($event['Series']!="Other")
		{
			$todays_date = date("F j, Y");     
			$event_date = date("F j, Y", strtotime($event['Date']));
			$curtimestamp1 = strtotime($event_date);
			$curtimestamp2 = strtotime($todays_date);
	
	        //echo $event_date." ".$todays_date."<br>";
	
			if($curtimestamp1>$curtimestamp2)
			{   
            	$sfcv = new SalesforceCouncilViewer($df, "salescache_councils", array('sales_id'=>$event['Council']), "fetch");
            	$council = $sfcv->getCouncil();
            	
        		$name = strtolower($event['Venue']);
        		$title ="";
        		
        		if(strpos($name, 'virtual') !== false || strpos(strtolower($event['Type_of_Location']), 'virtual') !== false) 
        		{
        		    $title = "Virtual  - ";
        		}
				
				if($event['Series']=="DiversityFIRST Virtual Suite - Workshop" || $event['Series']=="DiversityFIRST Virtual Suite - Academy")
				{
				    $title .= $event['Series'];
				}
				else if($event['Series']=="One Time Event Series"||$event['Name_of_Event']!="")
				{
					$name = str_replace("<br>", " ", $event['Name_of_Event']);
					$title .= $name;
                    if($event['Type_of_Region']=="Regional")
                    {
                        $title.= " ".$event['Series'];
                    }
				}
				else if($event['Series']=="DiversityFIRST Certification Program" || $event['Series']=="Women in Leadership Symposium")
				{//we should be able one day to take off this if specfic series and have the chapter location names show up
					
                    if($event['Chapter']!="")
					{
                    	$sfchv = new SalesforceChapterViewer($df, "salescache_chapters", array('sales_id'=>$event['Chapter']), "fetch");
                    	$chapter = $sfchv->getChapter();
						
						$title .=$event['Series']." - ".$chapter['Location_City'].", ".$event['State'];
					}
					else
					{
						$title .= $event['Series'];
					}
				}
				else
					$title .= $event['Series'];
					
  $web = is_numeric($event['Price'])&&$price!="Contact For More Information" ?  $event['Website'] : $council_website."/contact-us/";
			
				$html_txt[$a]['date'] = date("Y, m, d", strtotime($event['Date']));
                $html_txt[$a]['formatted_date'] = $date;
                $html_txt[$a]['Name'] = $name;
                $html_txt[$a]['Series'] = $series;
                $html_txt[$a]['Location'] = $location;
                $html_txt[$a]['Website'] = $event['Website'];
                $html_txt[$a]['Price'] = $price;
                $html_txt[$a]['ProgramBookURL'] = $event['ProgramBookURL'];
                $html_txt[$a]['Photos_URL'] = $event['Photos_URL'];
                $html_txt[$a]['ArticleLinkURL'] = $event['ArticleLinkURL'];
                $html_txt[$a]['IMG_URL'] = $event['thumbnail_url'];
                
                $html_txt[$a]['text'] = '<div class="tmb tmb-woocommerce tmb-iso-w4 tmb-iso-h4 tmb-light tmb-overlay-text-anim tmb-overlay-anim tmb-content-center tmb-image-anim tmb-bordered" style="position:relative;">';
                
                if($event['Type_of_Event']=="National")
                {
                    $html_txt[$a]['text'] .= '<div class="national-ctn" style="padding: 2px 10px; top: 20px; left: 0px; position: absolute; z-index: 9999; background-color: #006cff;">';
                    $html_txt[$a]['text'] .= '<h3 class="national-h6 t-entry-title h6" style=" text-align: center; padding: 6px; text-transform: uppercase; margin: 0px; color: #fff; letter-spacing: 1px; text-align: left; font-weight: 700;">National Event</h3></div>';
                }
                
                $html_txt[$a]['text'] .= '<div class="t-inside" style="opacity: 1"><div class="t-entry-visual"><div class="t-entry-visual-tc"><div class="t-entry-visual-cont"><div class="dummy" style="padding-top: 70%"></div>';
                
                if($event['Website']=="")
                    $html_txt[$a]['text'] .= '<a tabindex="-1" class="pushed" target="blank" data-lb-index="0">';
                else
                    $html_txt[$a]['text'] .= '<a tabindex="-1" href="'.$event['Website'].'" class="pushed" target="blank" data-lb-index="0">';
                
                $html_txt[$a]['text'] .= '<div class="t-entry-visual-overlay"><div class="t-entry-visual-overlay-in style-dark-bg" style="opacity: 0.5"></div></div>';
                $html_txt[$a]['text'] .= '<img src="'.$event['thumbnail_url'].'" width="720" height="960" alt="'.$img_name.'" title="'.$img_name.'"></a>';
                
                
                $html_txt[$a]['text'] .= '<div class="add-to-cart-overlay"><a target="_blank" href="'.$web.'" rel="nofollow">'.$price.'</a></div></div></div>';
                $html_txt[$a]['text'] .= '</div><div class="t-entry-text"><div class="t-entry-text-tc"><div class="t-entry">';
                $html_txt[$a]['text'] .= '<h3 class="t-entry-title h6"><a href="'.$event['Website'].'" target="blank">'.$name.'</a></h3>';
                $html_txt[$a]['text'] .= '<p class="t-entry-meta"><span class="t-entry-date">'.$event['Formatted_Date'].'</span></p>';
                
                $html_txt[$a]['text'] .= '<div id="past_event_info">';
                
                
                if($event['ProgramBookURL']!="")
                {
                	$html_txt[$a]['text'] .= '<div class="info-tab"><p class="t-entry-meta" style="margin-top: 0px"><span class="t-entry-past_program"><a href="'.$event['ProgramBookURL'].'" target="blank">View Program Book</a></span></p></div>';
                }
                if($event['Photos_URL']!="")
                {
                	$html_txt[$a]['text'] .= '<div class="info-tab"><p class="t-entry-meta" style="margin-top: 0px"><span class="t-entry-past_photos"><a href="'.$event['Photos_URL'].'" target="blank">View Photos</a></span></p></div>';
                }
                if($event['ArticleLinkURL']!="")
                {
                	$html_txt[$a]['text'] .= '<div class="info-tab"><p class="t-entry-meta" style="margin-top: 0px"><span class="t-entry-article_link"><a href="'.$event['ArticleLinkURL'].'" target="blank">View Article</a></span></p></div>';
                }
                
                $html_txt[$a]['text'] .= '</div><hr></div></div></div></div></div>';
                
                array_push($eventsarray, $html_txt[$a]);
                               
                $a++;
            }
        }
    }
        
	
	sort($eventsarray);

	//$html_txt = "<table id='virtual-suite'>";
	//$html_txt .= "<tr><th>Council</th><th>Event</th><th>Date</th>";
	//$html_txt .= "<th>Virtual</th>";
	//$html_txt .= "</tr>";
/*****
	 foreach($eventsarray as $events)
    {
		$html1_txt .= $events['text'];
	}

	//$html_txt .= "</table>";

	return $html1_txt;

}
*/
	return(json_encode($eventsarray)); }
	
add_shortcode('GetAllEvents1', 'getting_all_events1');

 
/******************************************************************* */

function get_sf_events_cal($atts) 
{
    $id = $atts["id"];
    
    $df = new Database();
    $sfc = new SalesforceCouncilViewer($df, "salescache_councils", array('sales_id'=>$id), "fetch");
    $council = $sfc->getCouncil();
	$council_name = $council['Name']; 
    
    
    $sf_chapter = new SalesforceChapterViewer($df, "salescache_chapters", array('Council'=>$id), "fetchAll");
    $chapters = $sf_chapter->getChapters();
    
    $chapter_locations = [];
    foreach($chapters as $chapter)
    {
        $city = str_replace(' Diversity Council', '', $chapter['Location_City']);
        if (!(in_array($city, $chapter_locations)))
            array_push($chapter_locations, $city);
    }

    $sfev = new SalesforceEventViewer($df, "salescache_events", array('isSFConnected'=>'1', 'IsDeleted'=>'0', 'Status'=>'Date Confirmed'), "fetchAll", array('values'=>array('Date'), 'order'=>'ASC'));
	$events = $sfev->getEvents();
	$events1 = array();
	
	foreach($events as $event)
	{
	    if($event['Series']!='Other'&&$event['thumbnail_url']!='')
	    {
	        $bool=FALSE;
	        $pos = strpos($event['Event_Council'], $council_name);
	        if($pos !== false)
	            $bool=TRUE;
	        else if($event['Council']==$id)
	            $bool=TRUE;
	        
	        if($bool)
                array_push($events1, $event);
	    }
	}
    
    
    $old_events = getPastEvents();
    $new_events = array_merge($events1, $old_events);
    $events = sf_calendar1($df, $new_events, $chapters, $council, $id); 
    
    return $events;
}
add_shortcode('sf-calendar1', 'get_sf_events_cal');
/*************************************** */




?>
