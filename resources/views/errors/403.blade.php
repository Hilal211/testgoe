@extends( Auth::user()->ser_type == 1 ?  'payer.layout.app' : 'payee.layout.app' )

@section('content')
<div class="content-wrapper test">
    <section class="content txn-creation-column">

      <div class="error-page">
        <h2 class="headline text-orange">Oops!</h2>

        <div class="error-content">
        <h3><i class="fa fa-warning text-orange"></i> {{ $exception->getMessage() }} </h3>

          <p>
            you may <a href="{{ URL::previous() }}">retrun back</a> or try using the search form.
        </p>
    </div>
</div>
<!-- /.error-page -->
</section>
</div>
@stop
