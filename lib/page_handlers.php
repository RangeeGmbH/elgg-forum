<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
function forum_page_handler($page)
{
    $page['subtype'] = 'forum';
    switch (elgg_extract(0, $page)) {
        case 'add':
            echo elgg_view_resource('forum/add', $page);
            break;
        case 'edit':
            echo elgg_view_resource('forum/edit', $page);
            break;
        case 'view':
            echo elgg_view_resource('forum/view', $page);
            break;
    }
}

function forumcategory_page_handler($page)
{
    $page['subtype'] = 'forumcategory';
    switch ($page[0]) {
        default:
        case 'all':
            elgg_register_title_button('forumcategory');
            echo elgg_view_resource('forumcategory/all');
            break;
        case 'add':
            echo elgg_view_resource('forumcategory/add', $page);
            break;
        case 'edit':
            echo elgg_view_resource('forumcategory/edit', $page);
            break;
        case 'view':
            echo elgg_view_resource('forumcategory/view', $page);
            break;
    }
}

function forumtopic_page_handler($page)
{
    $page['subtype'] = 'forumtopic';
    switch ($page[0]) {
        case 'add':
        default:
            echo elgg_view_resource('forumtopic/add', $page);
            break;
        case 'edit':
            echo elgg_view_resource('forumtopic/edit', $page);
            break;
        case 'view':
            echo elgg_view_resource('forumtopic/view', $page);
            break;
    }
}

function forumreply_page_handler($page)
{
    $page['subtype'] = 'forumreply';
    switch ($page[0]) {
        case 'edit':
            echo elgg_view_resource('forumreply/edit', $page);
            break;
    }
}
