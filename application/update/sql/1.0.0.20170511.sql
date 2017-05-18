ALTER TABLE bb_portal_menu add type int(11) NOT NULL after tid;
ALTER TABLE bb_portal_menu CHANGE template template_article char(50);
ALTER TABLE bb_portal_menu CHANGE template2 template_list char(50);
ALTER TABLE bb_portal_menu add template_add char(50) CHARACTER SET utf8 COLLATE utf8_general_ci after template_list;
ALTER TABLE bb_portal_menu add template_edit char(50) CHARACTER SET utf8 COLLATE utf8_general_ci after template_add;
UPDATE bb_portal_menu SET template_article = './portal/article';
UPDATE bb_portal_menu SET template_list = './portal/list';
UPDATE bb_portal_menu SET template_add = './post/add';
UPDATE bb_portal_menu SET template_edit = './post/edit';