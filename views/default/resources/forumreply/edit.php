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
$title = elgg_echo("forumreply:edit");
$content = elgg_view_form("forumreply/save", array(), $vars);
$params = array(
    "title"   => $title,
    "content" => $content
);
$body = elgg_view_layout("one_column", $params);
echo elgg_view_page($params['title'], $body);
