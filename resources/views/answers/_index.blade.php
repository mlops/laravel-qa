<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h2>{{ $answerCount . " " . str_plural('Answers', $answerCount)}}</h2>
                </div>
                <hr>
                @include('layouts._flash-message')
                @foreach ($answers as $answer)
                <div class="media">
                    <div class="d-flex flex-column vote-controls">
                        {{-- <a href="#" title="This answer is useful" class="vote-up">
                            <i class="fas fa-caret-up fa-3x"></i>
                        </a>
                        <span class="votes-count">1230</span>
                        <a href="#" title="This answer is no useful" class="vote-down off">
                            <i class="fas fa-caret-down fa-3x"></i>
                        </a> --}}
                        <a href="#" title="This answer is useful" class="vote-up-{{Auth::guest() ? 'favorited' : '' }}" onclick="event.preventDefault(); document.getElementById('up-vote-answer-{{ $answer->id }}').submit()">
                            <i class="fas fa-caret-up fa-3x"></i>
                        </a>
                        <form action="/answers/{{ $answer->id }}/vote" method="POST" id="up-vote-answer-{{ $answer->id }}" style="display:none">
                            @csrf
                            <input type="hidden" name="vote" value="1">
                        </form>
                        
                        <span class="votes-count">{{ $answer->votes_count}}</span>
                        
                        <a href="#" title="This question is no useful" class="vote-down {{Auth::guest() ? 'favorited' : '' }}" onclick="event.preventDefault(); document.getElementById('down-vote-answer-{{ $answer->id }}').submit()">
                            <i class="fas fa-caret-down fa-3x"></i>
                        </a>
                        <form action="/answers/{{ $answer->id }}/vote" method="POST" id="down-vote-answer-{{ $answer->id }}" style="display:none">
                            @csrf
                            <input type="hidden" name="vote" value="-1">
                        </form>
                        
                        {{-- <a href="#" title="Click to mark as favorite question(Click again to undo)" class="favorite mt-2 {{ Auth::guest() ? 'off' : ($answer->is_favorited ? 'favorited' : '') }}" onclick="event.preventDefault(); document.getElementById('favorite-question-{{ $answer->id }}').submit()">
                        
                            <i class="fas fa-star fa-2x"></i>
                            <span class="favorites-count">{{ $answer->favorites_count}}</span>
                        </a>
                        <form action="/answers/{{ $answer->id }}/favorites" method="POST" id="favorite-question-{{ $answer->id }}" style="display:none">
                            @csrf
                            @if($answer->is_favorited)
                            @method ('DELETE')
                            @endif
                        
                        </form> --}}
                        @can('accept',$answer)
                    
                            <a title="Mark this answer as best answer" class="{{ $answer->status}} mt-2"
                            onclick="event.preventDefault(); document.getElementById('accept-answer-{{ $answer->id }}').submit()">
                                <i class="fas fa-check fa-2x"></i>
                            </a>
                        <form action="{{route('answers.accept', $answer->id) }}" method="POST" id="accept-answer-{{ $answer->id }}" style="display:none">
                            @csrf

                        </form>
                    @else
                        @if ($answer->is_best)
                            <a title="The question owner accepted this answer as best answer" class="{{ $answer->status}} mt-2" >
                                <i class="fas fa-check fa-2x"></i>
                            </a>
                            
                        @endif
                    @endcan

                    </div>
                    <div class="media-body">
                        {!! $answer->body_html !!}
                        <div class="row">
                            <div class="col-4">
                                <div class="ml-auto">
                                    @can ('update', $answer)
                                    <a href="{{route('questions.answers.edit',[$question->id, $answer->id])}}" class="btn btn-sm btn-outline-info">Edit</a>
                                    @endcan
                                    @can ('delete', $answer)
                                    <form class="form-delete" action="{{ route('questions.answers.destroy', [$question->id, $answer->id])}}" method="post">
                                        @method('DELETE')
                                        {{-- {{methos_field('DELETE')}} --}}
                                        @csrf
                                        {{-- {{ ctrf_token() }} --}}
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('@lang('question.confirm')')">Delete</button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                            <div class="col-4"></div>
                            <div class="col-4">
                                <span class="text-muted">Answered {{ $answer->created_date}}</span>
                                <div class="media mt-2">
                                    <a href="{{ $answer->user->url}}" class="pr-2">
                                        <img src="{{ $answer->user->avatar}}"></a>
                            
                                    <div class="media-body mt-1">
                                        <a href="{{$answer->user->url}}">{{$answer->user->name}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                </div>
                <hr>
                @endforeach
            </div>
        </div>
    </div>
</div>