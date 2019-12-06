<?php

namespace Modules\TagGable\Entities;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static self InGroup($group_type, $field = 'type')
 */
class Tag extends \App\Models\Model
{
    protected $hidden = [
        'created_at',
        'updated_at',
        'sort_order',
        'pivot',
    ];

    protected $casts = [
        'extend' => 'array',
    ];

    protected $attributes = [
        'extend' => null,
    ];

    public function setExtendAttribute($value)
    {
        $value && $this->attributes['extend'] = json_encode($value);
    }

    public function tagGables()
    {
        return $this->hasMany(TagGable::class);
    }

    public function tagGroup()
    {
        return $this->belongsTo(TagGroup::class);
    }

    public function scopeInGroup(Builder $query, $group_type, $field = 'type')
    {
        return $query->whereHas('tagGroup', function (Builder $query) use ($group_type, $field) {
            $query->whereIn($field, (array) $group_type);
        });
    }

    public function setGroup($val, $field = 'name')
    {
        $group = TagGroup::firstOrCreate([
            $field => $val,
        ]);
        $this->update([
            'tag_group_id' => $group->id,
        ]);

        return $this;
    }
}
