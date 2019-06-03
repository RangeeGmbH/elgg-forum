<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
elgg_register_event_handler('init', 'system', 'forum_init');

function forum_init()
{
    // Create menu item
    $item = new ElggMenuItem('forum', elgg_echo('forum:forums'), 'forumcategory/all');
    elgg_register_menu_item('site', $item);

    // Register and load libraries
    elgg_register_library('forum_page_handlers', elgg_get_plugins_path() . 'forum/lib/page_handlers.php');
    elgg_load_library('forum_page_handlers');

    // Register page handlers
    elgg_register_page_handler('forum', 'forum_page_handler');
    elgg_register_page_handler('forumcategory', 'forumcategory_page_handler');
    elgg_register_page_handler('forumtopic', 'forumtopic_page_handler');
    elgg_register_page_handler('forumreply', 'forumreply_page_handler');

    // Register actions
    $plugins_path = elgg_get_plugins_path();
    elgg_register_action('forum/save', $plugins_path . 'forum/actions/forum/save.php', 'admin');
    elgg_register_action('forumcategory/save', $plugins_path . 'forum/actions/forumcategory/save.php', 'admin');
    elgg_register_action('forumtopic/save', $plugins_path . 'forum/actions/forumtopic/save.php');
    elgg_register_action('forumreply/save', $plugins_path . 'forum/actions/forumreply/save.php');

    // Register plugin hook handlers
    elgg_register_plugin_hook_handler('register', 'menu:entity', 'forum_entity_menu_setup');
    elgg_register_plugin_hook_handler('entity:url', 'object', 'forum_set_url');
    elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'forum_set_icon_url');

    // Register widget types
    elgg_register_widget_type('forumtopic', elgg_echo('forumtopic:widget:title'),
        elgg_echo('forumtopic:widget:description'));

    // Extend css view
    elgg_extend_view('elgg.css', 'css/forum');

    // Make things likeable
    elgg_register_plugin_hook_handler('likes:is_likable', 'object:forumreply', 'Elgg\Values::getTrue');
    elgg_register_plugin_hook_handler('likes:is_likable', 'object:forumtopic', "Elgg\Values::getTrue");

    // Register entity types for search
    elgg_register_entity_type('object', 'forumcategory');
    elgg_register_entity_type('object', 'forum');
    elgg_register_entity_type('object', 'forumtopic');
}

function forum_set_url($hook, $type, $url, $params)
{
    $entity = elgg_extract('entity', $params);
    if (elgg_instanceof($entity, 'object', 'forumcategory')) {
        $friendly_title = elgg_get_friendly_title($entity->getDisplayName());

        return "forumcategory/view/{$entity->guid}/$friendly_title";
    }
    if (elgg_instanceof($entity, 'object', 'forum')) {
        $friendly_title = elgg_get_friendly_title($entity->getDisplayName());

        return "forum/view/{$entity->guid}/$friendly_title";
    }
    if (elgg_instanceof($entity, 'object', 'forumtopic')) {
        $friendly_title = elgg_get_friendly_title($entity->getDisplayName());

        return "forumtopic/view/{$entity->guid}/$friendly_title";
    }
    if (elgg_instanceof($entity, 'object', 'forumreply')) {
        $container = get_entity($entity->container_guid);

        return 'forumtopic/view/' . $container->getGUID() . '/#forumreply_' . $entity->getGUID();
    }
}

