<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;

trait RESTfulTrait
{
    /**
     * @var string|\Eloquent
     */
    protected $model;

    protected function setRESTfulModel(\Illuminate\Database\Eloquent\Builder $model)
    {
        $this->model = $model
            ->when(method_exists($model->getModel(), 'scopeFilter'), function (Builder $q) {
                return $q->filter(request()->all());
            });
    }

    public function index()
    {
        $models = $this->model;

        if (method_exists($this, 'indexBefore')) {
            $this->indexBefore($models);
        }

        if (request('export') && method_exists($this, 'export')) {
            return $this->export(request('export'), $models->get());
        }

        if (request('per_page') || request('page')) {
            if (request('simple_page')) {
                $models = $models
                    ->simplePaginate(request('per_page'))
                    ->appends(request()->all());
            } else {
                $models = $models
                    ->paginate(request('per_page'))
                    ->appends(request()->all());
            }
        } else {
            $take = request('take');
            $models = $models
                ->when(is_null($take), function ($q) use ($take) {
                    return $q->take(1000);
                })
                ->when(!is_null($take) && is_numeric($take) && $take, function ($q) use ($take) {
                    return $q->take($take);
                })
                ->get();
        }

        if (method_exists($this, 'indexAfter')) {
            $res = $this->indexAfter($models);
            $res && $models = $res;
        }

        return \Response::success(new \App\Http\Resources\GanguoCollection($models));
    }

    /**
     * @param $id
     *
     * @return mixed
     *
     * @throws \Throwable
     */
    public function show($id)
    {
        $model = $models = $this->model
            ->findOrFail($id);

        if (method_exists($this, 'showAfter')) {
            $res = $this->showAfter($model);
            $res && $model = $res;
        }

        return \Response::success($model);
    }

    protected function delete($id)
    {
        /**
         * @var \Illuminate\Support\Collection
         */
        $models = $this->model->withoutGlobalScopes()
            ->whereIn('id', explode(',', $id))
            ->get();

        foreach ($models as $key => $model) {
            if ('force' == request('ac')) {
                $model->forceDelete();
            } elseif (method_exists($model, 'trashed') && $model->trashed()) {
                $model->restore();
            } else {
                $model->delete();
            }
        }

        return \Response::success();
    }
}
