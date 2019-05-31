<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
$guid = get_input("guid");
if ($guid) {
    $entity = get_entity($guid);
    if (elgg_instanceof($entity, "object", "forum")) {
        $forumtopic = new ForumTopic;
        $forumtopic->container_guid = $guid;
    } elseif (elgg_instanceof($entity, "object", "forumtopic")) {
        $forumtopic = $entity;
    } else {
        return false;
    }
}

$title = get_input("title");
$description = get_input("description");
$access_id = get_input("access_id");

$forumtopic->title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
$forumtopic->description = $description;
$forumtopic->access_id = $access_id;
$forumtopic->save();

elgg_create_river_item(array(
    'view'         => 'river/object/forumtopic/create',
    'action_type'  => 'create',
    'subject_guid' => $forumtopic->owner_guid,
    'object_guid'  => $forumtopic->getGUID(),
));

elgg_trigger_event('publish', 'object', $forumtopic);

system_message(elgg_echo("forum:forumtopic:saved"));

forward("forumcategory/all");
