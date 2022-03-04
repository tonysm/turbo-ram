<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopePosts(Builder $query): void
    {
        $query->where('recordable_type', (new Post())->getMorphClass());
    }

    public function scopeComments(Builder $query): void
    {
        $query->where('recordable_type', (new Comment())->getMorphClass());
    }

    public function bucket()
    {
        return $this->belongsTo(Bucket::class);
    }

    public function parent()
    {
        return $this->belongsTo(Recording::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function recordable()
    {
        return $this->morphTo();
    }

    public function children()
    {
        return $this->hasMany(Recording::class, 'parent_id');
    }

    public function setBucketAttribute(Bucket $bucket)
    {
        $this->bucket()->associate($bucket);
    }

    public function setParentAttribute(?Recording $parent)
    {
        if (! $parent) return;

        $this->parent()->associate($parent);
    }

    public function setCreatorAttribute(User $creator)
    {
        $this->creator()->associate($creator);
    }

    public function setRecordableAttribute($recordable)
    {
        $this->recordable()->associate($recordable);
    }

    public function recordablePartialPath()
    {
        return $this->recordable->recordablePartialPath();
    }

    public function recordablePartialData(array $options = [])
    {
        return $this->recordable->recordablePartialData(array_replace($options, [
            'recording' => $this,
        ]));
    }

    public function computeParentsList()
    {
        $parents = collect();
        $current = $this;

        while ($current = $current->parent) {
            $parents->add($current);
        }

        return $parents->reverse()->values();
    }

    public function breadcrumbsName()
    {
        return $this->recordable->breadcrumbsName();
    }

    public function breadcrumbsShowPath()
    {
        return $this->recordableShowPath();
    }

    public function recordableShowPath(array $options = [])
    {
        return $this->recordable->recordableShowPath($this, $options);
    }

    public function recordablePostShowPath(array $options = [])
    {
        return route('buckets.posts.show', array_replace($options, [
            'bucket' => $this->bucket,
            'recording' => $this,
        ]));
    }

    public function recordableCommentShowPath(array $options = [])
    {
        return route('buckets.posts.show', array_replace($options, [
            'bucket' => $this->bucket,
            'recording' => $this->parent,
            $this->pageFragmentId(),
        ]));
    }

    public function pageFragmentId()
    {
        return sprintf('#recording_%s', $this->id);
    }
}
