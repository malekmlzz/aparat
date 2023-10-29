<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    //region model constants
    const STATE_PENDING = 'pending'; // در انتظار تایید
    const STATE_READ = 'read'; //خوانده شده
    const STATE_ACCEPTED = 'accepted'; // تایید شده
    const STATES = [
        self::STATE_PENDING,
        self::STATE_READ,
        self::STATE_ACCEPTED,
    ];
    //endregion model constants

    //region model configs
    protected $table = 'comments';

    protected $fillable = [
        'user_id', 'video_id', 'parent_id',
        'body', 'state'
    ];
    //endregion model configs

    //region model relations
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    //endregion model relations

    //region custom static methods
    public static function channelComments($userId)
    {
        return Comment::join('videos', 'comments.video_id', '=', 'videos.id')
            ->selectRaw('comments.*')
            ->where('videos.user_id', $userId);
    }
    //endregion custom static methods

    //region override model methods
    public static function boot()
    {
        parent::boot();

        static::deleting(function($comment){
            $comment->children()->delete();
        });
    }
    //endregion override model methods
}
