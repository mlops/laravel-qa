<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use App\Http\Requests\AskQuestionRequest;

class QuestionsController extends Controller
{

    // este construct bloqueia todos os argumentos exceto index e show, se clicar em askquestion(create) redireciona pra login
    public function __construct() {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // \DB::enableQueryLog();
        $questions = Question::with('user')->latest()->paginate(3);

        return view('questions.index', compact('questions'));
        // view('questions.index', compact('questions'))->render();

        // dd(\DB::getQueryLog());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $question = new Question();
        return view('questions.create', compact('question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AskQuestionRequest $request)
    {
        // $request->user()->questions()->create($request->all());
        $request->user()->questions()->create($request->only('title', 'body'));

        // return redirect('/questions');
        return redirect()->route('questions.index')->with('success', trans('question.submited'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        $question->increment('views'); //aqui incrementa views campo BD e ja salva funçao incrementa do laravel.

        return view('questions.show', compact('question'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //authorize came from policy and register in AuthServiceProvider
        $this->authorize("update", $question);

        return view("questions.edit", compact('question'));
    }

    /**
     * Update the specified resource in storage. 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(AskQuestionRequest $request, Question $question)
    {
        //authorize came from policy and register in AuthServiceProvider
        $this->authorize("update", $question);

        $question->update($request->only('title', 'body'));
        return redirect('/questions')->with('update', trans('question.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        //authorize came from policy and register in AuthServiceProvider
        $this->authorize("update", $question);

        $question->delete();

           return redirect('/questions')->with('delete', trans('question.delete'));
    }
}
