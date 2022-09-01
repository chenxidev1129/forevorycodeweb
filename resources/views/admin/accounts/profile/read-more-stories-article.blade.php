@extends('admin.layouts.app')
@section('content')
@section('title', __('message.edit_account'))
<!-- article -->

<section class="article p-30">
    <div class="container">
    @if(!empty($getStoriesArticle))
        <div class="row">
            <div class="col-md-5 text-center text-md-right order-md-2 mb-4 mb-md-0">
                <img data-progressive="{{ getUploadMedia($getStoriesArticle->image) }}" class="img-fluid progressive__img progressive--not-loaded" alt="Articel-img">
            </div>
            <div class="col-md-7 order-md-1">
                <div class="article_head mb-2 mb-md-4 ">
                    <h1 class="h34 font-nbd my-24 mt-0">{{ ucfirst($getStoriesArticle->title) }}</h1>
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><em class="icon-user"></em>  @if(!empty($getStoriesArticle->profile->user)){{ $getStoriesArticle->profile->user->first_name }} @if(!empty($getStoriesArticle->profile->user->last_name)){{ $getStoriesArticle->profile->user->last_name }}@endif  @endif</li>
                        <li class="list-inline-item"><em class="icon-calendar"></em> {{ getConvertedDate($getStoriesArticle->created_at , 2) }}</li>
                    </ul>
                </div>
                {!! $getStoriesArticle->text !!}
            </div>
        </div>
    @else
    <div class="row ">
        <div class="col-md-5 text-center text-md-right order-md-2 mb-4 mb-md-0">
            <img data-progressive="{{ url('assets/images/view-profile/article01.jpg') }}" class="img-fluid progressive__img progressive--not-loaded" alt="Articel-img">
        </div>
        <div class="col-md-7 order-md-1">
            <div class="article_head mb-2 mb-md-4 ">
                <h1 class="h34 font-nbd my-24 mt-0">Remembering Foxtrot Squad and the battle of the cliff Daniel Sarris</h1>
                <ul class="list-inline mb-0">
                    <li class="list-inline-item"><em class="icon-user"></em> Christine Sarris</li>
                    <li class="list-inline-item"><em class="icon-calendar"></em> September 5, 2020</li>
                </ul>
            </div>
            <p>Mauris lorem neque, tristique vitae est euismod, elementum posuere urna. Fusce quis nisi semper, faucibus odio in, malesuada dui. Vestibulum sit amet erat fermentum, imperdiet quam eu, vehicula ipsum. Ut iaculis et lacus non convallis. Vestibulum non tempor orci, ac aliquet massa. Duis dapibus sapien id felis lobortis facilisis faucibus nec augue. Quisque quis mi nec lorem interdum dapibus. Curabitur tincidunt venenatis erat, vel vestibulum quam iaculis vitae. Nulla tempus lacinia tellus. Aenean erat dolor, dapibus sit amet hendrerit a, tempus sit amet risus. Aliquam sit amet euismod ipsu</p>
            <p>Nulla quis ipsum eget elit consectetur varius tempus vel quam. Vestibulum vitae velit bibendum, efficitur dui vitae, porttitor tortor. Aenean sollicitudin elit ligula, sit amet vestibulum neque scelerisque vel. Curabitur suscipit rutrum justo. Vivamus venenatis interdum odio, quis molestie tortor finibus nec. Quisque gravida, dolor at rutrum tristique, nisi nunc tempor diam, vel mattis lorem erat sit amet justo. Maecenas sagittis tempus velit vitae placerat. Maecenas consectetur in est sed scelerisque. Nunc nibh erat, scelerisque vitae interdum a, posuere id lorem.</p>
            <p>Sed aliquam pharetra magna vitae dignissim. Integer hendrerit interdum tellus eget cursus. Ut elementum, lorem tempor semper blandit, eros quam gravida risus, quis faucibus leo nisi at elit. Integer sit amet lorem ut mauris congue rhoncus. Nullam eleifend ultrices mi eu semper. Suspendisse potenti. Pellentesque ipsum quam, volutpat id velit vulputate, hendrerit dapibus nunc. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Proin quis felis massa. Sed euismod nisl ut fermentum dictum. Maecenas interdum urna turpis, non mattis nunc tincidunt eu. Interdum et malesuada fames ac ante ipsum primis in faucibus. Praesent luctus dolor diam, et tempor lacus ornare sed. In mollis faucibus bibendum. Morbi tempor metus non arcu auctor sollicitudin. Mauris massa lectus, tincidunt vitae varius vitae, porta vel eros.</p>
        </div>
        
    </div>    
    @endif    
    </div>
</section>
@endsection