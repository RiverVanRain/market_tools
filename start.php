<?php
/**
 * Elgg Market Tools plugin
 *
 * @author RiverVanRain
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 * @copyright (c) wZm 2k16
 *
 * @link http://o.wzm.me/crewz/p/1983/personal-net
 *
 */

elgg_register_event_handler('init', 'system', 'market_tools_init');

function market_tools_init() {

	elgg_register_library('market', elgg_get_plugins_path() . 'market_tools/lib/market.php');
	
	elgg_unregister_page_handler('market', 'market_page_handler');
	elgg_register_page_handler('market', 'market_tools_page_handler');

	add_group_tool_option('market', elgg_echo('market:enablemarket'), true);
	elgg_extend_view('groups/tool_latest', 'market/group_module');
	
	elgg_unregister_plugin_hook_handler('register', 'menu:page', 'market_page_menu');
	elgg_unregister_plugin_hook_handler('register', 'menu:owner_block', 'market_owner_block_menu');
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'market_tools_owner_block_menu');
	
	// Register actions
	$action_path = __DIR__ . '/actions';
	elgg_register_action("market/delete", "$action_path/delete.php");
}

function market_tools_page_handler($page) {

	$pages = elgg_get_plugins_path() . 'market/pages/market';

	if (!isset($page[1])) {
		$page[1] = 'all';
	}
	if (!isset($page[2])) {
		$page[2] = 'all';
	}
	
	// Show market sidebar at top of sidebar
	elgg_extend_view("page/elements/sidebar", "market/sidebar", 100);

	$page_type = $page[0];
	switch ($page_type) {
		case 'group':
			$resource_vars['group_guid'] = elgg_extract(1, $page);
			$resource_vars['subpage'] = elgg_extract(2, $page);
			$resource_vars['lower'] = elgg_extract(3, $page);
			$resource_vars['upper'] = elgg_extract(4, $page);
			
			echo elgg_view_resource('market/group', $resource_vars);
			break;
		case 'owned':
			set_input('username', $page[1]);
			include "$pages/owned.php";
			break;
		case 'view':
			set_input('marketpost', $page[1]);
			include "$pages/view.php";
			break;
		case 'image':
			set_input('guid', $page[1]);
			set_input('imagenum', $page[2]);
			set_input('size', $page[3]);
			set_input('tu', $page[4]);
			include "$pages/image.php";
			break;
		case 'imagepopup':
			set_input('guid', $page[1]);
			set_input('imagenum', $page[2]);
			include "$pages/imagepopup.php";
			break;
		case 'add':
			elgg_load_library('market');
			include "$pages/add.php";
			break;
		case 'edit':
			elgg_load_library('market');
			set_input('guid', $page[1]);
			include "$pages/edit.php";
			break;
		case 'category':
			set_input('cat', $page[1]);
			set_input('type', $page[2]);
			include "$pages/category.php";
			break;
		case 'terms':
			include "$pages/terms.php";
			break;
		default:
			set_input('cat', $page[1]);
			set_input('type', $page[2]);
			include "$pages/category.php";
			break;
	}
	return true;
}

function market_tools_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "market/owned/{$params['entity']->username}";
		$item = new ElggMenuItem('market', elgg_echo('market'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->market_enable != "no") {
			$url = "market/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('market', elgg_echo('market:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}