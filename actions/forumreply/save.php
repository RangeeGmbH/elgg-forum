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
    $forumreply = get_entity($guid);
    if (!elgg_instanceof($forumreply, "object", "forumreply")) {
        return true;
    }
} else {
    $forumreply = new ForumReply;
    $forumreply->container_guid = get_input("container_guid");
}
$title = get_input("title");
$description = get_input("description");
$access_id = get_input("access_id");
$forumreply->description = $description;
$forumreply->access_id = ACCESS_PUBLIC;
$forumreply->save();

elgg_create_river_item(array(
    'view'         => 'river/object/forumreply/create',
    'action_type'  => 'create',
    'subject_guid' => $forumreply->owner_guid,
    'object_guid'  => $forumreply->getGUID(),
));

elgg_trigger_event('publish', 'object', $forumreply);

system_message(elgg_echo("forum:reply:saved"));

forward("forumtopic/view/" . $forumreply->container_guid);

