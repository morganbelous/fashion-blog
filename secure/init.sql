-- TODO: Put ALL SQL in between `BEGIN TRANSACTION` and `COMMIT`
BEGIN TRANSACTION;

-- create tables
CREATE TABLE images (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	description TEXT NOT NULL,
	file_name TEXT NOT NULL,
  file_ext TEXT NOT NULL
);

CREATE TABLE tags (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	title TEXT NOT NULL UNIQUE
);

CREATE TABLE image_tags (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	image_id INTEGER NOT NULL,
  tag_id INTEGER NOT NULL
);


INSERT INTO images (description, file_name, file_ext) VALUES ('sweater and jeans', '1.jpg', 'jpg');
INSERT INTO images (description, file_name, file_ext) VALUES ('cheetah dress', '2.jpg', 'jpg');
INSERT INTO images (description, file_name, file_ext) VALUES ('sweater and shorts', '3.jpg', 'jpg');
INSERT INTO images (description, file_name, file_ext) VALUES ('silky dress', '4.jpg', 'jpg');
INSERT INTO images (description, file_name, file_ext) VALUES ('gray sweater and skirt', '5.jpg', 'jpg');
INSERT INTO images (description, file_name, file_ext) VALUES ('plaid pants and beige jacket', '6.jpg', 'jpg');
INSERT INTO images (description, file_name, file_ext) VALUES ('brown sweater and skirt', '7.jpg', 'jpg');
INSERT INTO images (description, file_name, file_ext) VALUES ('striped blouse and denim shorts', '8.jpg', 'jpg');
INSERT INTO images (description, file_name, file_ext) VALUES ('sweatpants', '9.jpg', 'jpg');
INSERT INTO images (description, file_name, file_ext) VALUES ('plaid midi skirt', '10.jpg', 'jpg');

INSERT INTO tags (title) VALUES ('winter');
INSERT INTO tags (title) VALUES ('spring');
INSERT INTO tags (title) VALUES ('summer');
INSERT INTO tags (title) VALUES ('fall');
INSERT INTO tags (title) VALUES ('casual');
INSERT INTO tags (title) VALUES ('comfy');
INSERT INTO tags (title) VALUES ('party');


INSERT INTO image_tags (image_id, tag_id) VALUES (1, 1);
INSERT INTO image_tags (image_id, tag_id) VALUES (1, 5);
INSERT INTO image_tags (image_id, tag_id) VALUES (1, 6);

INSERT INTO image_tags (image_id, tag_id) VALUES (2, 3);
INSERT INTO image_tags (image_id, tag_id) VALUES (2, 7);

INSERT INTO image_tags (image_id, tag_id) VALUES (3, 2);
INSERT INTO image_tags (image_id, tag_id) VALUES (3, 6);

INSERT INTO image_tags (image_id, tag_id) VALUES (4, 3);
INSERT INTO image_tags (image_id, tag_id) VALUES (4, 7);

INSERT INTO image_tags (image_id, tag_id) VALUES (5, 2);

INSERT INTO image_tags (image_id, tag_id) VALUES (6, 1);

INSERT INTO image_tags (image_id, tag_id) VALUES (7, 4);

INSERT INTO image_tags (image_id, tag_id) VALUES (8, 3);


COMMIT;
