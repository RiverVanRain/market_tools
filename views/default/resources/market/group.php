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
// access check for closed groups
elgg_group_gatekeeper();

$owner = elgg_get_page_owner_entity();
if (!$owner) {
	forward('', '404');
}

elgg_push_breadcrumb(elgg_echo('market:title'), "market/category");
elgg_push_breadcrumb($owner->name);

elgg_register_title_button('market', 'add', 'object', 'market');

$title = sprintf(elgg_echo('market:user:title'),$owner->name);

$content = elgg_list_entities(array(
		'type' => 'object',
		'subtype' => 'market',
		'container_guid' => $owner->guid,
		'limit' => 5,
		'full_view' => false,
		'pagination' => true,
		'list_type_toggle' => FALSE
	));

if (empty($content)) {
	$content = elgg_echo('market:none:found');
}

$params = array(
		'filter' => false,
		'content' => $content,
		'title' => $title,
	);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
