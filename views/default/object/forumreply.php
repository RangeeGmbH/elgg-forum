<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
$entity = elgg_extract("entity", $vars);
$view_type = elgg_extract("view_type", $vars);
$owner_link = '';
$owner = $entity->getOwnerEntity();
if ($owner) {
    $owner_link = elgg_view('output/url', array(
        'href' => $owner->getURL(),
        'text' => $owner->getDisplayName(),
        'is_trusted' => true,
    ));
}

$date = elgg_view_friendly_time($entity->getTimeCreated());

$subtitle = "$owner_link $date";
switch ($view_type) {
    default:
        $icon_size = "small";
        if (!elgg_in_context('widgets')) {
            $metadata = elgg_view_menu('entity', array(
                'entity' => $entity,
                'handler' => 'forumreply',
                'sort_by' => 'priority',
                'class' => 'elgg-menu-hz',
            ));
        }
        $content = $entity->description;
        break;
    case "compact":
        $icon_size = "tiny";
        $metadata = null;
        $content = elgg_get_excerpt($entity->description, 40);
        break;
}

$icon = elgg_view_entity_icon($owner, $icon_size);
$title_text = elgg_get_excerpt($entity->getDisplayName());
$title = elgg_view("output/url", array(
    "text" => $title_text,
    "href" => $entity->getURL()
        ));


$params = array(
    'entity' => $entity,
    'title' => $title,
    'metadata' => $metadata,
    'subtitle' => $subtitle,
    'content' => $content
);
$params = $params + $vars;
$body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($icon, $body, $vars);