function forum_entity_menu_setup($hook, $type, $return, $params)
{
    if (elgg_in_context('widgets')) {
        return $return;
    }

    $entity = $params['entity'];
    $handler = elgg_extract('handler', $params, false);
    $handlers = array(
        'forumcategory',
        'forum',
        'forumtopic',
        'forumreply'
    );
    if (!in_array($handler, $handlers)) {
        return $return;
    }
    if (elgg_instanceof($entity, 'object', 'forumcategory')) {
        $options = array(
            'name'     => 'add_forum',
            'text'     => elgg_echo('forum:add'),
            'href'     => 'forum/add/' . $entity->guid,
            'priority' => 150
        );
        $return[] = ElggMenuItem::factory($options);
    }
    if (elgg_instanceof($entity, 'object', 'forum')) {
        $url = $entity->getURL();
        $item = new ElggMenuItem('topic_count', elgg_echo('forum:count:topics', array(forum_count_topics($entity))),
            $url);
        array_unshift($return, $item);
        $item = new ElggMenuItem('reply_count', elgg_echo('forum:count:replies', array(forum_count_replies($entity))),
            $url);
        array_unshift($return, $item);
    }
    if (elgg_instanceof($entity, 'object', 'forumtopic')) {
        $url = $entity->getURL();
        $item = new ElggMenuItem('reply_count',
            elgg_echo('forumtopic:count:replies', array(topic_count_replies($entity))), $url);
        array_unshift($return, $item);
        $item = new ElggMenuItem('topic_view', elgg_echo('forumtopic:count:views', array(topic_count_views($entity))),
            $url);
        array_unshift($return, $item);
    }
    if (elgg_instanceof($entity, 'object', 'forumreply')) {
        $timeout = elgg_get_plugin_setting('forum_reply_edit_timeout', 'forum');
        if (!$timeout) {
            $timeout = 0;
        }
        $remove = array(
            'access'
        );
        foreach ($return as $key => $value) {
            $name = $value->getName();
            if (in_array($name, $remove)) {
                unset($return[$key]);
            }
            if (!elgg_is_admin_logged_in()) {
                if ($name === 'edit' || $name === 'delete') {
                    if ($timeout != 0) {
                        $time_created = $entity->time_created;
                        $time = time();
                        if ($time > $time_created + $timeout * 60) {
                            unset($return[$key]);
                        }
                    }
                }
            }
        }
    }

    return $return;
}

function forum_set_icon_url($hook, $type, $url, $params)
{
    $entity = $params['entity'];
    $size = elgg_extract('size', $params, 'large');
    $subtype = $entity->getSubType();
    $forum_subtypes = array(
        'forum',
        'forumtopic'
    );
    if (in_array($subtype, $forum_subtypes)) {
        $url = elgg_get_site_url() . "mod/forum/graphics/icons/{$size}.png";
        $url = elgg_trigger_plugin_hook('file:icon:url', 'override', $params, $url);

        return $url;
    }
}

function topic_count_replies($topic)
{

    return elgg_get_entities(array(
        'type'           => 'object',
        'subtype'        => 'forumreply',
        'container_guid' => $topic->getGuid(),
        'count'          => true
    ));
}

function topic_count_views($topic)
{
    return (int)$topic->views;
}

function forum_count_topics($forum)
{
    return elgg_get_entities(array(
        'type'           => 'object',
        'subtype'        => 'forumtopic',
        'container_guid' => $forum->getGUID(),
        'count'          => true
    ));
}

function forum_count_replies($forum)
{
    $forumtopics = elgg_get_entities(array(
        'type'           => 'object',
        'subtype'        => 'forumtopic',
        'container_guid' => $forum->getGUID()
    ));
    foreach ($forumtopics as $topic) {
        $count += elgg_get_entities(array(
            'type'           => 'object',
            'subtype'        => 'forumreply',
            'container_guid' => $topic->getGUID(),
            'count'          => true
        ));
    }

    return $count ? $count : 0;
}

