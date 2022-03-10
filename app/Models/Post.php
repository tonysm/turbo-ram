<?php

namespace App\Models;

use App\Models\Events\HasRecordableEvents;
use App\Models\Events\RecordsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;

class Post extends Model implements RecordsEvents
{
    use HasFactory;
    use HasRichText;
    use HasRecordableEvents;

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
