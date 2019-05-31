<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
$widget = elgg_extract('entity', $vars);


$content = elgg_list_entities([
    'type'       => 'object',
    'subtype'    => 'forumtopic',
    'owner_guid' => $widget->owner_guid,
    'limit'      => 5,
    'pagination' => false,
    'distinct'   => false
]);

echo $content;
