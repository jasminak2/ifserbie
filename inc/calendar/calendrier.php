<?php
  require_once dirname(__FILE__) . "/../../../../../wp-load.php";
  include "classe_dates.php";
  include "classe_calendrier.php";


  function getAllDays($start, $end, $aslist = true) {
    // convert the strings we get in to a timestamp
    $start = strtotime($start);
    $end = strtotime($end);
    
    // this will make sure there isn't an infinite loop by deciding
    // which way (back or forwards one day) the loop should go
    // based on whether the start date is before the end date or not
    $whichway = ($start < $end) ? "tomorrow" : "yesterday";
    
    // we'll increment $curday so set it to the start date
    $curday = $start;
    
    // initialise the $days array and add our first date to it (could be done in one line but looks nicer like this)
    $days = array();
    $days[] = date("Y-m-d", $curday);
    
 /*------ IZMENA! Ne prikazivati sve dane u kalendaru vec samo prvi!  
    // iterate through the days until we reach the end date
    while ($curday != $end) {
      // get the 'next' day in the sequence (might be forwards OR backwards one day)
      $curday = strtotime($whichway, $curday);
      $days[] = date("Y-m-d", $curday);
    }  
---------*/
    
    // if we only wanted an array back, return the array now
    if ($aslist === false) return $days;
    
    // if we wanted a formatted list...
    // inititalise empty string for the list
    $daylist = "";
    
    // go through each date in the array
    foreach ($days as $day) {
      // add it to the string and stick a comma on the end
      $daylist .= $day.", ";
    }
    
    // take the trailing comma-space off
    $daylist = substr($daylist, 0, -2);
    
    return $daylist;
  }

  
  $obj_cal = new classe_calendrier('ifcalendar');
  
  //langue
  global $sitepress, $sitepress_settings;
  $default_lg = isset($sitepress) ? $sitepress->get_default_language() : get_site_lang();

  if(version_compare(PHP_VERSION, '5.3.0') <= 0) {      
    $lang = defined('WPLANG') ? substr(WPLANG, 0, strpos(WPLANG, '_')) : 'en';
  } else {
    $lang = defined('WPLANG') ? strstr(WPLANG, '_', true) : 'en';
  }
  $pathlang = '';
  $default_lg = !$default_lg ? $lang : $default_lg;

  
  if(defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE ) { 
    $lang = isset($_POST['lang']) ? strtolower(substr($_POST['lang'], 0,2)) : ICL_LANGUAGE_CODE;
    $pathlang = ICL_LANGUAGE_CODE == $default_lg ? '' :  ICL_LANGUAGE_CODE;
  }
  
  if(isset($_POST['lang'])) {
    $postlang = strtolower(substr($_POST['lang'], 0,2));
    if($postlang != $default_lg) { $pathlang = $postlang; }
  }
  /* 
    $sitepress_settings['language_negotiation_type'] can have 3 values: 
    - 1 == languages in different folder (/en, /fr...)
    - 2 == languages in different domains
    - 3 == mysite.com?lang=fr
  */
  
  $pathJ = get_bloginfo('wpurl') . "/%04s/%02s/%02s/";
  $pathM = get_bloginfo('wpurl') . "/%04s/%02s/";
  
  if(isset($sitepress_settings['language_negotiation_type'])) { 
    //last verif before output link
    $pathlang = $pathlang == $sitepress_settings['default_language'] ? '' : $pathlang;
  
    switch ($sitepress_settings['language_negotiation_type']) {
      case 1:
        $pathlang = $pathlang ? '/'.$pathlang : '';
        $pathJ = get_bloginfo('wpurl') . $pathlang . "/%04s/%02s/%02s/";
        $pathM = get_bloginfo('wpurl') . $pathlang . "/%04s/%02s/";
      break;
      case 2:
        $pathlang = '';
      break;
      case 3:
        $pathlang = $pathlang ? '?lang='.$pathlang : '';
        $pathJ = get_bloginfo('wpurl') . "/%04s/%02s/%02s/" . $pathlang;
        $pathM = get_bloginfo('wpurl') . "/%04s/%02s/". $pathlang;
      break;
    }
  }

  $obj_cal->setLangue(strtoupper($lang));
  $lang_exist = $obj_cal->getLangue();
  $obj_cal->setLangue($lang_exist);
  $obj_cal->setJourCourt();

  $obj_cal->afficheMois();
  $obj_cal->afficheSemaines(false);
  $obj_cal->afficheJours(true);
  $obj_cal->afficheNavigMois(true);
  
  $obj_cal->activeLienMois();
  //$obj_cal->activeLiensSemaines();

  $obj_cal->activeJoursPasses();
  $obj_cal->activeJourPresent();
  $obj_cal->activeJoursFuturs();
  
  $obj_cal->activeJoursEvenements();
  
  $obj_cal->setFormatLienJours($pathJ);    
  $obj_cal->setFormatLienMois($pathM);
  
  $obj_cal->activeAjax("ajax_calendrier",get_bloginfo('wpurl')."/wp-content/themes/ifserbie/inc/calendar/calendrier.php");

   global $wpdb;
   $curLang = isset($_POST['lang']) ? $_POST['lang'] : str_ireplace('-', '_', get_bloginfo('language'));
   
   //get language code if multilang
   $tablemap = $wpdb->prefix.'icl_locale_map';
   $query_lang = "SELECT code FROM $tablemap WHERE locale = '$curLang'";
   $code_lang = $wpdb->get_var("SHOW TABLES LIKE '$tablemap'") != $tablemap ? NULL : $wpdb->get_row($query_lang);

   if($code_lang){
     $tabletrans = $wpdb->prefix.'icl_translations';
     $query_trans = "SELECT * FROM $tabletrans WHERE language_code = '$code_lang->code' AND element_type = 'post_post'";
     $results_trans = $wpdb->get_results($query_trans);
  }
  
  $query_str = "
    SELECT wposts.* 
    FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
    WHERE wposts.ID = wpostmeta.post_id 
    AND wposts.post_status = 'publish'
    AND wpostmeta.meta_key = 'if_events_startdate' 
    AND wposts.post_type = 'post' 
    ORDER BY wpostmeta.meta_value DESC
    ";
    
  $entries = $wpdb->get_results( $query_str, OBJECT );

  $i = 0;
  
  if(isset($results_trans)){
    foreach($entries as $k => $o){
  
     foreach($results_trans as $kt => $ot){
     if($o->ID == $ot->element_id && $ot->language_code == $code_lang->code){
  
      $data = get_meta_raw_if_post($o->ID);
      $start = !empty($data['start']) ? date('Y-m-d',$data['start']) : NULL;
      $end = !empty($data['end']) ? date('Y-m-d',$data['end']) : NULL;
      $time = !empty($data['time']) ? $data['time'] : NULL;
      $title = $data['title'];
      
      if($start !== $end) {
        $period = getAllDays($start, $end, $aslist = false);
        
        foreach($period as $d){
          $events[$i][$d] = $title;
        }
        
      } else {
        $events[$i][$start] = $title;
      }
      $i++;
     }
     }
    }
  }
  else {
    foreach($entries as $k => $o){
    
      $data = get_meta_raw_if_post($o->ID);
      $start = !empty($data['start']) ? date('Y-m-d',$data['start']) : NULL;
      $end = !empty($data['end']) ? date('Y-m-d',$data['end']) : NULL;
      $time = !empty($data['time']) ? $data['time'] : NULL;
      $title = $data['title'];
      
      if($start !== $end) {
        $period = getAllDays($start, $end, $aslist = false);
        
        foreach($period as $d){
          $events[$i][$d] = $title;
        }
        
      } else {
        $events[$i][$start] = $title;
      }
      $i++;
    }

  }
  
  //add events to the calendar
  if(isset($events) && $events){
    foreach($events as $k => $e){
      foreach($e as $day => $t){
      $obj_cal->ajouteEvenement($day,$t.'<br />');
      }
    }
  }

  print ($obj_cal->makeCalendrier((isset($_POST['annee']) ? $_POST['annee'] : date("Y")),(isset($_POST['mois']) ? $_POST['mois'] : date("m"))));


?>
