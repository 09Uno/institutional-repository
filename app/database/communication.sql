CREATE TABLE system_message
(
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id INT,
    system_user_to_id INT,
    subject TEXT,
    message TEXT,
    dt_message TEXT,
    checked char(1)
);

CREATE TABLE system_notification
(
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id INT,
    system_user_to_id INT,
    subject TEXT,
    message TEXT,
    dt_message TEXT,
    action_url TEXT,
    action_label TEXT,
    icon TEXT,
    checked char(1)
);

CREATE TABLE system_document_category
(
    id INTEGER PRIMARY KEY NOT NULL,
    name TEXT
);
INSERT INTO system_document_category VALUES(1,'Documentação');

CREATE TABLE system_folder (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    created_at date,
    name text not null,
    in_trash char(1),
    system_folder_parent_id int REFERENCES system_folder (id)
);

CREATE TABLE system_folder_user
(
    id INTEGER PRIMARY KEY NOT NULL,
    system_folder_id INTEGER references system_folder(id),
    system_user_id INTEGER
);

CREATE TABLE system_folder_group
(
    id INTEGER PRIMARY KEY NOT NULL,
    system_folder_id INTEGER references system_folder(id),
    system_group_id INTEGER
);

CREATE TABLE system_document
(
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id INTEGER,
    title TEXT,
    description TEXT,
    category_id INTEGER references system_document_category(id),
    submission_date DATE,
    archive_date DATE,
    filename TEXT,
    in_trash char(1),
    system_folder_id INTEGER references system_folder(id)
);

CREATE TABLE system_document_user
(
    id INTEGER PRIMARY KEY NOT NULL,
    document_id INTEGER references system_document(id),
    system_user_id INTEGER
);

CREATE TABLE system_document_group
(
    id INTEGER PRIMARY KEY NOT NULL,
    document_id INTEGER references system_document(id),
    system_group_id INTEGER
);

CREATE TABLE system_document_bookmark (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    system_document_id INTEGER references system_document(id)
);

CREATE TABLE system_folder_bookmark (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    system_folder_id INTEGER references system_folder(id)
);

CREATE TABLE system_post (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    title TEXT not NULL,
    content TEXT not NULL,
    created_at timestamp not null,
    active char(1) default 'Y' not null
);

CREATE TABLE system_post_share_group (
    id INTEGER PRIMARY KEY NOT NULL,
    system_group_id int,
    system_post_id int REFERENCES system_post (id) 
);

CREATE TABLE system_post_tag (
    id INTEGER PRIMARY KEY NOT NULL,
    system_post_id int REFERENCES system_post (id) ,
    tag text not null
);

CREATE TABLE system_post_comment (
    id INTEGER PRIMARY KEY NOT NULL,
    comment TEXT not NULL,
    system_user_id int not null,
    system_post_id int REFERENCES system_post (id) ,
    created_at timestamp not null
);

CREATE TABLE system_post_like (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    system_post_id int REFERENCES system_post (id) ,
    created_at timestamp not null
);

CREATE TABLE system_wiki_page (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    created_at timestamp not null,
    updated_at timestamp,
    title TEXT not null,
    description TEXT not null,
    content TEXT not null,
    active char(1) default 'Y' not null,
    searchable char(1) default 'Y' not null
);

CREATE TABLE system_wiki_tag (
    id INTEGER PRIMARY KEY NOT NULL,
    system_wiki_page_id int REFERENCES system_wiki_page (id) ,
    tag text not null
);

CREATE TABLE system_wiki_share_group (
    id INTEGER PRIMARY KEY NOT NULL,
    system_group_id int,
    system_wiki_page_id int REFERENCES system_wiki_page (id) 
);



INSERT INTO system_post_share_group VALUES(1,1,1);
INSERT INTO system_post_share_group VALUES(2,2,1);
INSERT INTO system_post_share_group VALUES(3,1,2);
INSERT INTO system_post_share_group VALUES(4,2,2);

INSERT INTO system_post_tag VALUES(1,1,'novidades');
INSERT INTO system_post_tag VALUES(2,2,'novidades');

INSERT INTO system_post_comment VALUES(1,'My first comment',1,2,'2022-11-03 15:22:11');
INSERT INTO system_post_comment VALUES(2,'Another comment',1,2,'2022-11-03 15:22:17');
INSERT INTO system_post_comment VALUES(3,'The best comment',2,2,'2022-11-03 15:23:11');

