
CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `un` varchar(300) NOT NULL,
  `pw` varchar(300) NOT NULL,
  `demo` enum('Y','N') NOT NULL DEFAULT 'Y',
  `lastupdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `un`, `pw`, `demo`, `lastupdate`) VALUES
(1, 'administrator', '21232f297a57a5a743894a0e4a801fc3', 'N', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `crawl`
--

CREATE TABLE `crawl` (
  `id` int(11) NOT NULL,
  `base_url` varchar(500) NOT NULL,
  `actual_url` varchar(500) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varbinary(200) DEFAULT NULL,
  `content` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `current_url` varchar(500) NOT NULL,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` enum('Y','N') DEFAULT 'N',
  `block_update` enum('Y','N') DEFAULT 'N',
  `visits` int(11) DEFAULT '0',
  `manual` enum('Y','N') DEFAULT 'N',
  `crawlRun` varchar(255) DEFAULT NULL,
  `crawlRunImages` varchar(255) DEFAULT NULL,
  `pdf` enum('Y','N') DEFAULT 'N',
  `ContentType` varchar(255) DEFAULT NULL,
  `ogimage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `crawl_images`
--

CREATE TABLE `crawl_images` (
  `id` int(11) NOT NULL,
  `base_url` varchar(500) NOT NULL,
  `actual_url` varchar(500) NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `current_url` varchar(500) NOT NULL,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` enum('Y','N') DEFAULT 'N',
  `block_update` enum('Y','N') DEFAULT 'N',
  `visits` int(11) DEFAULT '0',
  `manual` enum('Y','N') DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `crawl_settings`
--

CREATE TABLE `crawl_settings` (
  `id` int(11) NOT NULL,
  `batch` int(11) NOT NULL DEFAULT '25',
  `image_width` int(11) NOT NULL DEFAULT '200',
  `image_height` int(11) NOT NULL DEFAULT '200',
  `body_lengh` int(11) NOT NULL DEFAULT '2000',
  `max_links_per_site` int(11) DEFAULT '20',
  `ogimage` enum('Y','N') DEFAULT 'Y',
  `crawl_images_image_url` enum('Y','N') NOT NULL DEFAULT 'Y',
  `crawl_images_current_url` enum('Y','N') NOT NULL DEFAULT 'Y',
  `crawl_images_keywords` enum('Y','N') NOT NULL DEFAULT 'Y',
  `crawl_title` enum('Y','N') NOT NULL DEFAULT 'Y',
  `crawl_description` enum('Y','N') NOT NULL DEFAULT 'Y',
  `crawl_current_url` enum('Y','N') NOT NULL DEFAULT 'Y',
  `crawl_keywords` enum('Y','N') NOT NULL DEFAULT 'Y',
  `crawl_content` enum('Y','N') NOT NULL DEFAULT 'Y',
  `search_results_per_page` int(11) NOT NULL DEFAULT '10',
  `crawl_interval_between_links` int(11) NOT NULL DEFAULT '2000'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `crawl_settings`
--

INSERT INTO `crawl_settings` (`id`, `batch`, `image_width`, `image_height`, `body_lengh`, `max_links_per_site`, `ogimage`, `crawl_images_image_url`, `crawl_images_current_url`, `crawl_images_keywords`, `crawl_title`, `crawl_description`, `crawl_current_url`, `crawl_keywords`, `crawl_content`, `search_results_per_page`, `crawl_interval_between_links`) VALUES
(1, 20, 200, 200, 500, 20, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 10, 200);

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `results` int(11) NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `count` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `base_url` varchar(500) NOT NULL,
  `actual_url` varchar(500) NOT NULL,
  `spidered_url` varchar(500) DEFAULT 'N/A',
  `visits` int(11) DEFAULT '0',
  `demo` enum('Y','N') DEFAULT 'N',
  `crawlRun` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT 'administrator',
  `time` int(11) DEFAULT '1477728575',
  `spider_mode` enum('Y','N') DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `url_views`
--

CREATE TABLE `url_views` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `urlid` int(11) NOT NULL,
  `urltype` enum('Site','Image') NOT NULL DEFAULT 'Site',
  `ip` varchar(20) NOT NULL,
  `referer` varchar(2000) DEFAULT NULL,
  `referer_base` varchar(2000) DEFAULT NULL,
  `user_agent_info` text,
  `user_agent_language` varchar(255) DEFAULT NULL,
  `browser` varchar(50) DEFAULT NULL,
  `os` varchar(255) DEFAULT NULL,
  `counter` int(11) DEFAULT '1',
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crawl`
--
ALTER TABLE `crawl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`,`description`,`keywords`,`content`(255)),
  ADD KEY `actual_url` (`actual_url`(255)),
  ADD KEY `ogimage` (`ogimage`),
  ADD KEY `deleted` (`deleted`,`block_update`);

--
-- Indexes for table `crawl_images`
--
ALTER TABLE `crawl_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keywords` (`keywords`),
  ADD KEY `base_url` (`base_url`(333)),
  ADD KEY `current_url` (`current_url`(333)),
  ADD KEY `actual_url` (`actual_url`(333)),
  ADD KEY `image_url` (`image_url`(333));

--
-- Indexes for table `crawl_settings`
--
ALTER TABLE `crawl_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keyword` (`keyword`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `actual_url` (`actual_url`(255)),
  ADD KEY `base_url` (`base_url`(255)),
  ADD KEY `crawlRun` (`crawlRun`,`spider_mode`);

--
-- Indexes for table `url_views`
--
ALTER TABLE `url_views`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `crawl`
--
ALTER TABLE `crawl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `crawl_images`
--
ALTER TABLE `crawl_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `crawl_settings`
--
ALTER TABLE `crawl_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT for table `url_views`
--
ALTER TABLE `url_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;