<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
$guid = get_input('guid');
if ($guid) {
    $entity = get_entity($guid);
    $subtype = $entity->getSubtype();
    $allowed_entities = array(
        'forum',
        'forumcategory'
    );
    if (!in_array($subtype, $allowed_entities)) {
        return false;
    }
    if (elgg_instanceof($entity, 'object', 'forum')) {
        $container_guid = $entity->container_guid;
        $forum = $entity;
    }
    if (elgg_instanceof($entity, 'object', 'forumcategory')) {
        $container_guid = $guid;
        $forum = new Forum;
    }
}

$title = htmlspecialchars(get_input('title', null, false), ENT_QUOTES, 'UTF-8');
$description = get_input('description');
$access_id = get_input('access_id');


$forum->title = $title;
$forum->description = $description;
$forum->access_id = $access_id;
$forum->container_guid = $container_guid;
$forum->save();

elgg_create_river_item(array(
    'view'         => 'river/object/forum/create',
    'action_type'  => 'create',
    'subject_guid' => $forum->owner_guid,
    'object_guid'  => $forum->getGUID()
));

elgg_trigger_event('publish', 'object', $forum);

system_message(elgg_echo('forum:forum:saved'));

forward('forumcategory/all');
