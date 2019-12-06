# 评论模块

#### 注：你可以对里面的方法进行完善，但是严禁往里面丢业务逻辑相关代码      

主要对用户和评论对象进行多态归类，带软删功能，可用于评论的通过与否，extend字段可存多个字段，比如是否带图评论，管理员是否已回复等

## 启用模块
```
php artisan module:enable Comment
```

## Usage
Product
```
namespace App\Models;

use Modules\Comment\CommentTargetTrait;

class Product extends Model
{
    use CommentTargetTrait;

}
```

Subject
```
namespace App\Models;

use Modules\Comment\CommentTargetTraint;

class Subject extends Model
{
    use CommentTargetTrait;

}
```

使用了CommentTargetTrait，不论商品或者专题都可以使用`comments`关联关系，查出相关评论
```
$product = App\Product::with('comments')->find(1);
$subject = App\Subject::with('comments')->find(1);
```

同样也有CommentUserTrait, 不论用户还是管理员都可以`comments`查相关评论
```
$user = User::with('comments')->find(1);
$admin = Administrator::with('comments')->find(1);
```

日常回复
 ```
$product = Product::first();
 
$comment = Comment::find(1);
 
$replyComment = $product->comment([
   'content' => '这是管理员回复',
],$admin,$comment);

```
