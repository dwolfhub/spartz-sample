# spartz-sample

## If I had more time

- Write tests for query repositories using in-memory database
- Investigate speed increase from spatial indexes on latitude/longitude queries
- Consider different caching practices to account for pagination (perhaps cache the entire result?)

## Database Structure

```SQL
CREATE TABLE `cities` (
   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
   `state` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
   `status` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
   `latitude` double(9,6) NOT NULL,
   `longitude` double(9,6) NOT NULL,
   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
   PRIMARY KEY (`id`),
   KEY `cities_state_index` (`state`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `users` (
   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `first_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
   `last_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
   `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
   `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
   PRIMARY KEY (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `users_cities` (
   `user_id` int(10) unsigned NOT NULL,
   `city_id` int(10) unsigned NOT NULL,
   KEY `users_cities_user_id_index` (`user_id`),
   KEY `users_cities_city_id_index` (`city_id`),
   CONSTRAINT `users_cities_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
   CONSTRAINT `users_cities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```
