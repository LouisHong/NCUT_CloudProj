CREATE EVENT `delete_vistor_cart_info` 
ON SCHEDULE EVERY 10 MINUTE STARTS '2019-05-01 12:00:00' 
ON COMPLETION PRESERVE ENABLE 
DO 
DELETE FROM `cart_info` WHERE `token` IN(SELECT `token` FROM `carts` 
WHERE `users` = "A00000" AND TIMESTAMPDIFF(HOUR,timestamp,CURRENT_TIMESTAMP) > 3);

CREATE EVENT `delete_vistor_carts` 
ON SCHEDULE EVERY 20 MINUTE STARTS '2019-05-01 12:00:00' 
ON COMPLETION PRESERVE ENABLE 
DO 
DELETE FROM `carts` 
WHERE `users` = "A00000" AND TIMESTAMPDIFF(HOUR,timestamp,CURRENT_TIMESTAMP) > 3;