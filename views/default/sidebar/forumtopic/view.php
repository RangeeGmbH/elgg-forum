<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
$guid = elgg_extract("guid", $vars);

$title = elgg_echo("forumtopic:othertopics");

$content = elgg_list_entities(array(
    "type" => "object",
    "subtype" => "forumtopic",
    "container_guid" => $guid,
    "limit" => 5,
    "pagination" => false,
    "order_by" => "time_created",
    "view_type" => "compact"
        ));

echo elgg_view_module('aside', $title, $content);
