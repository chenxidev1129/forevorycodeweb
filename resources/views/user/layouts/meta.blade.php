<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="twitter:card" content="summary"></meta>
    <meta name="twitter:title" content="{{$getProfile->profile_name}}"></meta>
    <meta name="twitter:description" content="{{$getProfile->short_description}}"></meta>
    <meta property="twitter:image" content="{{getUploadMedia($getProfile->qrcode_image)}}"></meta>
</head>

<body>
    <div></div>
    </body>
</html>