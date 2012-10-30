CREATE TABLE IF NOT EXISTS `mod_comments` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `pid` int(11) NOT NULL default '0' COMMENT 'Page ID',
  `ip` varchar(15) NOT NULL default '',
  `date` date NOT NULL default '0000-00-00',
  `time` time NOT NULL default '00:00:00',
  `name` varchar(50) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `comments` longtext,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;




CREATE TABLE IF NOT EXISTS `tbl_page_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT 'Page ID',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `time` time NOT NULL DEFAULT '00:00:00',
  `name` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `comments` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

--
-- Dumping data for table `tbl_page_comments`
--

INSERT INTO `tbl_page_comments` (`id`, `pid`, `ip`, `date`, `time`, `name`, `title`, `comments`) VALUES
(8, 40, '69.62.168.123', '2007-12-27', '17:12:35', 'Lyconis', 'Test XSS', '<a href="http://oev.hilands.com">\r\n    <img src="http://oev.hilands.com/img/banners/banner.1.jpg" />\r\n</a>'),
(10, 40, '69.62.168.123', '2007-12-27', '19:55:41', 'Lyconis', 'BB Code Testing ALL.', '[b]bold[/b]\r\n[i]Italic[/i]\r\n[u]underline[/u]\r\n[left]left[/left]\r\n[center]center[/center]\r\n[right]right[/right]\r\n[code]code[/code]\r\n[quote]quote[/quote]\r\n[url="http://oev.hilands.com/links-oev.html"][img]http://oev.hilands.com/img/banners/banner.1.jpg[/img][/url]\r\n'),
(12, 1, '69.62.168.123', '2008-02-16', '00:14:33', 'Monsoona', 'Thank You', 'This website comes in handy!  Thank You! '),
(13, 1, '71.146.139.6', '2008-04-15', '13:36:14', 'Filthy Hippie', 'Many Thanks!', 'I use this site all the time, more up-to-date and easier than any other I''ve found. Please keep up the great work!'),
(14, 1, '217.8.209.59', '2008-04-16', '04:43:25', 'Fionbharr', 'Great site!!', 'This is really a great site!! Thanks for all the usefull information and keep it up!!\r\n\r\nGREAT JOB!'),
(15, 1, '71.146.139.6', '2008-04-27', '21:16:41', 'Monde Greene', 'Thread Readings', 'Hail! I have 5 threads of life and 2 of fate so far...I believe the skill to read them is spirit speak and it is done by double clicking the thread. The 2  threads of fate are oriented differently but have the same message, "Feelings of warmth and appreciation wash over you; you''ve gained something."    The threads of life are all different messages but some look the same, the messages are as follows:     "The sights, smells and sounds are long gone, but you can still feel them as though it just happened."... "You take a moment to reflect upon the past, and find it leads you to a solution for the present."... "Clarity leads you to an insightful thought, and you find yourself pleased with your rationale."... "You are imprisoned."... and finally, "You die."  I have no idea what they mean but if enough are read and shared around it should become clear, sooner or later.  '),
(23, 1, '71.146.143.62', '2008-05-25', '16:38:35', 'Twylyte Myste', 'TYPO! Stats, NOT Skills', 'Last post should have read ...*Stats gain until 125, if below...pertaining to Int as well as Dex, so i thought it worth mentioning...the skills seem to cap at GM, even though they are near elder tactics when wild...hope i didnt cause any confusion'),
(17, 1, '71.146.147.45', '2008-05-18', '16:48:54', 'Filthy Hippie', 'Dread War Horses!', 'I finally had a chance to tame one of these, but didn''t get to pre-lore it...one tamer said its the highest hp and the best resist total he''s seen so here goes: HP: 635, Sta: 114 and rising, Man: 124 and RISING, (yes RISING MANA! on a beefy mage pet!), Str: 511, Dex and Int matching Sta and Man like usual...\r\nResists are: 66, 34, 31, 52, 48...i''ve seen 70 phys but i still haven''t seen many so my numbers are just a glance\r\nskills are all below gm so far but 88 wrestling, 98 tactics, and 81 spell resist is good place to start!\r\nFilthy from the field, signing off;\r\nGood night, and good hunting!'),
(19, 1, '71.146.147.45', '2008-05-19', '23:18:24', 'Monde Greene', 'Thread Updates', 'Hail! The latest threads i''ve gotten say: "You find a lover.", "You protect something.", and, "You die."...doesn''t bode well perhaps, but then i''m a necro so i died a long time ago...anyway i hope this adds to the suspense, as well as the general knowledge.  Mwahahahahaha!'),
(22, 1, '71.146.143.62', '2008-05-25', '16:24:28', 'Twylyte Myste', 'Dread Warhorse Statsheet Update', 'After taming over a dozen of these and loreing about 30-35, (an admittedly small sample), i have come up with the following:\r\nHP: 573-635, Sta: 102-125*, Man:114-154*, Str,:510-545, dex and int match sta and man like always...* skills gain till 125 if below\r\nResists: 65-75, 20-37, 26-39, 50-59, 40-50, resit totals on the ones ive tamed range from 211-253.\r\nhope this helps, and thanks for the great site!'),
(24, 1, '71.146.140.195', '2008-06-21', '10:30:39', 'Twylyte Myste', 'Warhorse Wonders', 'The warhorses go as high as 650 hp, 160 mana, and resists cap at 75, 40, 40, 60, 50.  If you say, "trick" while leading one, and maybe while mounted, it will cast the same high level poison cloud it uses when wild!  I''m not sure how long you have to wait to use it again, but there is a significant delay...its been several minutes since i tried it and it still says ''you must wait before trying again''...'),
(25, 1, '71.146.140.195', '2008-06-21', '11:51:42', 'Twlyte Myste', 'Well, Maybe...', 'the ''trick'' timer seems to be 5 minutes but it resets if you try to use it before the timer expires...also doesn''t seem to have any real effect in Trammel and i''m not ready to try it in Felucca just yet...'),
(28, 11, '209.172.22.47', '2008-12-27', '22:45:58', 'Aluma Kiritan', 'Concerning Maps In Atlas', 'Is there no way to download your maps to my hard drive? It REALLY lags me out to have my web browser going while I play UO. And they are no good to me if I cannot access them while in the game.\r\n\r\nMy e-mail is:\r\nalumakiritan@yahoo.com\r\n\r\nYour feedback would be greatly appreciated.\r\nThank You.'),
(33, 18, '76.83.207.129', '2009-04-23', '23:15:09', 'Compton', 'Hater', 'Balka''s a hater'),
(36, 32, '77.37.134.190', '2009-12-03', '03:27:29', 'Catcher', ':)', 'Very cute ! '),
(39, 29, '174.110.164.114', '2010-06-28', '21:59:39', 'Cear Dallben Of Atlantic', 'I love this site', 'Huge fan. keep it going!');
