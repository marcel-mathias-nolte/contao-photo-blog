<?php

$GLOBALS['TL_DCA']['tl_member']['list']['label']['fields'][] = '';
$GLOBALS['TL_DCA']['tl_member']['list']['label']['label_callback'] = function($row, $label, DataContainer $dc, $args) {

    $image = 'member';
    $disabled = ($row['start'] !== '' && $row['start'] > time()) || ($row['stop'] !== '' && $row['stop'] <= time());

    if ($row['useTwoFactor'])
    {
        $image .= '_two_factor';
    }

    if ($disabled || $row['disable'])
    {
        $image .= '_';
    }

    $args[0] = sprintf(
        '<div class="list_icon_new" style="background-image:url(\'%s\')" data-icon="%s" data-icon-disabled="%s">&nbsp;</div>',
        Image::getPath($image),
        Image::getPath($disabled ? $image : rtrim($image, '_')),
        Image::getPath(rtrim($image, '_') . '_')
    );
    $args[count($args) - 1] = '<strong style="color: ';
    $groups = \deserialize($row['groups'], true);
    if (!\in_array('8', $groups)) { // FSK enabled
        $args[count($args) - 1] .= 'red';
    } elseif (\in_array('6', $groups)) { //FSK
        $args[count($args) - 1] .= 'green';
    } else {
        $args[count($args) - 1] .= 'yellow';
    }
    $args[count($args) - 1] .= ';">FSK</strong>';
    $args[count($args) - 1] .= ' <strong style="color: ';
    if (\in_array('7', $groups)) { //SEX
        $args[count($args) - 1] .= 'green';
    } else {
        $args[count($args) - 1] .= 'yellow';
    }
    $args[count($args) - 1] .= ';">SEX</strong>';


    return $args;
};
