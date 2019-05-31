<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
$title = elgg_echo('forum:topic:latest');
$content = elgg_list_entities(array(
    'type'             => 'object',
    'subtype'          => 'forumtopic',
    'view_type'        => 'compact',
    'order_by'         => 'time_created',
    'order_by_reverse' => true,
    'limit'            => 5,
    'pagination'       => false
));

echo elgg_view_module('aside', $title, $content);

