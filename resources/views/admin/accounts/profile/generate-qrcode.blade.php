<img src="{{ getUploadMedia($result->qrcode_image) }}" class="mx-auto" id="qrCodeImg" alt="QR-code">
<a href="{{ url('/admin/downlaod-qrcode/'.$result->id) }}" download="" target="_blank" class="btn btn-sm btn-primary d-flex ripple-effect">Download</a>
<input type="hidden" id="copyImgUrl" value="{{ $result->shared_link }}">
<ul class="list-inline">
    <!-- <li class="list-inline-item"><a onclick="getAnotherSticker()" href="javascript:void(0);">Get Another Sticker</a></li>
    <li class="list-inline-item">|</li> -->
    <li class="list-inline-item position-relative share">
        <a href="javascript:void(0);">Share</a>
        <div class="socialMedia row no-gutters">
            <div class="col-6">
                <a href="{{ Share::load( getUploadMedia($result->qrcode_image), $result->profile_name)->facebook() }}" class="facebook ripple-effect" target="_blank" rel="noopener"><em class="icon-facebook"></em></a>
            </div>
            <div class="col-6">
                <a href="{{ Share::load(getUploadMedia($result->qrcode_image), $result->profile_name)->twitter() }}" class="twitter ripple-effect" target="_blank" rel="noopener"><em class="icon-twitter"></em></a>
            </div>
            <div class="col-6">
                <a href="{{ Share::load(getUploadMedia($result->qrcode_image), $result->profile_name)->email() }}" class="mail ripple-effect"><em class="icon-mail"></em></a>
            </div>
            <div class="col-6">
                <a href="{{ Share::load(getUploadMedia($result->qrcode_image), $result->profile_name)->linkedin() }}" class="linkedin ripple-effect" target="_blank" rel="noopener"><em class="icon-linkedin"></em></a>
            </div> 
        </div>
    </li>
    <li class="list-inline-item">|</li>
    <li class="list-inline-item"><a onclick="copyQrCode()" href="javascript:void(0);">Copy Link</a></li>
</ul>

<script>

    $('#qrCode .share > a').click(function () {
        $('.socialMedia').toggleClass('show');
    })

    /* Copy Qr code url to clipboard */
    function copyQrCode() {
        var copyText = document.getElementById("copyImgUrl");
        copyText.select();
        navigator.clipboard.writeText(copyText.value).then(function() {
            _toast.success("Copied Link");
        }, function() {
            _toast.error("Copy error");
        });
    }
    
</script>