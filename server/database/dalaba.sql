/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50624
Source Host           : 127.0.0.1:3306
Source Database       : dalaba

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2015-08-29 01:01:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `advertisement`
-- ----------------------------
DROP TABLE IF EXISTS `advertisement`;
CREATE TABLE `advertisement` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `type` enum('telegraph_pole','catering','accommodation','housekeeping','supermarket','expressage') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'supermarket',
  `publish_time` datetime NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `text_content` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `fresh_coefficient` int(10) NOT NULL DEFAULT '0',
  `read_count` int(10) NOT NULL DEFAULT '0',
  `fresh_content` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_update_time` datetime DEFAULT NULL,
  `zan_num` int(10) unsigned NOT NULL DEFAULT '0',
  `address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lat` float(10,7) DEFAULT NULL,
  `lng` float(10,7) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid_idx` (`uid`),
  CONSTRAINT `advertisement_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of advertisement
-- ----------------------------
INSERT INTO `advertisement` VALUES ('91', '101', 'expressage', '2015-08-21 19:17:06', '你妹呀!', '你逗我玩呢!', 'data/img/advB0CD.jpg', '0', '0', '', null, '2', '世宁大厦 ', '39.9914284', '116.3586884');
INSERT INTO `advertisement` VALUES ('92', '109', 'expressage', '2015-08-21 19:17:19', '你妹呀!', '你逗我玩呢!', 'data/img/advE363.jpg', '0', '0', '', null, '0', '知春路地铁站', '39.9820938', '116.3466568');
INSERT INTO `advertisement` VALUES ('93', '107', 'expressage', '2015-08-21 19:17:27', '你妹呀!', '你逗我玩呢!', 'data/img/adv47.jpg', '0', '0', '', null, '1', '清河安宁里小区', '40.0520782', '116.3363037');
INSERT INTO `advertisement` VALUES ('94', '109', 'expressage', '2015-08-21 19:17:33', '你妹呀!', '你逗我玩呢!', 'data/img/adv1926.jpg', '0', '0', '', null, '0', '四拨子公交站', '40.0508041', '116.3439484');
INSERT INTO `advertisement` VALUES ('95', '107', 'expressage', '2015-08-21 19:17:40', '你妹呀!', '你逗我玩呢!', 'data/img/adv35BD.jpg', '0', '0', '', null, '1', '篮球场', '39.9857254', '116.3540955');
INSERT INTO `advertisement` VALUES ('96', '101', 'expressage', '2015-08-21 20:18:56', '你妹呀!', '你逗我玩呢!', 'data/img/adv4B04.jpg', '0', '0', '', null, '2', '乒乓球馆', '39.9863739', '116.3559723');
INSERT INTO `advertisement` VALUES ('97', '101', 'expressage', '2015-08-21 20:19:02', '你妹呀!', '你逗我玩呢!', 'data/img/adv6135.jpg', '0', '0', '', null, '2', '西土城地铁站', '39.9820671', '116.3606415');
INSERT INTO `advertisement` VALUES ('98', '107', 'expressage', '2015-08-21 20:19:04', '你妹呀!', '你逗我玩呢!', 'data/img/adv6B74.jpg', '0', '0', '', null, '0', '锦秋国际', '39.9816933', '116.3555298');
INSERT INTO `advertisement` VALUES ('99', '101', 'expressage', '2015-08-21 20:19:07', '你妹呀!', '你逗我玩呢!', 'data/img/adv7584.jpg', '0', '0', '', null, '2', '北航医院', '39.9866867', '116.3500137');
INSERT INTO `advertisement` VALUES ('100', '101', 'expressage', '2015-08-21 20:48:47', '你妹呀!', '你逗我玩呢!', 'data/img/advA176.jpg', '0', '0', '', null, '0', '上林溪', '40.0534058', '116.3332672');
INSERT INTO `advertisement` VALUES ('105', '98', 'expressage', '2015-08-29 00:03:14', '你妹呀!', '你逗我玩呢!', 'data/img/adv8853.jpg', '0', '0', '', null, '0', '上林溪', '40.0534058', '116.3332672');
INSERT INTO `advertisement` VALUES ('106', '98', 'expressage', '2015-08-29 00:03:18', '你妹呀!', '你逗我玩呢!', 'data/img/adv982D.jpg', '0', '0', '', null, '0', '上林溪', '40.0534058', '116.3332672');
INSERT INTO `advertisement` VALUES ('107', '98', 'expressage', '2015-08-29 00:03:20', '你妹呀!', '你逗我玩呢!', 'data/img/advA191.jpg', '0', '0', '', null, '0', '上林溪', '40.0534058', '116.3332672');

-- ----------------------------
-- Table structure for `fresh_content`
-- ----------------------------
DROP TABLE IF EXISTS `fresh_content`;
CREATE TABLE `fresh_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of fresh_content
-- ----------------------------
INSERT INTO `fresh_content` VALUES ('1', '1:从前有个人钓鱼，钓到了只鱿鱼。 \r\n      鱿鱼求他：你放了我吧，别把我烤来吃啊。 \r\n      那个人说：好的，那么我来考问你几个问题吧。 \r\n      鱿鱼很开心说：你考吧你考吧！ \r\n      然后这人就把鱿鱼给烤了.. ');
INSERT INTO `fresh_content` VALUES ('2', '我曾经得过精神分裂症，但现在我们已经康复了。');
INSERT INTO `fresh_content` VALUES ('3', '一留学生在美国考驾照，前方路标提示左转，他不是很确定，问考官： “turn left?” 答：“right” 于是……挂了.. ');
INSERT INTO `fresh_content` VALUES ('4', '有一天绿豆自杀从5楼跳下来，流了很多血，变成了红豆；一直流脓，又变成了黄豆；伤口结了疤，最后成了黑豆。');
INSERT INTO `fresh_content` VALUES ('5', '小明理了头发，第二天来到学校，同学们看到他的新发型，笑道：小明，你的头型好像个风筝哦！小明觉得很委屈，就跑到外面哭。哭着哭着～他就飞起来了…………');
INSERT INTO `fresh_content` VALUES ('6', '有个人长的像洋葱，走着走着就哭了…….');
INSERT INTO `fresh_content` VALUES ('7', '小企鹅有一天问他奶奶，“奶奶奶奶，我是不是一只企鹅啊？”“是啊，你当然是企鹅。”小企鹅又问爸爸，“爸爸爸爸，我是不是一只企鹅啊？”“是啊，你是企鹅啊，怎么了?”“可是，可是我怎么觉得那么冷呢？”');
INSERT INTO `fresh_content` VALUES ('8', '有一对玉米相爱了… 于是它们决定结婚… 结婚那天… 一个玉米找不到另一个玉米了… 这个玉米就问身旁的爆米花：你看到我们家玉米了吗? 爆米花：亲爱的，人家穿婚纱了嘛……. ');
INSERT INTO `fresh_content` VALUES ('9', '音乐课上 老师弹了一首贝多芬的曲子 小明问小华：“你懂音乐吗？” 小华：“是的” 小明：“那你知道老师在弹什麼吗？” 小华: “钢琴。” ');
INSERT INTO `fresh_content` VALUES ('10', 'Q：有两个人掉到陷阱里了，死的人叫死人，活人叫什么? A:叫救命啦! ');
INSERT INTO `fresh_content` VALUES ('11', '提问：布和纸怕什么？ 回答：布怕一万，纸怕万一。 原因：不(布)怕一万，只(纸)怕万一。');
INSERT INTO `fresh_content` VALUES ('12', '有一天有个婆婆坐车… 坐到中途婆婆不认识路了…. 婆婆用棍子打司机屁股说：这是哪？ 司机：这是我的屁股….. ');
INSERT INTO `fresh_content` VALUES ('13', '主持人问：猫是否会爬树？老鹰抢答：会！主持人：举例说明！老鹰含泪：那年，我睡熟了，猫爬上了树…后来就有了猫头鹰… ');
INSERT INTO `fresh_content` VALUES ('14', '俩屎壳螂讨论福利彩票，甲说:我要中了大奖就把方圆50里的厕所都买下来，每天吃个够！乙说:你丫太俗了！我要是中了大奖就包一活人，每天吃新鲜的！');

