<?php
return [
    'aid/:aid' => '@portal/article/index',
	'tid/[:tid]' => '@portal/lists/index',
	'add/:tid' => '@portal/post/add',
	'edit/:aid' => '@portal/post/edit',
    's' => '@portal/search/index',
];
