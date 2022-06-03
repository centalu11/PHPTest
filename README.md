1) run composer update or composer install
2) Change env variables according to own
3) import sql/phptest.sql file or manually create database
4) To run in CLI (e.g) php <folder-path>/index.php https://www.youtube.com/c/SkillsWithPhil EAAaBlBUOkNESQ 
   - first argument is required (url of the youtube channel)
   - second argument is optional (pageToken for pagination)

5) To run in browser (e.g) php <base-url>/index.php?url=https://www.youtube.com/c/SkillsWithPhil&page=EAAaBlBUOkNESQ
   - first parameter "url" is required (url of the youtube channel)
   - second parameter "page" is optional (pageToken for pagination)
  
**If chosen to manually create database run the following in mysql**

```
CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `videoId` varchar(255) NOT NULL,
  `channelId` varchar(255) NOT NULL,
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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique-video-id` (`videoId`);

ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
```