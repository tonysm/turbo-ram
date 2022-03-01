<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;

class Post extends Model
{
    use HasFactory;
    use HasRichText;

    protected $guarded = [];
    protected $richTextFields = ['content'];

    public function recording()
    {
        return $this->morphOne(Recording::class, 'recordable');
    }

    public function recordablePartialPath()
    {
        return 'posts._post';
    }

    public function recordablePartialData(array $options = [])
    {
        return array_replace($options, [
            'post' => $this,
        ]);
    }
}
