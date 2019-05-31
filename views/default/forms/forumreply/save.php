<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
$guid = $vars[1];

if ($guid) {
    $entity = get_entity($guid);
    if (elgg_instanceof($entity, "object", "forumreply")) {
        $label = "";
        $description = $entity->description;
        echo elgg_view("input/hidden", array(
            "name" => "guid",
            "value" => $guid
        ));
    }
    if (elgg_instanceof($entity, "object", "forumtopic")) {
        $label = elgg_echo("forutopic:reply");
        echo elgg_view("input/hidden", array(
            "name" => "container_guid",
            "value" => $guid
        ));
        $description = null;
    }
}


$description_label = elgg_format_element("label", array(), $label);
$description_input .= elgg_view("input/longtext", array(
    "id" => "description_id",
    "name" => "description",
    "value" => $description
        ));

echo elgg_format_element("div", array(), $description_label . $description_input);

echo elgg_view("input/submit", array(
    "value" => elgg_echo("forumtopic:reply:button")
));
