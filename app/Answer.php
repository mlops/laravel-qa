<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getBodyHtmlAttribute()
    {
        return \Parsedown::instance()->text($this->body);
    }

    public static function boot()
    {
        parent::boot();
        // esta variavel $answer pode ser qualquer nome
        // esta funçao sincroniza valor answers_count na tbl answers com answers tbl question_id  
        static::created(function($answer){
            $answer->question->increment('answers_count');
            $answer->question->save();
        });
    
    }

      public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
        // return $this->created_at->formatLocalized('%d/%B %A');
        // return $this->created_at->format('d-m-Y');
    }
}
