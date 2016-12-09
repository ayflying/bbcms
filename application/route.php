<?php
return [
    'aid/:aid' => '@portal/Article/index',
	'tid/[:tid]' => '@portal/Lists/index',
	'add/:tid' => '@portal/post/add',
	'edit/:aid' => '@portal/post/edit',
    'ss/:search' => '@portal/Search/index',
];
