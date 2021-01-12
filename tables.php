
/*
* item_subcategories
*/

SELECT `id`, `category_id`, `name`, `description`, `image` FROM `item_subcategories` WHERE 1
--------------------------------------
/*
* suppliers
*/

SELECT `id`, `name`, `address`, `email`, `phone` FROM `suppliers` WHERE 1
--------------------------------------

/*
* purchase
*/

SELECT `id`, `supplier_id`, `date` FROM `purchase` WHERE 1
--------------------------------------


/*
* purchase_items
*/

SELECT `id`, `purchase_id`, `item_id`, `purchase_rate`, `quantity` FROM `purchase_items` WHERE 1
--------------------------------------









