<?php

namespace Modules\TagGable;

use Modules\TagGable\Entities\Tag;
use Modules\TagGable\Entities\TagGable;
use Modules\TagGable\Entities\TagGroup;

/**
 * Trait Taggable.
 *
 * @method static static WithAnyTag($tagNames)
 * @method static static WithoutTags($tagNames)
 * @mixin \Eloquent
 */
trait TagGableTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|Tag
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'gable', 'tag_gables');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function tagNames()
    {
        return $this->tags()->pluck('name');
    }

    /**
     * 获取这个模型所有的标签.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function existingTags(array $columns = ['name', 'id'])
    {
        $join_table = Tag::getModel()->getTable();

        return TagGable::distinct()
            ->join($join_table, 'tag_id', '=', $join_table.'.id')
            ->where('gable_type', '=', (new static())->getMorphClass())
            ->orderBy('name', 'ASC')
            ->get($columns);
    }

    /**
     * 根据标签组获取这个模型所有的标签.
     *
     * @param array  $groups
     * @param string $group_field
     * @param array  $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function existingTagsInGroups(array $groups, $group_field = 'name', array $columns = ['name', 'id'])
    {
        $tags_table = Tag::getModel()->getTable();
        $tag_groups_table = TagGroup::getModel()->getTable();

        return TagGable::distinct()
            ->join($tags_table, 'tag_id', '=', $tags_table.'.id')
            ->join($tag_groups_table, 'tag_group_id', '=', $tag_groups_table.'.id')
            ->where('gable_type', '=', (new static())->getMorphClass())
            ->whereIn($tag_groups_table.'.'.$group_field, $groups)
            ->orderBy($tags_table.'.name', 'ASC')
            ->get($columns);
    }
}
