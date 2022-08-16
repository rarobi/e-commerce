<?php

namespace App\Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\User\Models\User;
use App\Modules\Blog\Models\Blog;

class BlogComment extends Model
{
    protected $fillable = [
        'user_id',
        'blog_id',
        'parent_id',
        'comment',
    ];

    public function blog(){
        return $this->belongsTo(Blog::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function childComments()
    {
        return $this->hasMany(BlogComment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

}
