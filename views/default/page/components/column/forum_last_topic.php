<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
$item = elgg_extract('item', $vars);
$guid = $item->getGUID();
echo elgg_list_entities(array(
    'type'           => 'object',
    'subtype'        => 'forumtopic',
    'container_guid' => $guid,
    'limit'          => 1,
    'pagination'     => false,
    'class'          => 'min200 max200',
    'view_type'      => 'compact'
));
