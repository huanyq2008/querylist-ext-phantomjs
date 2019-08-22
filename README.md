# querylist-ext-phantomjs
QueryList V3 Plugin: Use PhantomJS to crawl Javascript dynamically rendered pages.(headless WebKit ) 

# querylist-ext-phantomjs 安装
通过`composer`安装:
```
composer require huanyq2008/querylist-ext-phantomjs
```

# querylist-ext-phantomjs 使用
下面演示`QueryList`用一句代码采集页面信息：
```php
$urlarr = [
    'https://mimvp.com',  // 默认 utf-8
    'https://www.qq.com', // 默认 gb2312，需添加 header，否则乱码
    'https://www.dajie.com', // content在前，name在后，匹配错误（从第一个content开始，从最后一个name结束）
    'https://m.toutiao.com',
    'https://www.baidu.com',
    'https://mp.weixin.qq.com/s/NHD6BXCbJYzl4gK-NYBKaw',
];
//插件调用
$ql = QueryList::run('PhantomJs', [
    'binpath'=>'/usr/bin/phantomjs', 
    'url'=>$urlarr[0],
    'debug'=>false
]);
//设置规则
$data = $ql->setQuery(array(
    'title' => array('title','text'),
    'keywords' => array('meta[name=keywords]','content'),
    'description' => array('meta[name=description]','content'),
    'img' => array('img','src')
))->data;
print_r($data);
```
上面的代码实现的功能是采集相关页面的`标题`、`关键字`、`描述`和`图片`,然后分别以二维关联数组的格式输出。
