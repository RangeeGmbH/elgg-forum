<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
forum_push_breadcrumbs($vars);

$guid = elgg_extract(1, $vars);

$forumcategory = get_entity($guid);

if (!elgg_instanceof($forumcategory, "object", "forumcategory")) {
    return true;
}

$content = elgg_view_entity($forumcategory, array(
    "view_type" => "full"
        ));

$sidebar = elgg_view("sidebar/forumcategory/view", array(
    "guid" => $guid
        ));

$params = array(
    "title" => $title,
    "content" => $content,
    "sidebar" => $sidebar
);
$body = elgg_view_layout("one_sidebar", $params);
echo elgg_view_page($params['title'], $body);
