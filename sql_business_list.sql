
CREATE TABLE `business_list` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL,
  `contact_person` varchar(50) NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `address` varchar(500) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `mobile_number` varchar(50) NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `land_mark` varchar(50) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) DEFAULT NULL,
  `country` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `keywords` varchar(1000) DEFAULT NULL,
  `base_url` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `business_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `base_url` (`base_url`(255));


ALTER TABLE `business_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
