<?php
# basic information
$name = "Software";
$description = "A block module to display software (used at hilands.com)";
$version = "20110709";
$core = "alpha";

$moduleInsert = "
INSERT INTO `modules` (`name`, `description`, `path`, `content_file`, `content_tpl_file`, `content_db`, `view_file`, `boolEnable`) VALUES
('Software', 'A block module to display software (used at hilands.com)', 'mod/software/', '', '', '', '', 1);
";

$blocksInsert = "INSERT INTO `module_blocks` (`mid`, `block`, `block_file`, `boolLoadinall`, `boolSupErrMsg`) VALUES
([mid], 'software', 'software.php', 0, 0);
";
$moduleTable = "
CREATE TABLE IF NOT EXISTS `mod_software` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'tbl id',
  `description` longtext,
  `icon_cd` tinyint(1) NOT NULL DEFAULT '0',
  `icon_cli` tinyint(1) NOT NULL DEFAULT '0',
  `icon_easy` tinyint(1) NOT NULL DEFAULT '0',
  `icon_gui` tinyint(1) NOT NULL DEFAULT '0',
  `icon_gnu` tinyint(1) NOT NULL DEFAULT '0',
  `icon_linux` tinyint(1) NOT NULL DEFAULT '0',
  `icon_tech` tinyint(1) NOT NULL DEFAULT '0',
  `icon_windows` tinyint(1) NOT NULL DEFAULT '0',
  `img` varchar(200) NOT NULL,
  `imgthumb` varchar(200) NOT NULL,
  `filesize` varchar(20) NOT NULL,
  `filename` varchar(100) NOT NULL,
  `header` varchar(150) NOT NULL,
  `md5` varchar(32) NOT NULL,
  `urlwebsite` varchar(255) NOT NULL,
  `urldownload` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

";

