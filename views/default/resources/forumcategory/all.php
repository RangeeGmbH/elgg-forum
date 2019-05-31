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

$offset = get_input("offset");
$title = elgg_echo("forum:forums");
$content = elgg_list_entities(array(
    "type" => "object",
    "subtype" => "forumcategory",
    "order_by" => "time_created",
    "limit" => 5,
    "offset" => $offset,
    "pagination" => true,
    "view_type" => "compact"
        ));
$sidebar = elgg_view("sidebar/forumcategory/all");
$params = array(
    "title" => $title,
    "content" => $content,
    "sidebar" => $sidebar
);
$body = elgg_view_layout("one_sidebar", $params);
echo elgg_view_page($params['title'], $body);
