<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    use VotableTrait;
    public $timestamps = true;
    protected $table = 'questions';

    protected $fillable = ['title', 'body'];

    public function user() {
        return $this->belongsTo(User::class);
    }   
    
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = str_slug($value);
    }

    // public function setBodyAttributes($value)
    // {
    //     //save data clean in DB
    //     $this->attributes['body'] = clean($value);
    // }
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
        // return \Parsedown::instance()->text($this->body);
        return clean($this->bodyHtml());
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
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps(); //'user_id', 'question_id');
    }

    public function isFavorited()
    {
        return $this->favorites()->where('user_id', auth()->id())->count() > 0;
    }
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function excerpt($length)
    {
        return str_limit(strip_tags($this->bodyHtml()), $length);
    }

    public function getExcerptAttribute()
    {
        // return str_limit(strip_tags($this->bodyHtml()), 300);
        return $this->excerpt(250);
    }

    private function bodyHtml()
    {
        return \Parsedown::instance()->text($this->body);
    }

    
}
