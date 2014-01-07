CREATE TABLE `photos` ( `ID` int(11) NOT NULL AUTO_INCREMENT, `photo` text NOT NULL, `time` int(11) NOT NULL, `cats` text NOT NULL, `hash` char(16) NOT NULL, `hide` tinyint(1) NOT NULL, `asp_rat` double NOT NULL, PRIMARY KEY (`ID`) )
CREATE TABLE `photo_cats` ( `ID` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, PRIMARY KEY (`ID`) )
