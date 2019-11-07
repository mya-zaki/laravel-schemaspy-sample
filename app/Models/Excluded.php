<?php

namespace App\Models;

use App\Models\Base\Extend as Model;

class Excluded extends Model
{
    /**
     * Get the post that owns the comment.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
