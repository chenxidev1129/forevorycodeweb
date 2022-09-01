@extends('admin.layouts.app')
@section('content')
@section('title', __('message.transactions'))

<link rel="stylesheet" href="{{ url('assets/css/dataTables.bootstrap4.min.css') }}" type="text/css">
<!-- Main -->
<main class="main-content transactions">
    <div class="adminPageContent">
        <!-- admin page title -->
        <section class="adminPageTitle">
            <h1 class="font-nbd h22">Transactions</h1>
        </section>

        <!-- table -->
        <div class="table commonTable">
        {{ $dataTable->table() }}
        </div>
    </div>       
</main>

@endsection
    
@section('js')

<script src="{{ url('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('assets/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/js/bootbox.min.js') }}"></script>
<script src="{{ url('assets/admin/js/custom.js') }}"></script>
<script>
    $('th:first-child').hover(function(e){

        $(this).attr('data-title', $(this).attr('title'));
        $(this).removeAttr('title');
    },
    function(e){
        $(this).attr('title', $(this).attr('data-title'));
    });
</script>
{{ $dataTable->scripts() }}
@endsection