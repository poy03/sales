

CREATE TABLE `app_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(50) NOT NULL,
  `type_payment` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `contact_number` varchar(100) NOT NULL,
  `app_company_name` varchar(100) NOT NULL,
  `maximum_items_displayed` float NOT NULL,
  `logo` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


INSERT INTO app_config VALUES
("1","POS","CASH,CREDIT,BANK","padre gromez st. davao city","9074688265","JB","50","");




CREATE TABLE `log_items` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `itemID` int(11) NOT NULL,
  `item_detail_id` int(11) NOT NULL,
  `descripion` text COLLATE utf8_unicode_ci NOT NULL,
  `date` int(11) NOT NULL,
  `date_time` int(11) NOT NULL,
  `accountID` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE `tbl_cart` (
  `cartID` int(255) NOT NULL AUTO_INCREMENT,
  `itemID` int(255) NOT NULL,
  `quantity` double NOT NULL,
  `price` double NOT NULL,
  `accountID` int(11) NOT NULL,
  `Customer` varchar(100) NOT NULL,
  `type_payment` varchar(100) NOT NULL,
  `customerID` int(255) NOT NULL,
  `comments` text NOT NULL,
  `type_price` varchar(10) NOT NULL,
  `terms` float NOT NULL,
  `item_detail_id` int(11) NOT NULL,
  PRIMARY KEY (`cartID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;