function forum_push_breadcrumbs($vars)
{
    elgg_push_breadcrumb(elgg_echo('forum:forums'), 'forumcategory/all');
    switch ($vars['subtype']) {
        case 'forumcategory':
            switch ($vars[0]) {
                case 'add':
                    elgg_push_breadcrumb(elgg_echo('forumcategory:add'), 'forumcategory/add/' . $vars[1]);
                    break;
                case 'view':
                    $guid = $vars[1];
                    $category = get_entity($guid);
                    elgg_push_breadcrumb(elgg_get_excerpt($category->getDisplayName()),
                        'forumcategory/view/' . $vars[1]);
                    break;
                case 'edit':
                    $guid = $vars[1];
                    $category = get_entity($guid);
                    elgg_push_breadcrumb(elgg_get_excerpt($category->getDisplayName()), $category->getURL());
                    elgg_push_breadcrumb(elgg_echo('forumcategory:edit'), 'forumcategory/edit/' . $guid);
                    break;
            }
            break;
        case 'forum':
            switch ($vars[0]) {
                case 'add':
                    elgg_push_breadcrumb(elgg_echo('forum:add'), 'forumcategory/add/' . $vars[1]);
                    break;
                case 'view':
                    $guid = $vars[1];
                    $forum = get_entity($guid);
                    $category = get_entity($forum->container_guid);
                    elgg_push_breadcrumb(elgg_get_excerpt($category->getDisplayName()), $category->getURL());
                    elgg_push_breadcrumb(elgg_get_excerpt($forum->getDisplayName()), $forum->getURL());
                    break;
                case 'edit':
                    $guid = $vars[1];
                    $forum = get_entity($guid);
                    $category = get_entity($forum->container_guid);
                    elgg_push_breadcrumb(elgg_get_excerpt($category->getDisplayName()), $category->getURL());
                    elgg_push_breadcrumb(elgg_get_excerpt($forum->getDisplayName()), $forum->getURL());
                    elgg_push_breadcrumb(elgg_echo('forum:edit'), $forum->getURL());
                    break;
            }
            break;
        case 'forumtopic':
            switch ($vars[0]) {
                case 'add':
                    $guid = $vars[1];
                    $forum = get_entity($guid);
                    $category = get_entity($forum->container_guid);
                    elgg_push_breadcrumb(elgg_get_excerpt($category->getDisplayName()), $category->getURL());
                    elgg_push_breadcrumb(elgg_get_excerpt($forum->getDisplayName()), $forum->getURL());
                    elgg_push_breadcrumb(elgg_echo('forum:topic:add'), $forum->getURL());
                    break;
                case 'view':
                    $guid = $vars[1];
                    $topic = get_entity($guid);
                    $forum = get_entity($topic->container_guid);
                    $category = get_entity($forum->container_guid);
                    elgg_push_breadcrumb(elgg_get_excerpt($category->getDisplayName()), $category->getURL());
                    elgg_push_breadcrumb(elgg_get_excerpt($forum->getDisplayName()), $forum->getURL());
                    elgg_push_breadcrumb(elgg_get_excerpt($topic->getDisplayName()), $forum->getURL());
                    break;
                case 'edit':
                    $guid = $vars[1];
                    $topic = get_entity($guid);
                    $forum = get_entity($topic->container_guid);
                    $category = get_entity($forum->container_guid);
                    elgg_push_breadcrumb(elgg_get_excerpt($category->getDisplayName()), $category->getURL());
                    elgg_push_breadcrumb(elgg_get_excerpt($forum->getDisplayName()), $forum->getURL());
                    elgg_push_breadcrumb(elgg_get_excerpt($topic->getDisplayName()), $forum->getURL());
                    elgg_push_breadcrumb(elgg_echo('forumtopic:edit'), $forum->getURL());
                    break;
            }
            break;
        case 'forumreply':
            switch ($vars[0]) {
                case 'edit':
                    $guid = $vars[1];
                    $reply = get_entity($guid);
                    $topic = get_entity($reply->container_guid);
                    $forum = get_entity($topic->container_guid);
                    $category = get_entity($forum->container_guid);
                    elgg_push_breadcrumb(elgg_get_excerpt($category->getDisplayName()), $category->getURL());
                    elgg_push_breadcrumb(elgg_get_excerpt($forum->getDisplayName()), $forum->getURL());
                    elgg_push_breadcrumb(elgg_get_excerpt($topic->getDisplayName()), $forum->getURL());
                    elgg_push_breadcrumb(elgg_echo('forumreply:edit'), $forum->getURL());
                    break;
            }
            break;
        default:
            break;
    }
}

function forum_update_views($entity)
{
    $views = (int)$entity->views;
    $views++;
    $access = elgg_get_ignore_access();
    elgg_set_ignore_access();
    $entity->views = $views;
    elgg_set_ignore_access($access);
}
