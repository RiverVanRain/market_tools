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

$group = elgg_get_page_owner_entity();

if ($group->market_enable == "no") {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "market/group/$group->guid/all",
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
));

elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'market',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
	'no_results' => elgg_echo('market:none'),
	'distinct' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

$new_link = elgg_view('output/url', array(
	'href' => "market/add/$group->guid",
	'text' => elgg_echo('market:write'),
	'is_trusted' => true,
));

echo elgg_view('groups/profile/module', array(
	'title' => elgg_echo('market:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
