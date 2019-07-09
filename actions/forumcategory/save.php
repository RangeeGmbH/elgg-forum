<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
admin_gatekeeper();
$guid = get_input('guid');
if ($guid) {
    $forumcategory = get_entity($guid);
    if (!elgg_instanceof($forumcategory, 'object', 'forumcategory')) {
        return false;
    }
} else {
    $forumcategory = new ForumCategory;
}

$title = get_input('title');
$description = get_input('description');
$access_id = get_input('access_id');

$forumcategory->title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
$forumcategory->description = $description;
$forumcategory->access_id = $access_id;
$forumcategory->save();

elgg_create_river_item(array(
    'view'         => 'river/object/forumcategory/create',
    'action_type'  => 'create',
    'subject_guid' => $forumcategory->owner_guid,
    'object_guid'  => $forumcategory->getGUID()
));

elgg_trigger_event('publish', 'object', $forumcategory);

system_message(elgg_echo('forum:forumcategory:saved'));

forward('forumcategory/all');
