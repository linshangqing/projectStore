-- 创建数据库
CREATE DATABASE IF NOT EXISTS project1;

-- 选择数据库
USE project1;

-- 创建用户表 user
CREATE TABLE IF NOT EXISTS `user`(
	`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR(255) NOT NULL UNIQUE,
	`password` CHAR(32) NOT NULL,
	`level` TINYINT NOT NULL DEFAULT 0,-- 0普通用户 1vip用户 2管理员 3 超级管理员
	`status` TINYINT NOT NULL DEFAULT 0,-- 0 开启 1 禁用
	`addtime` INT UNSIGNED NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 添加超级管理员权限
INSERT INTO user(id,name,password,level,status,addtime) VALUES(NULL,'admin',md5('123456'),3,0,20181219);

-- 创建分类表
CREATE TABLE IF NOT EXISTS `type`(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    pid INT NOT NULL,
    path VARCHAR(255) NOT NULL,
    display TINYINT NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=UTF8;

-- 创建用户详情表
CREATE TABLE IF NOT EXISTS `user_info`(
    `id`INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `uid` INT NOT NULL,
    `zname` VARCHAR(255) NOT NULL,
    `sex` TINYINT NOT NULL DEFAULT 0,
    `age` TINYINT NOT NULL DEFAULT 0,
    `tel` CHAR(11) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    `hunfou` TINYINT NOT NULL DEFAULT 0,
    `pic` VARCHAR(255)
)ENGINE=InnoDB DEFAULT CHARSET=UTF8;

--创建商品表 goods
CREATE TABLE IF NOT EXISTS `goods`(
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `typeid` INT NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `store` INT UNSIGNED NOT NULL DEFAULT 0,
    `status` TINYINT NOT NULL DEFAULT 0,
    `pic` VARCHAR(255) NOT NULL,
    `sales` INT NOT NULL DEFAULT 0,
    `company` VARCHAR(255) NOT NULL,
    `descr` VARCHAR(255)
)ENGINE = InnoDB DEFAULT CHARSET=UTF8;

-- 创建用户表 user
CREATE TABLE IF NOT EXISTS `user`(
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `password` CHAR(32) NOT NULL,
    `level` TINYINT NOT NULL DEFAULT 0,-- 0普通用户 1vip用户 2管理员 3 超级管理员
    `status` TINYINT NOT NULL DEFAULT 0,-- 0 开启 1 禁用
    `addtime` INT UNSIGNED NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


--创建订单表 orders
CREATE TABLE IF NOT EXISTS `orders`(
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    -- 会员ID
    `uid` INT NOT NULL,
    -- 联系人
    `linkname` VARCHAR(255) NOT NULL,
    -- 收货地址
    `address` VARCHAR(255) NOT NULL,
    -- 电话
    `tel` CHAR(11) NOT NULL,
    -- 邮编
    `code` CHAR(6) NOT NULL DEFAULT 000000,
    -- 留言
    `message` VARCHAR(255),
    -- 下单时间
    `addtime` INT UNSIGNED NOT NULL,
    -- 金额
    `total` DECIMAL(10,2) NOT NULL,
    -- 状态
    `status` TINYINT NOT NULL DEFAULT 0
  --默认值0  0未付款 1.已付款 2.已发货 3.已收货  4.订单完成  (扩展)5.评论
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


--订单详情表 order_info
CREATE TABLE IF NOT EXISTS `order_info`(
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    -- 订单用户ID
    `oid` INT NOT NULL,
    -- 商品ID
    `gid` INT NOT NULL,
    -- 商品名称
    `gname` VARCHAR(255) NOT NULL,
    -- 商品单价
    `price` DECIMAL(10,2) NOT NULL,
    -- 商品数量
    `gnum` INT NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 友情链接表
CREATE TABLE IF NOT EXISTS `link`(
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `url` VARCHAR(255) NOT NULL,
    `addtime` INT UNSIGNED NOT NULL,
    `status` TINYINT NOT NULL DEFAULT 0
)ENGINE=InnoDB DEFAULT CHARSET=utf8;-- 0 开启 1 禁用