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
$title = $description = null;
if ($guid) {
    $entity = get_entity($guid);
    if (elgg_instanceof($entity, 'object', 'forum')) {
        $title = $entity->title;
        $description = $entity->description;
    }
    echo elgg_view('input/hidden', array(
        'name'  => 'guid',
        'value' => $guid
    ));
} else {
    $access_id = get_input('access_id');
}
$title_label = elgg_format_element('label', array(
    'for' => 'title_id'
), elgg_echo('title'));
$title_input .= elgg_view('input/text', array(
    'id'    => 'title_id',
    'name'  => 'title',
    'value' => $title
));

$description_label = elgg_format_element('label', array(), elgg_echo('description'));
$description_input .= elgg_view('input/longtext', array(
    'id'    => 'description_id',
    'name'  => 'description',
    'value' => $description
));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array(
    'name'           => 'access_id',
    'id'             => 'forum_access_id',
    'entity'         => $entity,
    'entity_type'    => 'object',
    'entity_subtype' => 'forum'
));

echo elgg_format_element('div', array(), $title_label . $title_input);
echo elgg_format_element('div', array(), $description_label . $description_input);
echo elgg_format_element('div', array(), $access_label . $access_input);

echo elgg_view('input/submit', array(
    'value' => elgg_echo('save')
));
