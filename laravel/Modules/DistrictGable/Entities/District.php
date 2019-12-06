<?php

namespace Modules\DistrictGable\Entities;

class District extends \App\Models\Model
{
    protected $hidden = [
        'is_edit',
        'latitude',
        'longitude',
        'is_city',
        'sort_order',
        'pinyin',
        'created_at',
        'updated_at',
        'pids',
        'name',
        'pivot',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (self $model) {
            if ($model->pid) {
                $parent = $model->parent;
                if (!$parent->pids) {
                    $pids = $parent->id;
                } else {
                    $pids = explode(',', $parent->pids);
                    $pids[] = $parent->id;
                    $pids = implode(',', $pids);
                }
                $model->pids = $pids;
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'pid');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'pid');
    }

    /**
     * 首字母
     * initial.
     *
     * @return bool|string
     */
    public function getInitialAttribute()
    {
        return strtoupper(substr($this->pinyin, 0, 1));
    }

    /**
     * 分类级数
     * level.
     *
     * @return bool|string
     */
    public function getLevelAttribute()
    {
        return count(explode(',', $this->pids)) + 1;
    }

    public static function generationChildren($rows = [], $id = 'id', $pid = 'pid', $child = 'children', $root = null)
    {
        if (empty($rows)) {
            $rows = static::get()->toArray();
        }
        $tree = [];
        if (is_array($rows)) {
            $array = [];
            foreach ($rows as $key => $item) {
                $array[$item[$id]] = &$rows[$key];
            }
            foreach ($rows as $key => $item) {
                $parentId = $item[$pid];
                if ($root == $parentId) {
                    $tree[] = &$rows[$key];
                } else {
                    if (isset($array[$parentId])) {
                        $parent = &$array[$parentId];
                        $parent[$child][] = &$rows[$key];
                    }
                }
            }
        }

        return $tree;
    }
}
