$( document ).ready(function() {
    $("#articlesList").sortable({
        handle: '.bar',
        cursor: 'move',
        axis: "y",
        start: function(e,ui)
        {
            /* Refresh position only for first drag */
            $(this).sortable("refreshPositions");
        }
    });
});


/* Get count to increment index value */
var storiesArticleAddMoreId = $("#storiesArticleAddMoreId").val();

for (let articleContentItration = 0; articleContentItration < articleContent; articleContentItration++) { // iteration over input
    ClassicEditor.create(document.querySelector('#storiesArticleText'+articleContentItration),{
        toolbar: ['Heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'Link']
    }).then( editor => {
        editor.model.document.on('change:data', () => {
            $('textarea#storiesArticleText'+articleContentItration).val(editor.getData())
        });
    })
    .catch( err => {
        console.error( err.stack );
    } );
}

/* Set dynamic value to parent index and id */
if(storiesArticleAddMoreId){
    articleContent = storiesArticleAddMoreId;
}

/* Default type to add image  */
var type = 'add';
$(".boxImagePreview").hide();
$('.addArticle').click(function() {
    
    $('#articlesList').append(
        '<div class="articleRow commonBox" id="articleIndex'+articleContent+'"><div class="form-group uploadImg mt-4"><input type="hidden" name="stories-articles_image-validation" value="required"><input type="hidden" name="stories-articles-validation" value="required"><input type="hidden" id="articleId'+articleContent+'" name="articles-image-position['+articleContent+']"><label class="mb-0 position-relative overflow-hidden d-flex align-items-center justify-content-center"><div class="text-center"><em class="icon-close"></em><p class="mb-0 mt-2 font-bd h17">Upload Article Image</p></div><input type="file" id="storiesArticleImage'+articleContent+'" name="storiesArticleImage['+articleContent+']" class="updateOnChange resetStoriesArticleImage'+articleContent+'" onchange="uploadStoriesArticlesImage((this), '+articleContent+','+articleContent+' , \''+type+'\');" aria-describedby="storiesArticleImage'+articleContent+'-error" accept="image/*"><img src="" id="articleContent${index}" class="img-fluid" alt="article" style="display: none"></label><span id="storiesArticleImage'+articleContent+'-error" class="help-block error-help-block"></span></div><div class="form-group"><label>Title</label><input type="text" id="storiesArticleTitle'+articleContent+'" name="storiesArticleTitle['+articleContent+']" class="form-control storiesArticleTitle" placeholder="Title"></div><div class="form-group mb-0"><label>Article</label><textarea id="storiesArticleText'+articleContent+'" name="storiesArticleText['+articleContent+']" class="form-control" placeholder="Enter Text Here"></textarea><span id="storiesArticleText'+articleContent+'-error" class="help-block error-help-block"></span></div><div class="action d-flex align-items-center"><a href="javascript:void(0);" class="delete deleteArticleRow"> <em class="icon-delete"></em></a><a href="javascript:void(0);" class="bar"> <em class="icon-bar"></em></a></div></div>'
    );

    ClassicEditor.create(document.querySelector('#storiesArticleText'+articleContent),{
        toolbar: ['Heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'Link']
    }).then( editor => {
        var contentId = articleContent-1;
        editor.model.document.on('change:data', () => {
            $('textarea#storiesArticleText'+contentId).val(editor.getData())
        });
    })
    .catch( err => {
        console.error( err.stack );
    });
    
    articleContent++;

    $('textarea').on('input', function() {
        this.style.height = 'auto';

        this.style.height = (this.scrollHeight) + 'px';
    });

});

/* Check alphanumaric string */ 
$('.storiesArticleTitle, .storiesArticleText, .imageCaption, .videoCaption, .audioCaption').keypress(function(evt){ 

var charCode = (evt.which) ? evt.which : evt.keyCode;
if ( (charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || (charCode >= 48 && charCode <= 57) || charCode == 32 || charCode == 44 || charCode == 46 || charCode == 39 || charCode == 34 || charCode == 13) {
    return true;
}
    return false;
    
});

/* Remove special charactor from string*/ 
$('.storiesArticleTitle, .storiesArticleText, .imageCaption, .videoCaption, .audioCaption').on('input', function() {
    var c = this.selectionStart,
        r = /[^a-z\d\-.,_\s]/gi, 
        v = $(this).val();
    if(r.test(v)) {
        $(this).val(v.replace(r, ''));
        c--;
    }
    this.setSelectionRange(c, c);
});