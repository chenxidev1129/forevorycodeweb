<?php

return [
    'separator' => '&',
    'services' => [
        'delicious' => [ 'uri' => 'https://delicious.com/post' ],
        'digg' => [ 'uri' => 'https://www.digg.com/submit' ],
        'email' => [ 'view' => 'social-share::email' ],
        'evernote' => [ 'uri' => 'https://www.evernote.com/clip.action' ],
        'facebook' => [ 'uri' => 'https://www.facebook.com/sharer/sharer.php', 'urlName' => 'u',  ],
        'gmail' => [ 'uri' => 'https://mail.google.com/mail/', 'urlName' => 'su', 'titleName' => 'body', 'extra' => [
            'view' => 'cm',
            'fs' => 1,
            'to' => '',
            'ui' => 2,
            'tf' => 1,
        ]],
        'gplus' => [ 'uri' => 'https://plus.google.com/share', 'only' => [ 'url' ] ],
        'linkedin' => [ 'uri' => 'https://www.linkedin.com/shareArticle', 'extra' => [ 'mini' => 'true' ] ],
        'pinterest' => [ 'uri' => 'https://pinterest.com/pin/create/button/', 'titleName' => 'description', 'mediaName' => 'media' ],
        'reddit' => [ 'uri' => 'https://www.reddit.com/submit' ],
        'scoopit' => [ 'uri' => 'https://www.scoop.it/oexchange/share' ],
        'telegramMe' => [ 'uri' => 'https://telegram.me/share/url', 'titleName' => 'text' ],
        'tumblr' => [ 'uri' => 'https://www.tumblr.com/share', 'urlName' => 'u', 'titleName' => 't', 'extra' => [
            'v' => 3,
        ]],
        'twitter' => [ 'uri' => 'https://twitter.com/intent/tweet', 'titleName' => 'text', 'mediaName' => 'image' ],
        'viadeo' => [ 'uri' => 'https://www.viadeo.com/' ],
        'vk' => [ 'uri' => 'https://vk.com/share.php', 'mediaName' => 'image', 'extra' => [
            'noparse' => 'false',
        ]],
        'whatsapp' => [ 'view' => 'social-share::whatsapp' ],
    ],
];
