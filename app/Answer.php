<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public $timestamps = true;
    protected $table = 'answers';
    protected $fillable = [
          'body', 'user_id'
      ];
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
        });
        static::deleted(function($answer){
            $answer->question->decrement('answers_count');
            
        });

    }

      public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
        // return $this->created_at->formatLocalized('%d/%B %A');
        // return $this->created_at->format('d-m-Y');
    }
    
    public function getStatusAttribute()
    {
        // return $this->id === $this->question->best_answer_id ? 'vote-accepted' : '';
        return $this->isBest() ? 'vote-accepted' : '';
    }
    public function getIsBestAttribute()
    {
        return $this->isBest();
    }
    public function isBest()
    {
        return $this->id === $this->question->best_answer_id; 
    }

    public function votes()
    {
        return $this->morphToMany(User::class, 'votable');
    }
}