-- ----------------------------
-- Table structure for `thumb_up_for_adv`
-- ----------------------------
DROP TABLE IF EXISTS `thumb_up_for_adv`;
CREATE TABLE `thumb_up_for_adv` (
  `uid` int(10) unsigned NOT NULL,
  `adv_id` bigint(20) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`,`adv_id`),
  KEY `adv_id_fk` (`adv_id`),
  CONSTRAINT `user_id_fk` FOREIGN KEY (`uid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of thumb_up_for_adv
-- ----------------------------
INSERT INTO `thumb_up_for_adv` VALUES ('98', '91', '2015-08-28 23:37:40');
INSERT INTO `thumb_up_for_adv` VALUES ('98', '96', '2015-08-28 23:38:10');
INSERT INTO `thumb_up_for_adv` VALUES ('98', '97', '2015-08-28 23:38:16');
INSERT INTO `thumb_up_for_adv` VALUES ('98', '99', '2015-08-28 23:38:23');
INSERT INTO `thumb_up_for_adv` VALUES ('98', '104', '2015-08-29 00:12:50');
INSERT INTO `thumb_up_for_adv` VALUES ('101', '93', '2015-08-28 11:14:31');
INSERT INTO `thumb_up_for_adv` VALUES ('101', '104', '2015-08-29 00:12:59');
INSERT INTO `thumb_up_for_adv` VALUES ('107', '104', '2015-08-29 00:13:06');
INSERT INTO `thumb_up_for_adv` VALUES ('109', '96', '2015-08-28 23:38:47');
INSERT INTO `thumb_up_for_adv` VALUES ('109', '97', '2015-08-28 23:38:52');
INSERT INTO `thumb_up_for_adv` VALUES ('109', '99', '2015-08-28 23:38:39');
INSERT INTO `thumb_up_for_adv` VALUES ('109', '104', '2015-08-29 00:13:11');
INSERT INTO `thumb_up_for_adv` VALUES ('112', '104', '2015-08-29 00:13:18');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `cellphone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `position` blob,
  `type` enum('VIP','Silver','Gold','Normal') COLLATE utf8_unicode_ci DEFAULT 'Silver',
  `credit_values` int(10) DEFAULT '0',
  `register_time` datetime DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `fans_num` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('98', 'chenyang', '827ccb0eea8a706c4c34a16891f84e7b', '13426370455', 'chenyang2@qq.com', null, 'Silver', '0', '2015-07-27 17:24:00', '2015-08-29 00:17:39', '5');
INSERT INTO `user` VALUES ('101', 'chenxiaoer', '827ccb0eea8a706c4c34a16891f84e7b', '13426370455', 'chenyang2123@qq.com', null, 'Silver', '0', '2015-08-06 16:01:36', '2015-08-29 00:55:38', '2');
INSERT INTO `user` VALUES ('107', 'chenxiaoer3', '827ccb0eea8a706c4c34a16891f84e7b', '13426370450', 'chenyang123123@qq.com', null, 'Silver', '0', '2015-08-06 16:08:35', '2015-08-29 00:14:16', '2');
INSERT INTO `user` VALUES ('109', 'weichushun', '827ccb0eea8a706c4c34a16891f84e7b', '13426370460', 'weichushun@qq.com', null, 'Silver', '0', '2015-08-09 16:24:55', '2015-08-29 00:14:22', '2');
INSERT INTO `user` VALUES ('112', 'weichushunlalla', '827ccb0eea8a706c4c34a16891f84e7b', '13426370460', 'weichushun2@qq.com', null, 'Silver', '0', '2015-08-21 20:45:11', '2015-08-29 00:14:39', '2');

-- ----------------------------
-- Table structure for `user_collect`
-- ----------------------------
DROP TABLE IF EXISTS `user_collect`;
CREATE TABLE `user_collect` (
  `uid` int(10) unsigned NOT NULL,
  `adv_id` bigint(20) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`,`adv_id`),
  KEY `adv_id` (`adv_id`),
  CONSTRAINT `adv_id` FOREIGN KEY (`adv_id`) REFERENCES `advertisement` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `uid` FOREIGN KEY (`uid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user_collect
-- ----------------------------
INSERT INTO `user_collect` VALUES ('98', '99', '2015-08-29 00:14:00');
INSERT INTO `user_collect` VALUES ('101', '92', '2015-08-28 00:52:37');
INSERT INTO `user_collect` VALUES ('101', '93', '2015-08-28 00:52:43');
INSERT INTO `user_collect` VALUES ('101', '94', '2015-08-28 00:52:48');
INSERT INTO `user_collect` VALUES ('101', '95', '2015-08-28 00:52:53');
INSERT INTO `user_collect` VALUES ('107', '99', '2015-08-29 00:14:16');
INSERT INTO `user_collect` VALUES ('109', '99', '2015-08-29 00:14:22');
INSERT INTO `user_collect` VALUES ('112', '99', '2015-08-29 00:13:53');

-- ----------------------------
-- Table structure for `user_focus`
-- ----------------------------
DROP TABLE IF EXISTS `user_focus`;
CREATE TABLE `user_focus` (
  `uid_a` int(10) unsigned NOT NULL,
  `uid_b` int(10) unsigned NOT NULL,
  `ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid_a`,`uid_b`),
  KEY `uid_b` (`uid_b`),
  CONSTRAINT `uid_a` FOREIGN KEY (`uid_a`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `uid_b` FOREIGN KEY (`uid_b`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user_focus
-- ----------------------------
INSERT INTO `user_focus` VALUES ('98', '101', '2015-08-29 00:11:07');
INSERT INTO `user_focus` VALUES ('98', '107', '2015-08-29 00:11:15');
INSERT INTO `user_focus` VALUES ('98', '109', '2015-08-29 00:11:21');
INSERT INTO `user_focus` VALUES ('98', '112', '2015-08-29 00:11:26');
INSERT INTO `user_focus` VALUES ('101', '98', '2015-08-21 21:06:34');
INSERT INTO `user_focus` VALUES ('101', '107', '2015-08-28 00:50:13');
INSERT INTO `user_focus` VALUES ('101', '109', '2015-08-28 00:50:42');
INSERT INTO `user_focus` VALUES ('101', '112', '2015-08-28 11:20:40');
INSERT INTO `user_focus` VALUES ('107', '98', '2015-08-29 00:05:06');
INSERT INTO `user_focus` VALUES ('109', '98', '2015-08-29 00:02:43');
INSERT INTO `user_focus` VALUES ('109', '101', '2015-08-28 23:49:56');
INSERT INTO `user_focus` VALUES ('112', '98', '2015-08-29 00:05:18');
