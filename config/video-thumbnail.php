<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Binaries
    |--------------------------------------------------------------------------
    |
    | Paths to ffmpeg nad ffprobe binaries
    |
    */

    'binaries' => [
        'ffmpeg'  => env('FFMPEG', '/usr/bin/ffmpeg'),
        'ffprobe' => env('FFPROBE', '/usr/bin/ffprobe')
    ]
];