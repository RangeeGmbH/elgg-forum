<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
class ForumReply extends ElggObject {

    protected function initializeAttributes() {
        parent::initializeAttributes();

        $this->attributes['subtype'] = "forumreply";
    }

}
