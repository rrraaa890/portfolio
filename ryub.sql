
-- --------------------------------------------------------

--
-- テーブルの構造 `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(100) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `news` text NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- テーブルのデータをダンプしています `news`
--
 --------------------------------------------------------

--
-- テーブルの構造 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- テーブルのデータをダンプしています `user`
--
