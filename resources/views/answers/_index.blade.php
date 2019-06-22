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
                   
                        
                        @include('share._vote', [
                        'model' => $answer
                        ])

                    
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
                               @include('share._author', [ 
                                   'model' => $answer,
                                   'label' => 'answered'
                               ])
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