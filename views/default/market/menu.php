<?php
/**
 * Market Categories pages menu
 *
 * @uses $vars['type']
 */
$user = elgg_get_logged_in_user_entity();
if (elgg_get_plugin_setting('market_type', 'market') == 'no') {
	return true;
}

$category = get_input('cat', 'all', true);
$selected_type = get_input('type', 'all', true);

if (empty($category)) {
	 $category = 'all';
}

//set the url
$url = elgg_get_site_url() . "market/category/$category/";
$types = array('all', 'buy', 'sell', 'swap', 'free');
foreach ($types as $type) {
	$tabs[] = array(
		'title' => elgg_echo("market:type:{$type}"),
		'url' => $url . $type,
		'selected' => $selected_type == $type,
	);
}

$tabs['mine'] = array(
		'title' => elgg_echo("market:mine"),
		'url' => 'market/owned/' . $user->username,
		'selected' => $selected_type == 'mine',
	);

echo elgg_view('navigation/tabs', array('tabs' => $tabs));



