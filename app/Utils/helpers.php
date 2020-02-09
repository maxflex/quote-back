<?php

function extract_fields($object, $fields, $merge = [])
{
    $return = [];
    foreach ($fields as $field) {
        $return[$field] = $object->{$field};
    }
    return array_merge($return, $merge);
}

function cloneObject($object)
{
    $clonedObject = clone $object;
    return $clonedObject;
}
