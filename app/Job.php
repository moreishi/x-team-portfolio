<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    protected $hidden = ['company_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @param $tags
     * @return bool
     */
    public function hasAnyTag($tags)
    {
        return null !== $this->tags()->whereIn('name', $tags)->first();
    }

    /**
     * @param $tag
     * @return bool
     */
    public function hasTag($tag)
    {
        return null !== $this->tags()->where('name', $tag)->first();
    }
}
