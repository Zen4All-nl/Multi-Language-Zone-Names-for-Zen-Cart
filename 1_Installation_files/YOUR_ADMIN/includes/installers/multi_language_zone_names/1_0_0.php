<?php

// use $configuration_group_id where needed
// For Admin Pages

$admin_page = 'configMultiLanguageZoneNames';
// delete configuration menu
$db->Execute("DELETE FROM " . TABLE_ADMIN_PAGES . " WHERE page_key = '" . $admin_page . "' LIMIT 1;");
// add configuration menu
if (!zen_page_key_exists($admin_page)) {
  if ((int)$configuration_group_id > 0) {
    zen_register_admin_page($admin_page, 'BOX_MULTI_LANGUAGE_ZONE_NAMES', 'FILENAME_CONFIGURATION', 'gID=' . $configuration_group_id, 'configuration', 'Y', $configuration_group_id);
    $messageStack->add('Enabled Multi Language Zone Names Configuration Menu.', 'success');
  }
}

// Table structure for table `zones_name`
$db->Execute("CREATE TABLE IF NOT EXISTS " . TABLE_ZONES_NAME . " (
  zone_id int(11) NOT NULL,
  language_id int(11) NOT NULL DEFAULT '1',
  zone_name varchar(64) NOT NULL,
  UNIQUE zones (zone_id, language_id, zone_name),
  KEY idx_zone_name_zen (zone_name)
) ENGINE=MyISAM DEFAULT CHARSET=" . DB_CHARSET . ";");

$selectZoneNamesQuery = "SELECT zone_id, zone_name
                         FROM " . TABLE_ZONES . "
                         ORDER BY zone_id ASC";

$languages = zen_get_languages();

for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
  $selectZoneNames = $db->Execute($selectZoneNamesQuery);
  $language_id = $languages[$i]['id'];
  foreach($selectZoneNames as $selectZoneName) {
    $zoneNameArray = array(
      'zone_id' => $selectZoneName['zone_id'],
      'language_id' => $language_id,
      'zone_name' => $selectZoneName['zone_name']);
    zen_db_perform(TABLE_ZONES_NAME, $zoneNameArray);
  }
}
