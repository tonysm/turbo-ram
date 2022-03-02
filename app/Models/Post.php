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

    public function breadcrumbsName()
    {
        return str($this->title)->limit(42);
    }

    public function recordableShowPath(Recording $recording, array $options = [])
    {
        return $recording->recordablePostShowPath($options);
    }
}
