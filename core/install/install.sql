DROP TABLE IF EXISTS yu_article_comment;
DROP TABLE IF EXISTS yu_article;
DROP TABLE IF EXISTS yu_user_group;
DROP TABLE IF EXISTS yu_group;
DROP TABLE IF EXISTS yu_user;

CREATE TABLE yu_group (
	ID INT NOT NULL AUTO_INCREMENT,
	UPDATED DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CODE VARCHAR(255) NOT NULL,
	NAME VARCHAR(255) NOT NULL,
	PRIMARY KEY (ID),
	UNIQUE UIX_CODE (CODE)
);
INSERT INTO yu_group (ID, CODE, `NAME`) VALUES
	(1, 'admin', 'Administrator'),
	(2, 'user', 'Regular user'),
	(3, 'editor', 'Editor')
;
CREATE TABLE yu_user (
	ID INT NOT NULL AUTO_INCREMENT,
	CREATED TIMESTAMP DEFAULT NOW(),
	UPDATED TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
	LOGIN VARCHAR(50) NOT NULL,
	`PASSWORD` VARCHAR(255),
	NAME VARCHAR(50) NOT NULL ,
	EMAIL VARCHAR(255),
	PRIMARY KEY (ID),
	UNIQUE UIX_LOGIN (LOGIN),
	INDEX UIX_EMAIL (EMAIL)
);
INSERT INTO yu_user (ID, LOGIN, `NAME`, EMAIL) VALUES
	(1, 'admin', 'Administrator', 'admin@example.com'),
	(2, 'user1', 'Regular user 1', 'user1@example.com'),
	(3, 'user2', 'Regular user 2', 'user2@example.com')
;

CREATE TABLE yu_user_group (
	USER_ID INT not null,
	GROUP_ID INT not null,
	UNIQUE IX_USER_GROUP (USER_ID, GROUP_ID),
	INDEX IX_USER_GROUP_GROUP (GROUP_ID),
	FOREIGN KEY (GROUP_ID) REFERENCES yu_group (ID) ON DELETE CASCADE,
	FOREIGN KEY (USER_ID) REFERENCES yu_user (ID) ON DELETE CASCADE
);
INSERT INTO yu_user_group (USER_ID, GROUP_ID) VALUES
	(1, 1),
	(1, 2),
	(2, 1),
	(3, 1)
;

CREATE TABLE yu_article (
	ID INT NOT NULL AUTO_INCREMENT,
	TITLE VARCHAR(255),
	BODY TEXT,
	AUTHOR_ID INT NULL,
	AUTHOR_NAME VARCHAR(255) NOT NULL,
	CREATED TIMESTAMP DEFAULT NOW(),
	UPDATED TIMESTAMP NOT NULL ON UPDATE NOW() DEFAULT NOW(),
	PRIMARY KEY (ID),
	FOREIGN KEY (AUTHOR_ID) REFERENCES yu_user(ID) ON DELETE SET NULL
);
CREATE TABLE yu_article_comment (
	ID INT NOT NULL AUTO_INCREMENT,
	ARTICLE_ID INT NOT NULL,
	BODY TEXT NOT NULL,
	AUTHOR_ID INT NULL,
	AUTHOR_NAME VARCHAR(255) NOT NULL,
	CREATED TIMESTAMP DEFAULT NOW(),
	UPDATED TIMESTAMP NOT NULL ON UPDATE NOW(),
	PRIMARY KEY (ID),
	FOREIGN KEY (AUTHOR_ID) REFERENCES yu_user(ID) ON DELETE SET NULL,
	FOREIGN KEY (ARTICLE_ID) REFERENCES yu_user(ID) ON DELETE CASCADE,
	INDEX IX_ARTICLE_ID (ARTICLE_ID)
);
INSERT INTO yu_article (TITLE, BODY, AUTHOR_ID, AUTHOR_NAME) VALUES
	('A Christmas Tale\<img src=\"\" onerror=\"alert(1)\">', 'Two little children lived with their old grandmother in a remote place in the Canadian forest. ', 1, '<h1>Administrator</h1>'),
	('A Christmas Tale\<img src=\"\" onerror=\"alert(1)\">', 'They were twin children—a boy and a girl, Pierre and Estelle by name—and except for their dress it ', 1, '<h1>Administrator</h1>'),
	('A Christmas Tale\<img src=\"\" onerror=\"alert(1)\">', 'was not easy to tell them apart. Their father and mother had died in the spring-time, and in the summer ', 1, '<h1>Administrator</h1>'),
	('A Christmas Tale\<img src=\"\" onerror=\"alert(1)\">', 'they had left their old home because of its many sad memories and had gone to live with their old grandmother in a new home elsewhere. ', 1, '<h1>Administrator</h1>'),
	('A Christmas Tale\<img src=\"\" onerror=\"alert(1)\">', 'In this new home in the forest where they now lived they were very poor, but they were not unhappy. ', 1, '<h1>Administrator</h1>'),
	('A Christmas Tale\<img src=\"\" onerror=\"alert(1)\">', 'but they caught fish in the streams and gathered berries and fruit and birds’ eggs on the wooded hills, and somehow throughout the summer they kept themselves from want. But when late autumn came and the streams were frozen over and the berries were all gone and there were no eggs, for the birds had all flown south, they were often hungry because they had so little to eat. Their grandmother worked so hard to provide for herself and the children that at last she fell very sick. For several days she could not leave her bed. And she said ', 1, '<h1>Administrator</h1>'),
	('A Christmas Tale\<img src=\"\" onerror=\"alert(1)\">', '“I want meat broth to make me well and I must have good meat to make it. If I do not get meat I can have no broth, and if I do not get broth I shall not get well, and if I do not get well I shall die, and if I die you two children will surely starve and die too. So meat and meat alone can save us all from starvation and death.” ', 1, '<h1>Administrator</h1>'),
	('<h1>A Christmas Tale2</h1>', 'So the two children, to keep themselves and their grandmother alive, set out one morning in search of meat to make the broth. They lived far from other people and they did not know where to go, but they followed the forest path. The snow lay deep on the ground and sparkled brightly in the sunlight. The children had never before been away from home alone and every sight was of great interest to them. Here and there a rabbit hopped over the snow, or a snowbird hovered and twittered overhead, all looking for food like the children. And there were holly-berries growing in many places, and there was mistletoe hanging from the trees.', 1, 'Administrator'),
	('A Christmas Tale3', 'And Pierre when he saw the holly-berries and the mistletoe said, “Saint Nicholas will be soon here, for the trees are dressed and ready for his coming.” And Estelle said, “Yes, Saint Nicholas will be soon here.” And they were both very glad thinking of his coming.

As they went along in the afternoon, they came upon an old man sitting at the door of a small house of spruce-boughs under the trees close to the forest path. He was busy making whistles, whittling willow wands with a knife and tapping gently on the bark until the bark loosened from the wood and slipped easily off. The children stood and watched him at his strange work, for he had merry twinkling eyes, and a kindly weather-beaten face, and thick white hair, and they were not afraid.said the old man.', 1, 'Administrator')
;
