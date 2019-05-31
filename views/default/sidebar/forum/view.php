<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
$guid = $vars[1];
$forum = get_entity($guid);
$title = elgg_echo('forum:topic:latest');
$category = get_entity($forum->container_guid);
$content = elgg_list_entities(array(
    'type'             => 'object',
    'subtype'          => 'forumtopic',
    'comtainer_guid'   => $category->guid,
    'order_by'         => 'time_created',
    'order_by_reverse' => true,
    'view_type'        => 'compact',
    'pagination'       => false
));

echo elgg_view_module('aside', $title, $content);
