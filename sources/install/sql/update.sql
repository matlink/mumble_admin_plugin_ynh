-- ---------------------------------------------------------------- --
-- |     MAP - Mumb1e Admin Plugin   |   http://www.mumb1e.de/    | --    
-- |              ___      ___   _________   _________            | --
-- |             |   \    /   | |   ___   | |   ___   |           | --
-- |             |    \__/    | |  |   |  | |  |   |  |           | --
-- |             |  |\    /|  | |  |___|  | |  |___|  |           | --
-- |             |  | \__/ |  | |   ___   | |   ______|           | --
-- |             |  |      |  | |  |   |  | |  |                  | --
-- |             |__|      |__| |__|   |__| |__|                  | --
-- |                                                              | --
-- | --- VERSION ---                                              | --
-- | Build: 6194                                                  | --
-- | Version: V2.5.2                                              | --
-- | Date: 2013-02-24                                             | --
-- | --- COPYRIGHT ---                                            | --
-- | Build by P.M. and M.H. | Accept the Copyrights!              | --
-- | © by Michael Koch 'aka' P.M. <pm@mumb1e.de>                  | --
-- | --- LICENSE ---                                              | --
-- | MAP - Mumb1e Admin Plugin is a dual-licensed software        | --
-- | MAP is released for private end-users under GPLv3            | --
-- |   >>  (visit at: http://www.gnu.org/licenses/gpl-3.0.html)   | --
-- | MAP is released for commercial use under a commerial license | --
-- |   >>  (visit at: http://www.mumb1e.de/en/about/license)      | --
-- | --- ATTENTION ---                                            | --
-- | Changing, editing or spreading of this sourcecode in other   | --
-- | scripts, or on other websites only with permission by PM!    | --
-- ---------------------------------------------------------------- --

-- DEFAULTS -----------------------------------------------------------------------------------------

-- BEGINN NEW STATEMENT

UPDATE `[database_prefix]settings` SET `value_2` = '[version_num]' WHERE `id` = 1 ;

-- BEGINN NEW STATEMENT

UPDATE `[database_prefix]settings` SET `value_2` = '[version_date]' WHERE `id` = 2 ;

-- BEGINN NEW STATEMENT

UPDATE `[database_prefix]settings` SET `value_2` = '[aktdate]' WHERE `id` = 15 ;

-- BEGINN NEW STATEMENT

UPDATE `[database_prefix]settings` SET `value_2` = '[version_build]' WHERE `id` = 35 ;

-- ADDITIONALS --------------------------------------------------------------------------------------

-- BEGINN NEW STATEMENT

INSERT INTO `[database_prefix]group` (`group_id`, `name`, `discription`, `user_id`, `date`) VALUES
('z08MJmSv4', 'Customer', '<p>This is a groups for customers of resellers. Define this permission group to your customers. Please attention, that this permissions are set to recursive, so the customers can&acute;t add more permissions to there accounts,as they don&acute;t have allready. They can&acute;t also not create users with more permissions, as the have granted itself, when they are defined to this group! This permissions group is set, that the users have enough permissions to edit there own servers, but cant cheat MAP! Try it, add a user to this group and login with this user!</p>', 1, '[aktdate]');

-- BEGINN NEW STATEMENT

INSERT INTO `[database_prefix]group_perm` (`group_id`, `section_id`, `value`, `date`) VALUES
('z08MJmSv4', '10001', 1, '[aktdate]'),
('z08MJmSv4', '10004', 1, '[aktdate]'),
('z08MJmSv4', '10007', 1, '[aktdate]'),
('z08MJmSv4', '10010', 1, '[aktdate]'),
('z08MJmSv4', '10013', 1, '[aktdate]'),
('z08MJmSv4', '10016', 1, '[aktdate]'),
('z08MJmSv4', '10019', 1, '[aktdate]'),
('z08MJmSv4', '10022', 1, '[aktdate]'),
('z08MJmSv4', '10025', 0, '[aktdate]'),
('z08MJmSv4', '10026', 0, '[aktdate]'),
('z08MJmSv4', '10028', 1, '[aktdate]'),
('z08MJmSv4', '10031', 0, '[aktdate]'),
('z08MJmSv4', '10034', 1, '[aktdate]'),
('z08MJmSv4', '11001', 1, '[aktdate]'),
('z08MJmSv4', '11002', 1, '[aktdate]'),
('z08MJmSv4', '11005', 0, '[aktdate]'),
('z08MJmSv4', '11006', 1, '[aktdate]'),
('z08MJmSv4', '11007', 0, '[aktdate]'),
('z08MJmSv4', '11020', 1, '[aktdate]'),
('z08MJmSv4', '11021', 1, '[aktdate]'),
('z08MJmSv4', '11022', 1, '[aktdate]'),
('z08MJmSv4', '12001', 0, '[aktdate]'),
('z08MJmSv4', '12002', 0, '[aktdate]'),
('z08MJmSv4', '12003', 0, '[aktdate]'),
('z08MJmSv4', '12004', 0, '[aktdate]'),
('z08MJmSv4', '12005', 0, '[aktdate]'),
('z08MJmSv4', '12006', 1, '[aktdate]'),
('z08MJmSv4', '12007', 1, '[aktdate]'),
('z08MJmSv4', '12008', 1, '[aktdate]'),
('z08MJmSv4', '12009', 1, '[aktdate]'),
('z08MJmSv4', '12010', 1, '[aktdate]'),
('z08MJmSv4', '12011', 0, '[aktdate]'),
('z08MJmSv4', '12012', 0, '[aktdate]'),
('z08MJmSv4', '12013', 0, '[aktdate]'),
('z08MJmSv4', '12014', 0, '[aktdate]'),
('z08MJmSv4', '12015', 0, '[aktdate]'),
('z08MJmSv4', '12016', 1, '[aktdate]'),
('z08MJmSv4', '12017', 1, '[aktdate]'),
('z08MJmSv4', '12018', 1, '[aktdate]'),
('z08MJmSv4', '12019', 1, '[aktdate]'),
('z08MJmSv4', '12020', 1, '[aktdate]'),
('z08MJmSv4', '12021', 1, '[aktdate]'),
('z08MJmSv4', '12022', 1, '[aktdate]'),
('z08MJmSv4', '12023', 1, '[aktdate]'),
('z08MJmSv4', '12024', 1, '[aktdate]'),
('z08MJmSv4', '12025', 0, '[aktdate]'),
('z08MJmSv4', '12026', 0, '[aktdate]'),
('z08MJmSv4', '12027', 0, '[aktdate]'),
('z08MJmSv4', '12028', 0, '[aktdate]'),
('z08MJmSv4', '12029', 0, '[aktdate]'),
('z08MJmSv4', '12030', 0, '[aktdate]'),
('z08MJmSv4', '12031', 1, '[aktdate]'),
('z08MJmSv4', '12032', 1, '[aktdate]'),
('z08MJmSv4', '12033', 1, '[aktdate]'),
('z08MJmSv4', '12034', 1, '[aktdate]'),
('z08MJmSv4', '12035', 1, '[aktdate]'),
('z08MJmSv4', '12036', 0, '[aktdate]'),
('z08MJmSv4', '12037', 1, '[aktdate]'),
('z08MJmSv4', '13001', 1, '[aktdate]'),
('z08MJmSv4', '13002', 1, '[aktdate]'),
('z08MJmSv4', '13003', 1, '[aktdate]'),
('z08MJmSv4', '13004', 1, '[aktdate]'),
('z08MJmSv4', '14002', 1, '[aktdate]'),
('z08MJmSv4', '14005', 1, '[aktdate]'),
('z08MJmSv4', '14006', 1, '[aktdate]'),
('z08MJmSv4', '14007', 1, '[aktdate]'),
('z08MJmSv4', '14011', 1, '[aktdate]'),
('z08MJmSv4', '14012', 1, '[aktdate]'),
('z08MJmSv4', '14013', 1, '[aktdate]'),
('z08MJmSv4', '14014', 1, '[aktdate]'),
('z08MJmSv4', '17001', 1, '[aktdate]'),
('z08MJmSv4', '17002', 1, '[aktdate]'),
('z08MJmSv4', '17005', 1, '[aktdate]'),
('z08MJmSv4', '17006', 1, '[aktdate]'),
('z08MJmSv4', '17007', 0, '[aktdate]'),
('z08MJmSv4', '17020', 0, '[aktdate]'),
('z08MJmSv4', '17021', 1, '[aktdate]'),
('z08MJmSv4', '18001', 1, '[aktdate]'),
('z08MJmSv4', '18002', 1, '[aktdate]'),
('z08MJmSv4', '18003', 1, '[aktdate]'),
('z08MJmSv4', '18004', 1, '[aktdate]'),
('z08MJmSv4', '18005', 1, '[aktdate]'),
('z08MJmSv4', '18006', 0, '[aktdate]'),
('z08MJmSv4', '18007', 0, '[aktdate]'),
('z08MJmSv4', '18008', 0, '[aktdate]'),
('z08MJmSv4', '18009', 1, '[aktdate]'),
('z08MJmSv4', '18010', 1, '[aktdate]'),
('z08MJmSv4', '18011', 1, '[aktdate]'),
('z08MJmSv4', '18012', 0, '[aktdate]'),
('z08MJmSv4', '18013', 1, '[aktdate]'),
('z08MJmSv4', '18014', 1, '[aktdate]'),
('z08MJmSv4', '18015', 1, '[aktdate]'),
('z08MJmSv4', '18016', 1, '[aktdate]'),
('z08MJmSv4', '18017', 1, '[aktdate]'),
('z08MJmSv4', '18018', 1, '[aktdate]'),
('z08MJmSv4', '18019', 1, '[aktdate]'),
('z08MJmSv4', '19001', 1, '[aktdate]'),
('z08MJmSv4', '19006', 1, '[aktdate]'),
('z08MJmSv4', '19007', 1, '[aktdate]'),
('z08MJmSv4', '19008', 1, '[aktdate]'),
('z08MJmSv4', '20001', 0, '[aktdate]'),
('z08MJmSv4', '20002', 0, '[aktdate]'),
('z08MJmSv4', '20003', 0, '[aktdate]'),
('z08MJmSv4', '20004', 0, '[aktdate]'),
('z08MJmSv4', '25001', 1, '[aktdate]'),
('z08MJmSv4', '25002', 0, '[aktdate]'),
('z08MJmSv4', '25003', 0, '[aktdate]'),
('z08MJmSv4', '25004', 0, '[aktdate]'),
('z08MJmSv4', '25005', 0, '[aktdate]'),
('z08MJmSv4', '25006', 1, '[aktdate]'),
('z08MJmSv4', '25012', 1, '[aktdate]'),
('z08MJmSv4', '25014', 1, '[aktdate]'),
('z08MJmSv4', '35001', 0, '[aktdate]'),
('z08MJmSv4', '35002', 0, '[aktdate]'),
('z08MJmSv4', '35003', 0, '[aktdate]'),
('z08MJmSv4', '35004', 0, '[aktdate]'),
('z08MJmSv4', '35005', 0, '[aktdate]'),
('z08MJmSv4', '35006', 0, '[aktdate]'),
('z08MJmSv4', '35007', 0, '[aktdate]'),
('z08MJmSv4', '35008', 0, '[aktdate]'),
('z08MJmSv4', '35009', 0, '[aktdate]'),
('z08MJmSv4', '35010', 0, '[aktdate]'),
('z08MJmSv4', '35011', 0, '[aktdate]'),
('z08MJmSv4', '35012', 0, '[aktdate]'),
('z08MJmSv4', '93001', 0, '[aktdate]'),
('z08MJmSv4', '93002', 0, '[aktdate]'),
('z08MJmSv4', '93003', 0, '[aktdate]'),
('z08MJmSv4', '93004', 0, '[aktdate]'),
('z08MJmSv4', '93005', 0, '[aktdate]'),
('z08MJmSv4', '93006', 0, '[aktdate]'),
('z08MJmSv4', '93007', 0, '[aktdate]'),
('z08MJmSv4', '93021', 0, '[aktdate]'),
('z08MJmSv4', '93022', 0, '[aktdate]'),
('z08MJmSv4', '93023', 0, '[aktdate]'),
('z08MJmSv4', '93024', 0, '[aktdate]'),
('z08MJmSv4', '93025', 0, '[aktdate]'),
('z08MJmSv4', '93026', 0, '[aktdate]');

-- BEGINN NEW STATEMENT

DELETE FROM `[database_prefix]section` WHERE `section_id` = 10026;

-- BEGINN NEW STATEMENT

INSERT INTO `[database_prefix]settings` (`id`, `value_1`, `value_2`) VALUES
(47, 'email_sender', '');

-- BEGINN NEW STATEMENT

INSERT INTO `[database_prefix]section` (`section_id`, `name`, `name_translation`, `discription`, `discr_translation`, `default_perms`, `section_name`, `section_path`, `error_message`, `data_count`) VALUES
(10026, 'menu_link_email', '_perm_name_menu_link_email', 'Anzeigen des E-Mail Links im Menu', '_perm_disc_menu_link_email', 1, '', '/inc/layout.php', 0, 0),
(12026, 'server_conf_registerLocation', '_perm_name_server_conf_registerLocation', 'Serverconfig ändern registerLocation', '_perm_disc_server_conf_registerLocation', 1, 'edit_server', '/server/index.php', 0, 0),
(12027, 'server_conf_sslca', '_perm_name_server_conf_sslca', 'Serverconfig ändern sslca', '_perm_disc_server_conf_sslca', 1, 'edit_server', '/server/index.php', 0, 0),
(12028, 'server_conf_sslcert', '_perm_name_server_conf_sslcert', 'Serverconfig ändern sslcert', '_perm_disc_server_conf_sslcert', 1, 'edit_server', '/server/index.php', 0, 0),
(12029, 'server_conf_sslkey', '_perm_name_server_conf_sslkey', 'Serverconfig ändern sslkey', '_perm_disc_server_conf_sslkey', 1, 'edit_server', '/server/index.php', 0, 0),
(12030, 'server_conf_sslpassphrase', '_perm_name_server_conf_sslpassphrase', 'Serverconfig ändern sslpassphrase', '_perm_disc_server_conf_sslpassphrase', 1, 'edit_server', '/server/index.php', 0, 0),
(12031, 'server_conf_channelnestinglimit', '_perm_name_server_conf_channelnestinglimit', 'Serverconfig ändern channelnestinglimit', '_perm_disc_server_conf_channelnestinglimit', 1, 'edit_server', '/server/index.php', 0, 0),
(12032, 'server_conf_opusthreshold', '_perm_name_server_conf_opusthreshold', 'Serverconfig ändern opusthreshold', '_perm_disc_server_conf_opusthreshold', 1, 'edit_server', '/server/index.php', 0, 0),
(12033, 'server_conf_suggestpositional', '_perm_name_server_conf_suggestpositional', 'Serverconfig ändern suggestpositional', '_perm_disc_server_conf_suggestpositional', 1, 'edit_server', '/server/index.php', 0, 0),
(12034, 'server_conf_suggestpushtotalk', '_perm_name_server_conf_suggestpushtotalk', 'Serverconfig ändern suggestpushtotalk', '_perm_disc_server_conf_suggestpushtotalk', 1, 'edit_server', '/server/index.php', 0, 0),
(12035, 'server_conf_suggestversion', '_perm_name_server_conf_suggestversion', 'Serverconfig ändern suggestversion', '_perm_disc_server_conf_suggestversion', 1, 'edit_server', '/server/index.php', 0, 0),
(12036, 'server_conf_sendversion', '_perm_name_server_conf_sendversion', 'Serverconfig ändern sendversion', '_perm_disc_server_conf_sendversion', 1, 'edit_server', '/server/index.php', 0, 0),
(12037, 'server_conf_bonjour', '_perm_name_server_conf_bonjour', 'Serverconfig ändern bonjour', '_perm_disc_server_conf_bonjour', 1, 'edit_server', '/server/index.php', 0, 0);

INSERT INTO `[database_prefix]user_perm` (`perm_id`, `section_id`, `value`, `date`) VALUES
('1s4lD7GLo', '12026', 0, '[aktdate]'),
('1s4lD7GLo', '12027', 0, '[aktdate]'),
('1s4lD7GLo', '12028', 0, '[aktdate]'),
('1s4lD7GLo', '12029', 0, '[aktdate]'),
('1s4lD7GLo', '12030', 0, '[aktdate]'),
('1s4lD7GLo', '12031', 0, '[aktdate]'),
('1s4lD7GLo', '12032', 0, '[aktdate]'),
('1s4lD7GLo', '12033', 0, '[aktdate]'),
('1s4lD7GLo', '12034', 0, '[aktdate]'),
('1s4lD7GLo', '12035', 0, '[aktdate]'),
('1s4lD7GLo', '12036', 0, '[aktdate]'),
('1s4lD7GLo', '12037', 0, '[aktdate]');