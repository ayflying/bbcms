<?php
return [
	'tid/[:tid]' => ['@portal/lists/index',['method' => 'get'],['tid'=>'\d+']],
	'aid/[:aid]' => ['@portal/article/index',['method' => 'get'],['aid'=>'\d+']],
    'add/:tid$' => ['@portal/post/add',['method' => 'post|get'],['tid'=>'\d+']],
	'edit/:aid$' => ['@portal/post/edit',['method' => 'post|get'],['aid'=>'\d+']],
    's' => '@portal/search/index',
    'uid/:uid' => '@member/index/index',
];
