<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
$forum_reply_edit_timeout = $vars['entity']->forum_reply_edit_timeout;

if (!$forum_reply_edit_timeout) {
    $forum_reply_edit_timeout = 0;
}

echo elgg_view_field(array(
    '#label' => 'Reply Edit Timeout',
    '#type'  => 'text',
    '#help'  => 'How long users will be allowed to edit or delete their replies in minutes.  Enter 0 for no timeout.',
    'value'  => $forum_reply_edit_timeout,
    'name'   => 'params[forum_reply_edit_timeout]'
));
