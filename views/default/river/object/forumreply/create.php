<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
$item = elgg_extract("item", $vars);

$object = $item->getObjectEntity();

$excerpt = $object->excerpt ? $object->excerpt : $object->description;
$excerpt = strip_tags($excerpt);
$excerpt = elgg_get_excerpt($excerpt);

$owner = $object->getOwnerEntity();

$owner_string = elgg_view("output/url", array(
    "text" => $owner->getDisplayName(),
    "href" => $owner->getURL()
        ));

$topic = get_entity($object->container_guid);

$topic_string = elgg_view("output/url", array(
    "text" => $topic->getDisplayName(),
    "href" => $topic->getURL()
        ));

$summary = elgg_echo("river:create:object:forumreply", array(
    $owner_string,
    $topic_string
        ));

echo elgg_view('river/elements/layout', array(
    'item' => $vars['item'],
    'message' => $excerpt,
    "summary" => $summary
));