INSERT INTO system_wiki_page VALUES(1,1,'2022-11-02 15:33:58','2022-11-02 15:35:10','Manual de operações','Este manual explica os procedimentos básicos de operação','<p style="text-align: justify; "><span style="font-size: 18px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Sapien nec sagittis aliquam malesuada bibendum arcu vitae. Quisque egestas diam in arcu cursus euismod quis. Risus nec feugiat in fermentum posuere urna nec tincidunt praesent. At imperdiet dui accumsan sit amet. Est pellentesque elit ullamcorper dignissim cras tincidunt lobortis. Elementum facilisis leo vel fringilla est ullamcorper. Id porta nibh venenatis cras. Viverra orci sagittis eu volutpat odio facilisis mauris sit. Senectus et netus et malesuada fames ac turpis. Sociis natoque penatibus et magnis dis parturient montes. Vel turpis nunc eget lorem dolor sed viverra ipsum nunc. Sed viverra tellus in hac habitasse. Tellus id interdum velit laoreet id donec ultrices tincidunt arcu. Pharetra et ultrices neque ornare aenean euismod elementum. Volutpat blandit aliquam etiam erat velit scelerisque in. Neque aliquam vestibulum morbi blandit cursus risus. Id consectetur purus ut faucibus pulvinar elementum.</span></p><p style="text-align: justify; "><br></p>','Y','Y');
INSERT INTO system_wiki_page VALUES(2,1,'2022-11-02 15:35:04','2022-11-02 15:37:49','Instruções de lançamento','Este manual explica as instruções de lançamento de produto','<p><span style="font-size: 18px;">Non curabitur gravida arcu ac tortor dignissim convallis. Nunc scelerisque viverra mauris in aliquam sem fringilla ut morbi. Nunc eget lorem dolor sed viverra. Et odio pellentesque diam volutpat commodo sed egestas. Enim lobortis scelerisque fermentum dui faucibus in ornare quam viverra. Faucibus et molestie ac feugiat. Erat velit scelerisque in dictum non consectetur a erat nam. Quis risus sed vulputate odio ut enim blandit volutpat. Pharetra vel turpis nunc eget lorem dolor sed viverra. Nisl tincidunt eget nullam non nisi est sit. Orci phasellus egestas tellus rutrum tellus pellentesque eu. Et tortor at risus viverra adipiscing at in tellus integer. Risus ultricies tristique nulla aliquet enim. Ac felis donec et odio pellentesque diam volutpat commodo sed. Ut morbi tincidunt augue interdum. Morbi tempus iaculis urna id volutpat.</span></p><p><a href="index.php?class=SystemWikiView&amp;method=onLoad&amp;key=3" generator="adianti">Sub página de instruções 1</a></p><p><a href="index.php?class=SystemWikiView&amp;method=onLoad&amp;key=4" generator="adianti">Sub página de instruções 2</a><br><span style="font-size: 18px;"><br></span><br></p>','Y','Y');
INSERT INTO system_wiki_page VALUES(3,1,'2022-11-02 15:36:59','2022-11-02 15:37:21','Instruções - sub página 1','Instruções - sub página 1','<p><span style="font-size: 18px;">Follow these steps:</span></p><ol><li><span style="font-size: 18px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span></li><li><span style="font-size: 18px;">Sapien nec sagittis aliquam malesuada bibendum arcu vitae.</span></li><li><span style="font-size: 18px;">Quisque egestas diam in arcu cursus euismod quis.</span><br></li></ol>','Y','N');
INSERT INTO system_wiki_page VALUES(4,1,'2022-11-02 15:37:17','2022-11-02 15:37:22','Instruções - sub página 2','Instruções - sub página 2','<p><span style="font-size: 18px;">Follow these steps:</span></p><ol><li><span style="font-size: 18px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span></li><li><span style="font-size: 18px;">Sapien nec sagittis aliquam malesuada bibendum arcu vitae.</span></li><li><span style="font-size: 18px;">Quisque egestas diam in arcu cursus euismod quis.</span></li></ol>','Y','N');

INSERT INTO system_wiki_tag VALUES(3,1,'manual');
INSERT INTO system_wiki_tag VALUES(5,4,'manual');
INSERT INTO system_wiki_tag VALUES(6,3,'manual');
INSERT INTO system_wiki_tag VALUES(7,2,'manual');

INSERT INTO system_wiki_share_group VALUES(1,1,1);
INSERT INTO system_wiki_share_group VALUES(2,2,1);
INSERT INTO system_wiki_share_group VALUES(3,1,2);
INSERT INTO system_wiki_share_group VALUES(4,2,2);
INSERT INTO system_wiki_share_group VALUES(5,1,3);
INSERT INTO system_wiki_share_group VALUES(6,2,3);
INSERT INTO system_wiki_share_group VALUES(7,1,4);
INSERT INTO system_wiki_share_group VALUES(8,2,4);
