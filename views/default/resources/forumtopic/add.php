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
$forum = get_entity($guid);
if (!elgg_instanceof($forum, 'object', 'forum')) {
    return false;
}
$title = elgg_echo('forum:topic:add', array(
    elgg_get_excerpt($forum->getDisplayName(), 40)
));
$content = elgg_view_form('forumtopic/save', array(), $vars);
$sidebar = elgg_view('sidebar/forumtopic/add', $vars);
$params = array(
    'title'   => $title,
    'content' => $content,
    'sidebar' => $sidebar
);
$body = elgg_view_layout('one_sidebar', $params);
echo elgg_view_page($params['title'], $body);
