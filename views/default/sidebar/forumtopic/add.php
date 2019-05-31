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
$title = elgg_echo("forum:topic:latest");
$forum = get_entity($guid);
$category = get_entity($forum->container_guid);
$content = elgg_list_entities(array(
    "type" => "object",
    "subtype" => "forumtopic",
    "limit" => 5,
    "pagination" => false,
    "view_type" => "compact",
    "order_by" => "time_created",
    "order_by_reverse" => true
        ));
echo elgg_view_module('aside', $title, $content);
