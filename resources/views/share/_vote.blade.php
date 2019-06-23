@if($model instanceof App\Question)
@php
$name = 'question';
$firstURISegment = 'questions';

@endphp
@elseif($model instanceof App\Answer)
@php
$name = 'answer';
$firstURISegment = 'answers';

@endphp
@endif

@php
    $formId = $name ."-" . $model->id;
    $formAction = "/{$firstURISegment}/{$model->id}/vote";
@endphp

<div class="d-flex flex-column vote-controls">
    <a href="#" title="This {{$name}} is useful" class
    ="vote-up-{{Auth::guest() ? 'favorited' : '' }}" onclick="event.preventDefault(); document.getElementById('up-vote-{{$formId }}').submit()">
        <i class="fas fa-caret-up fa-3x"></i>
    </a>
    <form action="{{$formAction}}" method="POST" id="up-vote-{{$formId }}" style="display:none">
        @csrf
        <input type="hidden" name="vote" value="1">
    </form>

    <span class="votes-count">{{ $model->votes_count}}</span>

    <a href="#" title="This {{$name}} is no useful" class="vote-down {{Auth::guest() ? 'favorited' : '' }}" onclick="event.preventDefault(); document.getElementById('down-vote-{{$formId }}').submit()">
        <i class="fas fa-caret-down fa-3x"></i>
    </a>
    <form action="{{$formAction}}" method="POST" id="down-vote-{{$formId }}" style="display:none">
        @csrf
        <input type="hidden" name="vote" value="-1">
    </form>

    @if ($model instanceof App\Question)
        @include('share._favorite', [
            'model' => $model
        ])
    @elseif($model instanceof App\Answer)
        @include('share._accept', [
        'model' => $model
        ])
    @endif

</div>