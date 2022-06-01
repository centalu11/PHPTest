1) run composer update or composer install
2) Change env variables according to own
3) import sql/phptest.sql file or manually create database

**If chosen to manually create database run the following in mysql**

```
CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `channelUsername` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `publicationDate` varchar(255) NOT NULL,
  `viewCount` int(11) NOT NULL,
  `likeCount` int(11) NOT NULL,
  `favoriteCount` int(11) NOT NULL,
  `commentCount` int(11) NOT NULL,
  `tags` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
```