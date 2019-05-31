<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
$title = elgg_echo("forum:topic:latest");
$guid = $vars[1];
$content = elgg_list_entities(array(
    "type" => "object",
    "subtype" => "forumtopic",
    "limit" => 5,
    "pagination" => false,
    "container_guid" => $guid,
    "view_type" => "compact"
        ));

echo elgg_view_module('aside', $title, $content);
