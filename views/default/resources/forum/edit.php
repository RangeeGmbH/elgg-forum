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
$title = elgg_echo("forum:edit");
$content = elgg_view_form("forum/save", array(), $vars);
$sidebar = elgg_view("sidebar/forumcategory/all");
$params = array(
    "title" => $title,
    "content" => $content,
    "sidebar" => $sidebar
);
$body = elgg_view_layout("one_sidebar", $params);
echo elgg_view_page($params['title'], $body);