CREATE TABLE `tbl_cart_expenses` (
  `cartID` int(255) NOT NULL AUTO_INCREMENT,
  `description` varchar(150) NOT NULL,
  `expenses` double NOT NULL,
  `accountID` int(255) NOT NULL,
  `comments` text NOT NULL,
  `serial_number` text NOT NULL,
  PRIMARY KEY (`cartID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;






CREATE TABLE `tbl_cart_receiving` (
  `cartID` int(255) NOT NULL AUTO_INCREMENT,
  `itemID` int(255) NOT NULL,
  `quantity` float NOT NULL,
  `accountID` int(255) NOT NULL,
  `supplierID` int(255) NOT NULL,
  `mode` varchar(50) NOT NULL,
  `costprice` double NOT NULL,
  `subtotal` double NOT NULL,
  `comments` text NOT NULL,
  `serial_number` text NOT NULL,
  `purchase_order` text NOT NULL,
  PRIMARY KEY (`cartID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;






CREATE TABLE `tbl_credits` (
  `creditID` int(255) NOT NULL AUTO_INCREMENT,
  `accountID` int(255) NOT NULL,
  `customerID` int(255) NOT NULL,
  `amount` double NOT NULL,
  `date` varchar(50) NOT NULL,
  `payment` double NOT NULL,
  `paymentID` varchar(255) NOT NULL,
  `type_payment` varchar(150) NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`creditID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;






CREATE TABLE `tbl_customer` (
  `customerID` int(255) NOT NULL AUTO_INCREMENT,
  `companyname` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `contactperson` varchar(50) NOT NULL,
  `deleted` int(11) NOT NULL,
  `x2` varchar(11) NOT NULL,
  `x3` varchar(11) NOT NULL,
  PRIMARY KEY (`customerID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


INSERT INTO tbl_customer VALUES
("1","jbtech\' marketing&quot; &amp;","padre gromez \' st. &amp; davao city&quot; a","na&quot;l\'a&amp;mritchie@gmail.com","09\'0&quot;7&amp;4688265","j&quot;e\'n&amp;o","0","","");




CREATE TABLE `tbl_expenses` (
  `exID` int(255) NOT NULL AUTO_INCREMENT,
  `description` varchar(150) NOT NULL,
  `expenses` float NOT NULL,
  `accountID` int(255) NOT NULL,
  `orderID` int(255) NOT NULL,
  `x1` int(11) NOT NULL,
  `x2` int(11) NOT NULL,
  PRIMARY KEY (`exID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;






CREATE TABLE `tbl_items` (
  `itemID` int(255) NOT NULL AUTO_INCREMENT,
  `itemname` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `costprice` float NOT NULL,
  `srp` double NOT NULL,
  `dp` float NOT NULL,
  `quantity` float NOT NULL,
  `comment` text NOT NULL,
  `deleted` int(11) NOT NULL,
  `reorder` int(11) NOT NULL,
  `has_serial` int(11) NOT NULL,
  PRIMARY KEY (`itemID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


INSERT INTO tbl_items VALUES
("1","Evolve mouse\'s","Computer\'s &amp; asdasd","301","257","251","0","","0","2","1"),
("2","cssdd\'asss","Computer\'s &amp; asdasd","2233","1211","2331","0","","0","222","0");




CREATE TABLE `tbl_items_detail` (
  `item_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `itemID` int(11) NOT NULL,
  `serial_number` text COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `deleted` int(11) NOT NULL,
  `has_serial` int(11) NOT NULL,
  PRIMARY KEY (`item_detail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO tbl_items_detail VALUES
("1","1","96586323","0","0","1"),
("2","2","","230","0","0"),
("3","1","111\'12233&amp;","0","0","1"),
("4","1","1233&quot;aaa\'aa&amp;","0","0","1"),
("5","1","215213","0","0","1");




CREATE TABLE `tbl_items_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `item_detail_id` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `serial_number` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `date_time` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `referenceID` int(11) NOT NULL,
  `reference_number` text COLLATE utf8_unicode_ci NOT NULL,
  `accountID` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO tbl_items_history VALUES
("1","Purchase","1","1","96586323","","1483718400","1","1","","1"),
("2","Purchase","3","1","111\'12233&amp;","","1483718400","1","2","134235\'aa&amp;","1"),
("3","Purchase","4","1","1233&quot;aaa\'aa&amp;","","1483718400","1","2","134235\'aa&amp;","1"),
("4","Purchase","2","2","","","1483718400","233","3","","1"),
("5","Sales","2","2","","","1483718400","1","2","","1"),
("6","Sales","2","2","","","1483718400","1","3","","1"),
("7","Purchase","5","1","215213","","1483718400","1","4","","1"),
("8","Sales","5","1","215213","","1483718400","1","4","","1"),
("9","Sales","1","1","96586323","","1483718400","1","5","","1"),
("10","Sales Delete","1","1","96586323","joke","1483718400","1","5","","1"),
("11","Sales","1","1","96586323","","1483718400","1","6","","1"),
("12","Sales Delete","2","2","","joke","1483718400","1","2","","1"),
("13","Sales","2","2","","","1483718400","1","7","","1"),
("14","Sales","2","2","","","1483891200","1","8","","1");




CREATE TABLE `tbl_orders` (
  `orderID` int(255) NOT NULL AUTO_INCREMENT,
  `date_ordered` int(11) NOT NULL,
  `time_ordered` varchar(50) NOT NULL,
  `accountID` int(255) NOT NULL,
  `total` double NOT NULL,
  `type_payment` varchar(20) NOT NULL,
  `customer` varchar(100) NOT NULL,
  `payment` double NOT NULL,
  `profits` double NOT NULL,
  `loss` double NOT NULL,
  `balance` double NOT NULL,
  `costprice` double NOT NULL,
  `comments` text NOT NULL,
  `deleted` int(10) NOT NULL,
  `date_due` varchar(20) NOT NULL,
  `customerID` int(255) NOT NULL,
  PRIMARY KEY (`orderID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;


INSERT INTO tbl_orders VALUES
("1","1483718400","12:02:52 PM","1","502","","jbtech\' marketing&quot; &amp; asdasd","0","0","100","502","602","Deleted by: ADMIN (01/07/2017 - 04:04:06 AM)<br>Reason: 123123123","1","1486310400","0"),
("2","1483718400","12:05:52 PM","1","1211","","jbtech\' marketing&quot; &amp; ssss","0","0","1022","1211","2233","Deleted by: ADMIN (01/07/2017 - 08:05:15 AM)<br>Reason: joke","1","1486310400","0"),
("3","1483718400","12:06:15 PM","1","1211","","jbtech\' marketing&quot; &amp;","0","0","1022","1211","2233","","0","1486310400","1"),
("4","1483718400","03:49:34 PM","1","251","","jbtech\' marketing&quot; &amp;","0","0","50","251","301","","0","1486310400","1"),
("5","1483718400","03:55:22 PM","1","257","","jbtech\' marketing&quot; &amp;","0","0","44","257","301","Deleted by: ADMIN (01/07/2017 - 07:58:55 AM)<br>Reason: joke","1","1486310400","1"),
("6","1483718400","04:04:44 PM","1","257","","jbtech\' marketing&quot; &amp;","0","0","44","257","301","","0","1486310400","0"),
("7","1483718400","04:05:57 PM","1","1211","","jbtech\' marketing&quot; &amp;","0","0","1022","1211","2233","","0","1486310400","1"),
("8","1483891200","01:18:13 PM","1","1211","","jbtech\' marketing&quot; &amp;","0","0","1022","1211","2233","","0","1486483200","1");




CREATE TABLE `tbl_orders_expenses` (
  `orderID` int(255) NOT NULL AUTO_INCREMENT,
  `date_of_expense` int(11) NOT NULL,
  `time_expended` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `expenses` double NOT NULL,
  `comments` text NOT NULL,
  `accountID` int(255) NOT NULL,
  `deleted` int(11) NOT NULL,
  PRIMARY KEY (`orderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;






CREATE TABLE `tbl_orders_receiving` (
  `orderID` int(255) NOT NULL AUTO_INCREMENT,
  `total_cost` float NOT NULL,
  `date_received` int(11) NOT NULL,
  `time_received` varchar(20) NOT NULL,
  `accountID` int(255) NOT NULL,
  `comments` text NOT NULL,
  `supplierID` int(255) NOT NULL,
  `mode` varchar(20) NOT NULL,
  `deleted` int(11) NOT NULL,
  PRIMARY KEY (`orderID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


INSERT INTO tbl_orders_receiving VALUES
("1","300","1483718400","02:02:38 AM","1","","1","","0"),
("2","602","1483718400","03:23:45 AM","1","","0","","0"),
("3","520289","1483718400","03:29:51 AM","1","","0","","0"),
("4","301","1483718400","07:21:49 AM","1","","0","","0");




CREATE TABLE `tbl_payments` (
  `paymentID` int(255) NOT NULL AUTO_INCREMENT,
  `type_payment` varchar(100) NOT NULL,
  `payment` double NOT NULL,
  `accountID` int(255) NOT NULL,
  `orderID` int(255) NOT NULL,
  `deleted` int(11) NOT NULL,
  `comments` text NOT NULL,
  `date` int(20) NOT NULL,
  `time` varchar(20) NOT NULL,
  `date_due` int(11) NOT NULL,
  `customerID` int(255) NOT NULL,
  `cash_change` float NOT NULL,
  `credit_status` int(11) NOT NULL,
  PRIMARY KEY (`paymentID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;


INSERT INTO tbl_payments VALUES
("1","CASH","0","1","1","1","","1483718400","12:02:52 PM","1486310400","0","0","0"),
("2","CASH","0","1","2","1","","1483718400","12:05:52 PM","1486310400","0","0","0"),
("3","CASH","0","1","3","0","","1483718400","12:06:15 PM","1486310400","1","0","0"),
("4","CASH","0","1","4","0","","1483718400","03:49:34 PM","1486310400","1","0","0"),
("5","CASH","0","1","5","1","","1483718400","03:55:22 PM","1486310400","1","0","0"),
("6","BANK","0","1","6","0","","1483718400","04:04:44 PM","1486310400","0","0","0"),
("7","BANK","0","1","7","0","","1483718400","04:05:57 PM","1486310400","1","0","0"),
("8","CASH","0","1","8","0","","1483891200","01:18:13 PM","1486483200","1","0","0");




CREATE TABLE `tbl_purchases` (
  `purchaseID` int(255) NOT NULL AUTO_INCREMENT,
  `itemID` int(255) NOT NULL,
  `quantity` float NOT NULL,
  `price` float NOT NULL,
  `subtotal` float NOT NULL,
  `accountID` int(255) NOT NULL,
  `profit` float NOT NULL,
  `loss` float NOT NULL,
  `orderID` int(255) NOT NULL,
  `state` int(11) NOT NULL,
  `costprice` float NOT NULL,
  `customerID` int(255) NOT NULL,
  `date_ordered` varchar(20) NOT NULL,
  `serial_number` text NOT NULL,
  `date_ordered_int` int(11) NOT NULL,
  `item_detail_id` int(11) NOT NULL,
  `deleted` int(11) NOT NULL,
  PRIMARY KEY (`purchaseID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


INSERT INTO tbl_purchases VALUES
("1","2","1","1211","1211","1","0","1022","2","1","2233","0","01/07/2017","","1483718400","2","0"),
("2","2","1","1211","1211","1","0","1022","3","1","2233","1","01/07/2017","","1483718400","2","0"),
("3","1","1","251","251","1","0","50","4","1","301","1","01/07/2017","215213","1483718400","5","0"),
("4","1","1","257","257","1","0","44","5","1","301","1","01/07/2017","96586323","1483718400","1","0"),
("5","1","1","257","257","1","0","44","6","1","301","0","01/07/2017","96586323","1483718400","1","0"),
("6","2","1","1211","1211","1","0","1022","7","1","2233","1","01/07/2017","","1483718400","2","0"),
("7","2","1","1211","1211","1","0","1022","8","1","2233","1","01/09/2017","","1483891200","2","0");




CREATE TABLE `tbl_receiving` (
  `receiveID` int(255) NOT NULL AUTO_INCREMENT,
  `itemID` int(255) NOT NULL,
  `quantity` float NOT NULL,
  `costprice` float NOT NULL,
  `total_cost` float NOT NULL,
  `accountID` int(255) NOT NULL,
  `orderID` int(255) NOT NULL,
  `date_received` int(11) NOT NULL,
  `serial_number` text NOT NULL,
  PRIMARY KEY (`receiveID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;


INSERT INTO tbl_receiving VALUES
("1","1","1","300","300","1","1","1483718400","96586323"),
("2","3","1","301","301","1","2","1483718400","111\'12233&amp;"),
("3","4","1","301","301","1","2","1483718400","1233&quot;aaa\'aa&amp;"),
("4","2","233","2233","520289","1","3","1483718400",""),
("5","5","1","301","301","1","4","1483718400","215213");




CREATE TABLE `tbl_soa` (
  `soaID` int(255) NOT NULL AUTO_INCREMENT,
  `creditID` int(255) NOT NULL,
  `accountID` int(255) NOT NULL,
  `deleted` int(11) NOT NULL,
  `date` varchar(20) NOT NULL,
  `time` varchar(20) NOT NULL,
  `orderID` varchar(255) NOT NULL,
  `customerID` int(11) NOT NULL,
  `total` double NOT NULL,
  `paid` int(11) NOT NULL,
  PRIMARY KEY (`soaID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;






CREATE TABLE `tbl_suppliers` (
  `supplierID` int(255) NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(100) NOT NULL,
  `supplier_company` varchar(100) NOT NULL,
  `supplier_number` varchar(100) NOT NULL,
  `supplier_address` varchar(100) NOT NULL,
  `deleted` int(20) NOT NULL,
  `x2` int(20) NOT NULL,
  `x3` int(20) NOT NULL,
  PRIMARY KEY (`supplierID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


INSERT INTO tbl_suppliers VALUES
("1","Jason\' hhb&quot; ll&amp;","JJ&quot; co\'mpa&amp;ny","092074688525","pa\'dr:e &quot;gro&amp;mez st. davao city","0","0","0");




CREATE TABLE `tbl_users` (
  `accountID` int(255) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `type` varchar(10) NOT NULL,
  `employee_name` varchar(50) NOT NULL,
  `themes` varchar(50) NOT NULL,
  `deleted` int(10) NOT NULL,
  `items` int(11) NOT NULL,
  `customers` int(11) NOT NULL,
  `sales` int(11) NOT NULL,
  `receiving` int(11) NOT NULL,
  `users` int(11) NOT NULL,
  `reports` int(11) NOT NULL,
  `suppliers` int(11) NOT NULL,
  `credits` int(11) NOT NULL,
  `expenses` int(11) NOT NULL,
  PRIMARY KEY (`accountID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


INSERT INTO tbl_users VALUES
("1","jean","21523222c65d70ac44ac82905ff84561","admin","ADMIN","bootstrap.min.css","0","1","1","1","1","1","1","1","1","1");


