<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['title', 'body'];

    public function user() {
        return $this->belongsTo(User::class);
    }   
    
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = str_slug($value);
    }

    public function getUrlAttribute()
    {
        // 1) without  RouteServiceProvider config to slug {{ $question->url }} in index.blade.php
        // return route("questions.show", $this->id);
        // 2) its possible because RouteServiceProvider config to slug
        return route("questions.show", $this->slug);
    }
    
    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
        // return $this->created_at->formatLocalized('%d/%B %A');
        // return $this->created_at->format('d-m-Y');
    }

    public function getStatusAttribute()
    {
        if ($this->answers_count > 0) {
            if($this->best_answer_id){
                return "answered-accepted";
            }
            return "answered";
        }
        return "unanswered";
    }

    public function getBodyHtmlAttribute()
    {
        return \Parsedown::instance()->text($this->body);
    }


    public function answers()
    {
        return $this->hasMany(Answer::class);

    }
    public function acceptBestAnswer(Answer $answer)
    {
        $this->best_answer_id = $answer->id;
        $this->save();
    }

}
