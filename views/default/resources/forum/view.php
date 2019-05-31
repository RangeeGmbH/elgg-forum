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

$title = null;
$forum = get_entity($guid);
if (!elgg_instanceof($forum, 'object', 'forum')) {
    return true;
}
if (elgg_is_logged_in()) {
    elgg_register_menu_item('title', array(
        'name'       => 'add_forum_topic',
        'href'       => 'forumtopic/add/' . $guid,
        'text'       => elgg_echo('forum:topic:add', array(
            $forum->getDisplayName()
        )),
        'link_class' => 'elgg-button elgg-button-action'
    ));
}
$content = elgg_view_entity($forum, array(
    'view_type' => 'full'
));
$content .= elgg_list_entities(array(
    'type'           => 'object',
    'subtype'        => 'forumtopic',
    'list_type'      => 'table',
    'container_guid' => $guid,
    'columns'        => array(
        elgg()->table_columns->item(elgg_echo('forumtopic:topics')),
        elgg()->table_columns->fromView('topic_last_reply', elgg_echo('forumtopic:latest:reply'))
    )
));

$sidebar = elgg_view('sidebar/forum/view');

$params = array(
    'title'   => $title,
    'content' => $content,
    'sidebar' => $sidebar
);
$body = elgg_view_layout('one_sidebar', $params);
echo elgg_view_page($params['title'], $body);