/*
INSERT INTO `mod_software` (`id`, `description`, `icon_cd`, `icon_cli`, `icon_easy`, `icon_gui`, `icon_gnu`, `icon_linux`, `icon_tech`, `icon_windows`, `img`, `imgthumb`, `filesize`, `filename`, `header`, `md5`, `urlwebsite`, `urldownload`) VALUES
(1, 'A Windows system cleaner. This program will clean arbitrary files (updates, temp files etc). Along with removing system registry files that are no longer in use. An additional feature of an uninstaller for programs that are stuck in your windows add remove programs.\r\n', 0, 0, 1, 1, 0, 0, 0, 1, 'img/software/ccleaner.png', 'img/software/ccleaner.thumb.png', '3,326,576', 'ccsetup226.exe', 'CCleaner', 'd3f8f4b1fd52f34349521f2275555927', 'http://www.ccleaner.com/', 'http://www.filehippo.com/download_ccleaner/download/5fe2e2248a38a9e27491341bff4c1a21/'),
(2, 'Darik''s Boot and Nuke is a harddrive cleaning utility. Capable of overwriting, wiping, and zero''ing harddrives.<br /> Bootable CD.\r\n', 1, 1, 0, 0, 1, 0, 0, 0, '/img/software/dban.png', '/img/software/dban.thumb.png', '2,146,304', 'dban-1.0.7_i386.iso', 'DBAN', 'd6ee60178e28882c44495f37b1f672d8', 'http://dban.sourceforge.net/', 'http://sourceforge.net/projects/dban/files/dban/dban-1.0.7/dban-1.0.7_i386.iso/download'),
(3, 'ExamDiff is a freeware visual file compare tool. It features a side by side screen display allowing you to compare two files. The interface is easy to use and you can set up two files to compare in a matter of minutes.\r\n', 0, 0, 1, 1, 0, 0, 0, 1, '/img/software/examdiff.png', '/img/software/examdiff.thumb.png', '525,602 bytes', 'ed18_setup.zip', 'ExamDiff', '8231ba89e19f62ebd4a8139855305a79', 'http://www.prestosoft.com/edp_examdiff.asp', 'http://www.prestosoft.com/ps_download.asp?file=ed18_setup.zip&prod=ed'),
(4, 'GMER is a Root Kit Revealer. If you can''t find it with your Anti Virus software, Hijack This, Spyware Cleanup tools it might be hiding itself in the low level portions of the system. Your best chance is GMER. Warning this tool WILL find your embedded Anti Virus Software.\r\n', 0, 0, 0, 1, 0, 0, 1, 1, 'img/software/gmer.png', 'img/software/gmer.thumb.png', '', '', 'GMER', '', 'http://www.gmer.net/', ''),
(5, 'GNU Image Manipulation Program. A free graphic design program which works on multiple operating systems. This is commonly used as a free alternative to adobe photoshop.\r\n', 0, 0, 0, 1, 1, 1, 0, 1, 'img/software/gimp.png', 'img/software/gimp.thumb.png', '16,871,432', 'gimp-2.6.7-i686-setup.exe', 'GIMP', '00ceffc4a959ae9d97ef035eca681f8f', 'http://www.gimp.org', 'http://sourceforge.net/projects/gimp-win/files/GIMP%20%2B%20GTK%2B%20%28stable%20release%29/GIMP%202.6.7%20%2B%20GTK%2B%202.16.5/gimp-2.6.7-i686-setup.exe/download'),
(6, 'GSview is a graphical interface for Ghostscript. With Ghostscript your system can read Postscript pages and Adobe Portable Document Format (PDF) Files.\r\n', 0, 0, 1, 1, 0, 0, 0, 1, 'img/software/gsview.png', 'img/software/gsview.thumb.png', '', '', 'GSview', '', 'http://pages.cs.wisc.edu/~ghost/', ''),
(7, 'Hexedit is a simple hexeditor to view files in hexidecimal.\r\n', 0, 0, 0, 1, 0, 0, 1, 1, 'img/software/hexedit.png', 'img/software/hexedit.thumb.png', '', '', 'Hexedit', '', 'http://www.physics.ohio-state.edu/~prewett/hexedit/', ''),
(8, 'Hijack This is a program that sifts through the windows registry and other locations to find potential malware. This software will scourer your system to and find programs that are loaded by certain system triggers that are not part of the default operating system.<br />\r\nThis software has been sold to TrendMicro Systems who is now the active provider for updates.\r\n', 0, 0, 0, 1, 0, 0, 1, 1, 'img/software/hijackthis.png', 'img/software/hijackthis.thumb.png', '', '', 'Hijack This', '', 'http://free.antivirus.com/hijackthis/', ''),
(9, 'ISO recorder is a plugin that allows you to copy and burn CD''s into ISO files.\r\n', 0, 0, 1, 1, 0, 0, 1, 1, '', '', '', '', 'ISO Recorder', '', 'http://isorecorder.alexfeinman.com/', ''),
(10, 'Magic Disc is a freeware virtual CD-Rom drive for windows. You can create and load CD and DVD images. This software will mount an ISO and make it appear as if it is a normal drive under my computer!\r\n', 0, 0, 0, 1, 0, 0, 1, 1, 'img/software/magicdisc.png', 'img/software/magicdisc.thumb.png', '', '', 'Magic Disc', '', 'http://www.magiciso.com/', ''),
(11, 'MD5 is gui md5 hash generator for windows. The interface is simple, select the <i>Generate Checksums</i> then drag the file you want on the window!\r\n', 0, 0, 1, 1, 0, 0, 1, 1, 'img/software/md5.png', 'img/software/md5.thumb.png', '', '', 'MD5', '', 'http://www.toast442.org/md5/', ''),
(12, 'Bootable CD to test your systems memory\r\n', 1, 1, 1, 0, 0, 0, 1, 0, '', '', '', '', 'Memtest86', '', 'http://www.memtest.org/', ''),
(13, 'Nmap is one of the best port scanners created. Version 4.62 for windows. This is a command line tool and will require basic knowledge of the dos prompt.\r\n<br />\r\n<br />\r\nYou may need to install winpcap for this software to work.\r\n', 0, 1, 0, 1, 0, 1, 1, 1, 'img/software/nmap.png', 'img/software/nmap.thumb.png', '', '', 'Nmap', '', 'http://www.nmap.org/', ''),
(14, 'A free Icon pack licensed under the <a href="http://creativecommons.org/licenses/by/3.0/">Creative Commons</a>. I found these icons on <a href="http://www.freeiconsweb.com/">Free Icons Web</a>.<br />\r\nThe Floppy Disk download icon is being used on this website.\r\n', 0, 0, 0, 0, 0, 0, 0, 0, 'img/software/pi_diagona_pack.png', 'img/software/pi_diagona_pack.thumb.png', '', '', 'PI Diagona Pack', '', 'http://www.pinvoke.com/', ''),
(15, 'An Instant Messenger client capable of communicating with popular instant messengers like AIM, ICQ, MSN, Yahoo and more.\r\n', 0, 0, 1, 1, 1, 1, 0, 1, '', '', '297,749', 'pidgin-2.6.4.exe', 'Pidgin', '2b16a8db865551a904c45923de12508e', 'http://pidgin.im/', 'http://sourceforge.net/projects/pidgin/files/Pidgin/pidgin-2.6.4.exe'),
(16, 'Create your own Portable Document Format (PDF) files. Primo PDF creates a fake print driver your system can print to. Anything that can be printed can be converted into a PDF file. The backend of this system uses ghostscript.\r\n', 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 'Primo PDF', '', 'http://www.primopdf.com/', ''),
(17, 'Windows Password Viewer. Simple password viewer with visual basic source code. When you hover over a password it can detect it grabs the windows attributes and then displays the password in the pwviewer window along with removing the asteriks (*) from the password window they are in.  This does not work with passwords stored in say firefox.', 0, 0, 1, 1, 0, 0, 1, 1, 'img/software/passwordviewer.png', 'img/software/passwordviewer.thumb.png', '7,258', 'pwviewer.zip', 'pwviewer', 'cf5d605327c471476be219bcba35f721', 'Unknown', 'http://www.hilands.com/files/pwviewer.zip'),
(18, 'An open source encryption utility. Allows full disk encryption, USB encryption. Encrypt a standard container, which is a file with x amount of space, that mounts as a windows drive.<br />\r\nTrue Crypt has an easy to follow installation, configuration, and usage. \r\n', 0, 0, 1, 1, 0, 1, 1, 1, 'img/software/truecrypt.png', 'img/software/truecrypt.thumb.png', '', '', 'True Crypt', '', 'http://www.truecrypt.org/', ''),
(19, 'If you are in need of booting to a Removable USB Drive, Thumb Drive, or SD card this is the tool for you. Use bootable ISO images and transfer them to your removable media from a Windows or Linux operating system.\r\n', 0, 0, 1, 1, 0, 1, 1, 1, '', '', '', '', 'UNetbootin', '', 'http://unetbootin.sourceforge.net/', ''),
(20, 'A free windows password recovery tool. The software can be used off of a bootable CD and will give you the option to overwrite the windows password. Encrypted windows files and folders may cause some issues.\r\n', 1, 1, 0, 0, 0, 0, 1, 0, '', '', '', '', 'Offline NT Password & Registry Editor, Bootdisk / CD', '', 'http://pogostick.net/~pnh/ntpasswd/', ''),
(21, 'Windows IP Firewall (WIPFW) is a windows version of the popular IPFW for FreeBSD.\r\n', 0, 0, 0, 0, 0, 0, 1, 1, '', '', '', '', 'wipfw', '', 'http://wipfw.sourceforge.net/', ''),
(22, 'Wireshark (formerly Ethereal) is a network packet sniffer. Capable of live streaming capture and TCP stack traces. A simple gui and easy to use interface.\r\n<br />\r\n<br />\r\nYou may need to install winpcap for this software to work. Though I believe it is packaged with the installer\r\n', 0, 0, 1, 1, 0, 1, 1, 1, 'img/software/wireshark.png', 'img/software/wireshark.thumb.png', '', '', 'Wireshark', '', 'http://www.wireshark.org/', ''),
(23, 'Process Explorer shows a list of process currently running on the system along with the DLL files they are using. ', 0, 0, 0, 1, 0, 0, 1, 1, '/img/software/processexplorer.png', '/img/software/processexplorer.thumb.png', '1,615,732', 'ProcessExplorer.zip', 'Process Explorer', '595a29aa50f10f5c4740536eae0e95af', 'http://technet.microsoft.com/en-us/sysinternals/bb896653.aspx', 'http://technet.microsoft.com/en-us/sysinternals/bb896653.aspx'),
(24, 'Process Monitor will show various activities your system is running. This is a great resource for tracking down what programs are accessing what registry keys and entries.', 0, 0, 0, 1, 0, 0, 1, 1, '/img/software/processmonitor.png', '/img/software/processmonitor.thumb.png', '1,309,584', 'ProcessMonitor.zip', 'Process Monitor', 'e8c581912e2d29895c074e595e8a9a61', 'http://technet.microsoft.com/en-us/sysinternals/bb896645.aspx', 'http://technet.microsoft.com/en-us/sysinternals/bb896645.aspx'),
(25, 'Zortam is an all in one MP3 music tool allowing you to rip, modify, and listen to your music. The interface allows you to easily fix your MP3 ID3 tags allowing you to easily add lyrics and album covers to your MP3''s. This is the only MP3 "media studio" that has done everything I wanted it to with the easiest learning curve.\r\n', 0, 0, 1, 1, 0, 0, 1, 1, 'http://www.zortam.com/images/mmsslide4a.gif', 'http://www.zortam.com/images/mmsslide4.gif', '5,754,292', 'mms1001.exe', 'Zortam', '28919dd443d8846ad3aa44b3b494ddb4', 'http://www.zortam.com/', 'http://www.zortam.com/mms1001.exe');
*/

?>
