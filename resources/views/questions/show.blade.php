@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="d-flex align-items-center">
                            <h1>{{ $question->title }}</h1>
                            <div class="ml-auto">
                                <a href="{{ route('questions.index')}}" class="btn btn-outline-secondary">
                                    Back to all Questions
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="media">
                        <div class="d-flex flex-column vote-controls">


                            <a href="#" title="This question is useful" class="vote-up-{{Auth::guest() ? 'favorited' : '' }}" onclick="event.preventDefault(); document.getElementById('up-vote-question-{{ $question->id }}').submit()">
                                <i class="fas fa-caret-up fa-3x"></i>
                            </a>
                            <form action="/questions/{{ $question->id }}/vote" method="POST" id="up-vote-question-{{ $question->id }}" style="display:none">
                                @csrf
                                <input type="hidden" name="vote" value="1">
                            </form>

                            <span class="votes-count">{{ $question->votes_count}}</span>

                            <a href="#" title="This question is no useful" 
                                class="vote-down {{Auth::guest() ? 'favorited' : '' }}"
                                onclick="event.preventDefault(); document.getElementById('down-vote-question-{{ $question->id }}').submit()"> 
                                <i class="fas fa-caret-down fa-3x"></i>
                            </a>
                            <form action="/questions/{{ $question->id }}/vote" method="POST" id="down-vote-question-{{ $question->id }}" style="display:none">
                                @csrf
                                <input type="hidden" name="vote" value="-1">
                            </form>
                            
                            <a href="#" title="Click to mark as favorite question(Click again to undo)" 
                                class="favorite mt-2 {{ Auth::guest() ? 'off' : ($question->is_favorited ? 'favorited' : '') }}" 
                                onclick="event.preventDefault(); document.getElementById('favorite-question-{{ $question->id }}').submit()">

                                <i class="fas fa-star fa-2x"></i>
                                <span class="favorites-count">{{ $question->favorites_count}}</span>
                            </a>
                            <form action="/questions/{{ $question->id }}/favorites" method="POST" id="favorite-question-{{ $question->id }}" style="display:none">
                                @csrf
                                @if($question->is_favorited)
                                @method ('DELETE')
                                @endif

                            </form>




                            
                        </div>
                        <div class="media-body">
                            {{-- _html em body markdow syntax laravel new --}}
                            {!! $question->body_html !!}


                            <div class="float-right">
                                <span class="text-muted">Answered {{ $question->created_date}}</span>
                                <div class="media mt-2">
                                    <a href="{{ $question->user->url}}" class="pr-2">
                                        <img src="{{ $question->user->avatar}}"></a>

                                    <div class="media-body mt-1">
                                        <a href="{{$question->user->url}}">{{$question->user->name}}</a>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('answers._index', [
    'answers' => $question->answers,
    'answerCount' => $question->answers_count
    ])
    @include('answers._create')
</div>
@endsection