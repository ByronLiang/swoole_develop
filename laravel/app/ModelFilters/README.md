# 查询过滤

使用 [Eloquent Filter https://github.com/Tucker-Eric/EloquentFilter](https://github.com/Tucker-Eric/EloquentFilter) 依赖实现，具体使用看[官方文档](http://tucker-eric.github.io/EloquentFilter/)

## Introduction
Lets say we want to return a list of users filtered by multiple parameters. When we navigate to:

`/users?name=er&last_name=&company_id=2&roles[]=1&roles[]=4&roles[]=7&industry=5`

`$request->all()` will return:

```php
[
    'name'       => 'er',
    'last_name'  => '',
    'company_id' => '2',
    'roles'      => ['1','4','7'],
    'industry'   => '5'
]
```

To filter by all those parameters we would need to do something like:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $query = User::where('company_id', $request->input('company_id'));

        if ($request->has('last_name'))
        {
            $query->where('last_name', 'LIKE', '%' . $request->input('last_name') . '%');
        }

        if ($request->has('name'))
        {
            $query->where(function ($q) use ($request)
            {
                return $q->where('first_name', 'LIKE', $request->input('name') . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $request->input('name') . '%');
            });
        }

        $query->whereHas('roles', function ($q) use ($request)
        {
            return $q->whereIn('id', $request->input('roles'));
        })
            ->whereHas('clients', function ($q) use ($request)
            {
                return $q->whereHas('industry_id', $request->input('industry'));
            });

        return $query->get();
    }

}
```

To filter that same input With Eloquent Filters:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;

class UserController extends Controller
{

    public function index(Request $request)
    {
        return User::filter($request->all())->get();
    }

}
```
