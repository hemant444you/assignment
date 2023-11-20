@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row d-flex justify-content-around">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">{{ __('Bucket Suggestion') }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif


                    <div class="suggestion-box">
                        <form action="{{route('suggestion.store')}}" method="post">
                            @csrf
                            @forelse($balls as $ball)
                            <div class="form-group row mb-3">
                                <label for="ball" class="col-sm-4 col-form-label">
                                    <span style="display:inline-block;height:24px;width:24px;border-radius:24px;line-height: 24px;margin-top:-20px;
                                            background-color:{{$ball->name}};"></span>
                                    {{$ball->name}}</label>
                                <div class="col-sm-8">
                                  <input type="hidden" name="ball_id[]" value="{{$ball->id}}">
                                  <input type="text" name="quantity[]" class="form-control" value="{{$ball->balls_quantity()}}" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                                </div>
                            </div>
                            @empty
                            <p class="text-danger">Please add balls and buckets to suggest occupation</p>
                            @endforelse
                            <div class="form-group row mt-5">
                                <div class="col-md-12">
                                    <input type="submit" class="btn btn-block btn-primary" value="Save Suggestion" {{Auth::User()->balls->count() > 0 ? '' : 'disabled'}}>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Result') }}</div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th>S No</th>
                                <th>Bucket</th>
                                <th>Balls</th>
                                <th>Space</th>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @forelse($buckets as $bucket)
                                <?php $i++; ?>
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$bucket->name}}</td>
                                    <td>
                                        @forelse($bucket->ball_buckets as $ball_bucket)
                                            <span class="mr-3">{{$ball_bucket->ball->name}} : {{$ball_bucket->quantity}}</span>
                                        @empty
                                        @endforelse
                                    </td>
                                    <td>{{$bucket->space}}</td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
