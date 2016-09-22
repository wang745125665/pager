# pager
A simple php pagination plugin，一个简单的php分页插件
#调用方式
```php
require_once('Pager.php');
$total = 100;
$p     = 1;
$pager = new Pager($total, $p, array('limit'=>10));
echo $pager->show();
```
#样式
提供样式示例，可自由扩展，#pager为包裹分页的容器
```css
#pager ul { list-style: none; }
#pager li { display: inline-block; }
#pager a { padding: 6px; text-decoration: none; color: blue;}
#pager a.active { color: red; }
#pager a:hover { color: #666; text-decoration: none; }
#pager a.disable {}
```

![演示效果](http://www.tenshi.cc/files/upload/image/20160922/1474552413538577.jpg)
#支持参数
```php
array(  
  'limit' 	=> 10, 		//每页显示条数  
  'groups' 	=> 5, 		//每次显示多少页  
  'first' 	=> '首页',	//首页名称  
  'last'		=> '末页',	//末页名称  
  'prev' 		=> '上一页', 	//上一页  
  'next' 		=> '下一页', 	//下一页  
  'page_name'	=> 'p'		//页的名称，?list=curr  
)
```
#不同视图
插件提供两种视图，第二种用 $pager->show(2); 来调用，视图也可参照源代码中的 view_1()、view_2() 函数来实现
