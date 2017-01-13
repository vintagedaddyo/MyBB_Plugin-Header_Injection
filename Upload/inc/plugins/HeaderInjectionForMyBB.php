<?php
/*
 * MyBB: Header Injection For MyBB
 *
 * File: HeaderInjectionForMyBB.php
 * 
 * Authors: Jimmy Peña, Vintagedaddyo
 *
 * MyBB Version: 1.8
 *
 * Plugin Version: 1.0.1
 * 
 */

// disallow direct loading of this file

if (!defined("IN_MYBB")) {
  die("Direct loading of this file is not allowed.");
}

// hook into start function, to inject header code

//$plugins->add_hook('index_start', 'injectheadercode');

$plugins->add_hook('global_end','injectheadercode');

// required by MyBB
// info function must have same name as plugin file
function HeaderInjectionForMyBB_info() { 
    global $lang;

    $lang->load("HeaderInjectionForMyBB");
    
    $lang->HeaderInjectionForMyBB_Desc = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="float:right;">' .
        '<input type="hidden" name="cmd" value="_s-xclick">' . 
        '<input type="hidden" name="hosted_button_id" value="AZE6ZNZPBPVUL">' .
        '<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">' .
        '<img alt="" border="0" src="https://www.paypalobjects.com/pl_PL/i/scr/pixel.gif" width="1" height="1">' .
        '</form>' . $lang->HeaderInjectionForMyBB_Desc;

    return Array(
        'name' => $lang->HeaderInjectionForMyBB_Name,
        'description' => $lang->HeaderInjectionForMyBB_Desc,
        'website' => $lang->HeaderInjectionForMyBB_Web,
        'author' => $lang->HeaderInjectionForMyBB_Auth,
        'authorsite' => $lang->HeaderInjectionForMyBB_AuthSite,
        'version' => $lang->HeaderInjectionForMyBB_Ver,
        'guid' => $lang->HeaderInjectionForMyBB_GUID,
        'compatibility' => $lang->HeaderInjectionForMyBB_Compat
    );
}

// optional function that runs when plugin is activated
// must have same name as plugin file
function HeaderInjectionForMyBB_activate() {
  
  global $db, $lang;

    $lang->load("HeaderInjectionForMyBB");

  // ***********************************************
  // create plugin settings group
  // ***********************************************

  $himybb_group = array(
    "gid" => "NULL", 
    "name" => $lang->HeaderInjectionForMyBB_name_0,
    "title" => $lang->HeaderInjectionForMyBB_title_0,
    "description" => $lang->HeaderInjectionForMyBB_description_0, 
    "disporder" => "1", 
    "isdefault" => "no"
  );

  $db->insert_query("settinggroups", $himybb_group);
  $gid = $db->insert_id();

  // ***********************************************
  // create plugin settings
  // ***********************************************

  $himybb_setting = array(
    "sid" => "NULL", 
    "name" => $lang->HeaderInjectionForMyBB_name_1,
    "title" => $lang->HeaderInjectionForMyBB_title_1,
    "description" => $lang->HeaderInjectionForMyBB_description_1,  
    "optionscode" => "yesno", 
    "value" => "1", 
    "disporder" => "1", 
    "gid" => intval($gid)
  );

  $db->insert_query("settings", $himybb_setting);

  // code to be injected
  $himybb_setting = array(
    "sid" => "NULL", 
    "name" => $lang->HeaderInjectionForMyBB_name_2,
    "title" => $lang->HeaderInjectionForMyBB_title_2,
    "description" => $lang->HeaderInjectionForMyBB_description_2, 
    "optionscode" => "textarea", 
    "value" => '', 
    "disporder" => "2", 
    "gid" => intval($gid)
  );

  $db->insert_query("settings", $himybb_setting);

  rebuild_settings();

} // end activate function

// optional function that runs when plugin is deactivated
// must have same name as plugin file
function HeaderInjectionForMyBB_deactivate() {
  global $db;
  // delete settings first
  $db->query("DELETE FROM " . TABLE_PREFIX . "settings WHERE name IN ('himybb_plugin_enabled')");
  $db->query("DELETE FROM " . TABLE_PREFIX . "settings WHERE name IN ('himybb_inject')");
  // delete settings group
  $db->query("DELETE FROM " . TABLE_PREFIX . "settinggroups WHERE name='himybb_group'");
  rebuild_settings();
}

// main function that runs on hook
function injectheadercode() {
  global $mybb;
  global $headerinclude;
//global $header;

  $isenabled = (bool)$mybb->settings['himybb_plugin_enabled'];
  $codetoinsert = $mybb->settings['himybb_inject'];
    
  if ($isenabled) { // plugin is enabled
    if (strlen($codetoinsert) > 0) { // code included, inject it
      $headerinclude .= $codetoinsert;
      //$header .= $codetoinsert;
    } // end code check
  } // end enabled check
} // end injectheadercode function
?>