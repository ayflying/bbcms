##menu
名称：menu
功能：常用于网站顶部或者各地方的栏目使用，以获取站点栏目信息，方便网站会员分类浏览整站信息。

## 补充说明
该标签允许嵌套3层

## 语法
```php
{bb:menu topid='' row='' item=''}

</bb:menu>
```

## 参数介绍
* topid=''：上级栏目的ID
* row='10':调用的数量
* item='ltag'：参数的变量

## 输出主要字段
* {$ltag.typeid}:显示当前栏目id
* {$ltag.topid}：显示上级栏目id
* {$ltag.name}：显示当前栏目名称
* {$ltag.url}：显示当前栏目的链接地址
* ......
更多变量可以使用{:dump($ltag)}遍历输出查询

## 使用范例

### 单层栏目
```php
<bb:menu topid="0">
    <li><a href="{$ltag.url}">{$ltag.name}</a></li>
</bb:menu>
```

### 多层栏目
```php
<bb:menu topid="0">
<li><a href="{$ltag.url}">{$ltag.name}<br></a>
  <ul>
    <bb:menu topid="$ltag.typeid">
        <li><a href="{$ltag.url}">{$ltag.name}</a></li>
    </bb:menu>
  </ul>
</li>
</bb:menu>
```