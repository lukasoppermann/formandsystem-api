<?php

return [
    'encoding' => 'UTF-8',
    'finalize' => true,
    'preload'  => false,
    'cachePath' => null,
    'settings' => [
        'default' => [
            'HTML.Doctype'             => 'XHTML 1.0 Strict',
            'HTML.ForbiddenElements'   => 'script,css,style',
            'CSS.ForbiddenProperties'  => '',
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty'   => false
        ],
        'test' => [
            'Attr.EnableID' => true
        ],
        "youtube" => [
            "HTML.SafeIframe" => 'true',
            "URI.SafeIframeRegexp" => "%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%",
        ],
    ],

];
