-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 05 2019 г., 15:27
-- Версия сервера: 5.6.41
-- Версия PHP: 7.0.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `1_0_2_4`
--

-- --------------------------------------------------------

--
-- Структура таблицы `actions`
--

CREATE TABLE `actions` (
  `id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `client_id` int(11) NOT NULL,
  `action_type_id` int(11) NOT NULL,
  `action_status_id` int(11) NOT NULL,
  `action_priority_id` int(11) NOT NULL,
  `responsable_id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `action_date` datetime NOT NULL,
  `description` text
) ENGINE=InnoDB AVG_ROW_LENGTH=3276 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `actions`
--

INSERT INTO `actions` (`id`, `text`, `client_id`, `action_type_id`, `action_status_id`, `action_priority_id`, `responsable_id`, `creation_date`, `action_date`, `description`) VALUES
(1, 'Новая задача', 1, 3, 1, 1, 1, '2019-07-05 11:44:42', '2019-12-31 11:00:00', '');

-- --------------------------------------------------------

--
-- Структура таблицы `actions_files`
--

CREATE TABLE `actions_files` (
  `id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `actions_priority`
--

CREATE TABLE `actions_priority` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=3276 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `actions_priority`
--

INSERT INTO `actions_priority` (`id`, `name`, `color`) VALUES
(1, 'Средний', 'green'),
(2, 'Важный', 'white'),
(3, 'Низкий', 'gray');

-- --------------------------------------------------------

--
-- Структура таблицы `actions_statuses`
--

CREATE TABLE `actions_statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=3276 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `actions_statuses`
--

INSERT INTO `actions_statuses` (`id`, `name`, `color`) VALUES
(1, 'Ожидается', '#5DBB4B'),
(2, 'Завершено', '#9E9E9E'),
(3, 'Нет результата', '#E8A20D');

-- --------------------------------------------------------

--
-- Структура таблицы `actions_types`
--

CREATE TABLE `actions_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=3276 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `actions_types`
--

INSERT INTO `actions_types` (`id`, `name`) VALUES
(3, 'Действие не указано'),
(4, 'Звонок'),
(5, 'Встреча'),
(6, 'Предложение'),
(7, 'Акция');

-- --------------------------------------------------------

--
-- Структура таблицы `activations`
--

CREATE TABLE `activations` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `add_data` text,
  `date` datetime NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=8192 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `additional_fields`
--

CREATE TABLE `additional_fields` (
  `id` int(11) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(55) NOT NULL,
  `default_value` text,
  `required` tinyint(1) NOT NULL,
  `weight` int(11) NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `section_id` int(11) NOT NULL,
  `tip` varchar(255) DEFAULT NULL,
  `noEdit` tinyint(1) NOT NULL DEFAULT '0',
  `unique` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `additional_fields`
--

INSERT INTO `additional_fields` (`id`, `table_name`, `name`, `type`, `default_value`, `required`, `weight`, `size`, `section_id`, `tip`, `noEdit`, `unique`) VALUES
(1, 'fieldFio', 'Имя', 'varchar', '', 1, 1, '1/3', 1, NULL, 0, 1),
(2, 'fieldTelephone', 'Телефон', 'varchar', '', 0, 2, '1/3', 1, '', 0, 1),
(3, 'fieldEmail', 'E-mail', 'varchar', '', 0, 3, '1/3', 1, '', 0, 1),
(4, 'field_4', 'Источник', 'select', '{\"1\":{\"optionName\":\"\\u041d\\u0435\\u0442 \\u0438\\u0441\\u0442\\u043e\\u0447\\u043d\\u0438\\u043a\\u0430\",\"optionWeight\":1,\"default\":\"1\",\"id\":1},\"2\":{\"optionName\":\"\\u0421\\u0430\\u0439\\u0442\",\"optionWeight\":2,\"id\":2},\"3\":{\"optionName\":\"\\u0421\\u043e\\u0446. \\u0441\\u0435\\u0442\\u0438\",\"optionWeight\":3,\"id\":3},\"4\":{\"optionName\":\"\\u0414\\u043e\\u0441\\u043a\\u0430 \\u043e\\u0431\\u044a\\u044f\\u0432\\u043b\\u0435\\u043d\\u0438\\u0439\",\"optionWeight\":4,\"id\":4},\"5\":{\"optionName\":\"\\u041b\\u0438\\u0441\\u0442\\u043e\\u0432\\u043a\\u0438\",\"optionWeight\":5,\"id\":5}}', 0, 4, '1/1', 1, '', 0, 0),
(5, 'field_5', 'Город', 'select', '{\"1\":{\"optionName\":\"\\u041d\\u0435\\u0442 \\u0433\\u043e\\u0440\\u043e\\u0434\\u0430\",\"optionWeight\":1,\"default\":\"1\",\"id\":1},\"2\":{\"optionName\":\"\\u041c\\u043e\\u0441\\u043a\\u0432\\u0430\",\"optionWeight\":2,\"id\":2},\"3\":{\"optionName\":\"\\u0421\\u0430\\u043d\\u043a\\u0442-\\u041f\\u0435\\u0442\\u0435\\u0440\\u0431\\u0443\\u0433\",\"optionWeight\":3,\"id\":3},\"4\":{\"optionName\":\"\\u0415\\u043a\\u0430\\u0442\\u0435\\u0440\\u0438\\u043d\\u0431\\u0443\\u0440\\u0433\",\"optionWeight\":4,\"id\":4},\"5\":{\"optionName\":\"\\u041f\\u0435\\u0440\\u043c\\u044c\",\"optionWeight\":5,\"id\":5},\"6\":{\"optionName\":\"\\u0422\\u044e\\u043c\\u0435\\u043d\\u044c\",\"optionWeight\":6,\"id\":6},\"7\":{\"optionName\":\"\\u0421\\u0430\\u0440\\u0430\\u0442\\u043e\\u0432\",\"optionWeight\":7,\"id\":7},\"8\":{\"optionName\":\"\\u041a\\u0440\\u0430\\u0441\\u043d\\u043e\\u0434\\u0430\\u0440\",\"optionWeight\":8,\"id\":8}}', 0, 5, '1/1', 1, '', 0, 0),
(6, 'field_6', 'Адрес', 'varchar', '', 0, 6, '1/3', 1, '', 0, 0),
(7, 'field_7', 'Web сайт', 'varchar', '', 0, 7, '1/3', 1, '', 0, 0),
(8, 'field_8', 'Вконтакте', 'varchar', '', 0, 8, '1/3', 1, '', 0, 0),
(9, 'field_9', 'Facebook', 'varchar', '', 0, 9, '1/3', 1, '', 0, 0),
(10, 'field_10', 'Описание', 'varchar', '', 0, 1, '1/1', 2, '', 0, 0),
(11, 'field_11', 'Сообщение', 'varchar', '', 0, 2, '1/1', 2, '', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `additional_fields_section`
--

CREATE TABLE `additional_fields_section` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `access` varchar(10) NOT NULL,
  `weight` int(10) NOT NULL,
  `noEdit` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `additional_fields_section`
--

INSERT INTO `additional_fields_section` (`id`, `name`, `access`, `weight`, `noEdit`) VALUES
(1, 'О контакте', 'all', 1, 1),
(2, 'Описание', 'all', 2, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `additional_fields_values`
--

CREATE TABLE `additional_fields_values` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `fieldFio` TEXT NOT NULL,
  `fieldTelephone` TEXT NOT NULL,
  `fieldEmail` TEXT NOT NULL,
  `field_4` varchar(255) NOT NULL,
  `field_5` varchar(255) NOT NULL,
  `field_6` TEXT NOT NULL,
  `field_7` TEXT NOT NULL,
  `field_8` TEXT NOT NULL,
  `field_9` TEXT NOT NULL,
  `field_10` TEXT NOT NULL,
  `field_11` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `additional_fields_values`
--

INSERT INTO `additional_fields_values` (`id`, `client_id`, `fieldFio`, `fieldTelephone`, `fieldEmail`, `field_4`, `field_5`, `field_6`) VALUES
(5, 1, 'Новый контакт', '', '', '1', '1', '');

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `adres` varchar(255) DEFAULT NULL,
  `email_1` varchar(255) DEFAULT NULL,
  `email_2` varchar(255) DEFAULT NULL,
  `phone_1` varchar(255) DEFAULT NULL,
  `phone_2` varchar(255) DEFAULT NULL,
  `site` varchar(255) DEFAULT NULL,
  `vk_profile` varchar(255) DEFAULT NULL,
  `icq` varchar(255) DEFAULT NULL,
  `skype` varchar(255) DEFAULT NULL,
  `description` text,
  `question` text,
  `creation_date` datetime NOT NULL,
  `change_client_date` datetime DEFAULT NULL,
  `responsable_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `priority_id` int(11) NOT NULL,
  `source_id` int(11) DEFAULT NULL,
  `goal_id` int(11) DEFAULT NULL,
  `city_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=3276 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `clients`
--

INSERT INTO `clients` (`id`, `name`, `adres`, `email_1`, `email_2`, `phone_1`, `phone_2`, `site`, `vk_profile`, `icq`, `skype`, `description`, `question`, `creation_date`, `change_client_date`, `responsable_id`, `creator_id`, `priority_id`, `source_id`, `goal_id`, `city_id`, `group_id`) VALUES
(1, 'Новый контакт', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-07-05 11:44:21', '2019-07-05 11:45:00', 1, 1, 1, 28, NULL, 29, 33);

-- --------------------------------------------------------

--
-- Структура таблицы `clients_cityes`
--

CREATE TABLE `clients_cityes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=3276 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `clients_cityes`
--

INSERT INTO `clients_cityes` (`id`, `name`) VALUES
(29, 'Нет города'),
(30, 'Москва'),
(31, 'Пермь'),
(32, 'Санкт-Петербуг'),
(33, 'Екатеринбург'),
(34, 'Новосибирск');

-- --------------------------------------------------------

--
-- Структура таблицы `clients_files`
--

CREATE TABLE `clients_files` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `clients_goals`
--

CREATE TABLE `clients_goals` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=3276 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `clients_goals`
--

INSERT INTO `clients_goals` (`id`, `name`) VALUES
(20, 'Нет цели'),
(21, 'Создать интерес'),
(22, 'Заключить сделку'),
(23, 'Новые сделки');

-- --------------------------------------------------------

--
-- Структура таблицы `clients_groups`
--

CREATE TABLE `clients_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=3276 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `clients_groups`
--

INSERT INTO `clients_groups` (`id`, `name`) VALUES
(33, 'Главная группа'),
(34, 'Потенциальные контакты'),
(35, 'Юр. лица'),
(36, 'Постоянные контакты'),
(37, 'Партнеры'),
(43, 'Нет интереса'),
(44, 'Архив');

-- --------------------------------------------------------

--
-- Структура таблицы `clients_priority`
--

CREATE TABLE `clients_priority` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=3276 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `clients_priority`
--

INSERT INTO `clients_priority` (`id`, `name`, `color`) VALUES
(1, 'Средний', 'green'),
(2, 'Важный', 'red'),
(3, 'Низкий', 'gray');

-- --------------------------------------------------------

--
-- Структура таблицы `clients_sources`
--

CREATE TABLE `clients_sources` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=3276 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `clients_sources`
--

INSERT INTO `clients_sources` (`id`, `name`) VALUES
(28, 'Нет источника'),
(29, 'Сайт'),
(30, 'Соц. сети'),
(31, 'SEO'),
(32, 'Листовки'),
(33, 'Доска объявлений');

-- --------------------------------------------------------

--
-- Структура таблицы `deals`
--

CREATE TABLE `deals` (
  `id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `client_id` int(11) NOT NULL,
  `deal_category_id` int(11) NOT NULL,
  `deal_priority_id` int(11) NOT NULL,
  `deal_status_id` int(11) NOT NULL,
  `responsable_id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `paid` decimal(20,2) NOT NULL,
  `balance` decimal(20,2) NOT NULL,
  `description` text
) ENGINE=InnoDB AVG_ROW_LENGTH=3276 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `deals`
--

INSERT INTO `deals` (`id`, `text`, `client_id`, `deal_category_id`, `deal_priority_id`, `deal_status_id`, `responsable_id`, `creation_date`, `paid`, `balance`, `description`) VALUES
(1, 'Новая сделка', 1, 4, 1, 1, 1, '2019-07-05 11:53:08', '0.00', '0.00', '');

-- --------------------------------------------------------

--
-- Структура таблицы `deals_categories`
--

CREATE TABLE `deals_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=3276 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `deals_categories`
--

INSERT INTO `deals_categories` (`id`, `name`) VALUES
(4, 'Нет категории'),
(5, 'Категория сделки #1'),
(6, 'Категория сделки #2'),
(7, 'Категория сделки #3');

-- --------------------------------------------------------

--
-- Структура таблицы `deals_files`
--

CREATE TABLE `deals_files` (
  `id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `deals_priority`
--

CREATE TABLE `deals_priority` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=3276 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `deals_priority`
--

INSERT INTO `deals_priority` (`id`, `name`, `color`) VALUES
(1, 'Средний', 'green'),
(2, 'Важный', 'white'),
(3, 'Низкий', 'gray');

-- --------------------------------------------------------

--
-- Структура таблицы `deals_statuses`
--

CREATE TABLE `deals_statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=3276 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `deals_statuses`
--

INSERT INTO `deals_statuses` (`id`, `name`) VALUES
(1, 'Потенциальная'),
(2, 'В обработке'),
(3, 'Сделка заключена'),
(4, 'Нет результата');

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `date_upload` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `labels`
--

CREATE TABLE `labels` (
  `id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `color_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `labels`
--

INSERT INTO `labels` (`id`, `type_id`, `name`, `color`, `color_text`) VALUES
(1, 1, 'рассказать о нас', '#0092CE', '#ffffff'),
(2, 1, 'нужен договор', '#EF9300', '#ffffff'),
(3, 1, 'важный контакт', '#C90036', '#ffffff'),
(4, 1, 'скидки и акции', '#A295D4', '#ffffff'),
(5, 1, 'контакт оплатил', '#60B860', '#ffffff'),
(6, 2, 'звонок', '#D75152', '#ffffff'),
(7, 2, 'перезвонить', '#7F8C8D', '#ffffff'),
(8, 2, 'отправить на почту', '#458CC8', '#ffffff'),
(9, 2, 'выезд к контакту', '#EEAC56', '#ffffff'),
(10, 2, 'связаться позже', '#34495D', '#ffffff'),
(11, 3, 'принимает решение', '#AC8C48', '#ffffff'),
(12, 3, 'договор согласован', '#CE0069', '#ffffff'),
(13, 3, 'ожидается оплата', '#118512', '#ffffff'),
(14, 3, 'отказ в покупке', '#7F8C8D', '#ffffff'),
(15, 3, 'в архиве', '#34495D', '#ffffff');

-- --------------------------------------------------------

--
-- Структура таблицы `labels_in_actions`
--

CREATE TABLE `labels_in_actions` (
  `id` int(11) NOT NULL,
  `label_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `labels_in_actions`
--

INSERT INTO `labels_in_actions` (`id`, `label_id`, `action_id`) VALUES
(1, 6, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `labels_in_clients`
--

CREATE TABLE `labels_in_clients` (
  `id` int(11) NOT NULL,
  `label_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `labels_in_deals`
--

CREATE TABLE `labels_in_deals` (
  `id` int(11) NOT NULL,
  `label_id` int(11) NOT NULL,
  `deal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `labels_in_deals`
--

INSERT INTO `labels_in_deals` (`id`, `label_id`, `deal_id`) VALUES
(1, 11, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `labels_type`
--

CREATE TABLE `labels_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `labels_type`
--

INSERT INTO `labels_type` (`id`, `name`) VALUES
(2, 'actions'),
(1, 'clients'),
(3, 'deals');

-- --------------------------------------------------------

--
-- Структура таблицы `range_ip`
--

CREATE TABLE `range_ip` (
  `id` int(11) NOT NULL,
  `begin_ip` int(10) UNSIGNED NOT NULL,
  `end_ip` int(10) UNSIGNED NOT NULL,
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text
) ENGINE=InnoDB AVG_ROW_LENGTH=8192 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('admin', 1, NULL, NULL, NULL),
('director', 2, NULL, NULL, NULL),
('manager', 3, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `scheduler`
--

CREATE TABLE `scheduler` (
  `id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `period` varchar(10) NOT NULL,
  `module` varchar(255) NOT NULL,
  `task` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `last_run` date NOT NULL,
  `active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `scheduler`
--

INSERT INTO `scheduler` (`id`, `order`, `period`, `module`, `task`, `data`, `last_run`, `active`) VALUES
(1, 1, '1d', 'system', 'clearNoActivatedUser', '[]', '2018-07-28', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `sector_in_groups`
--

CREATE TABLE `sector_in_groups` (
  `id` int(11) NOT NULL,
  `sector_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `param` varchar(255) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `param`, `value`) VALUES
(5, 'sizeFile', '25'),
(6, 'extFile', 'png, jpg, jpeg, bmp, tif, gif, svg, rar, zip, pdf, docx, xlsx, doc, xls, txt, ppt'),
(7, 'timeZone', 'Europe/Moscow');

-- --------------------------------------------------------

--
-- Структура таблицы `steps`
--

CREATE TABLE `steps` (
  `id` int(11) NOT NULL,
  `steps_type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL,
  `selected_default` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `steps`
--

INSERT INTO `steps` (`id`, `steps_type_id`, `name`, `weight`, `selected_default`) VALUES
(1, 1, 'Нет воронки', 1, 0),
(2, 2, 'Нет воронки', 1, 0),
(3, 1, 'Воронка по контактам', 2, 1),
(4, 2, 'Воронка по сделкам', 2, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `steps_in_clients`
--

CREATE TABLE `steps_in_clients` (
  `id` int(11) NOT NULL,
  `clients_id` int(11) NOT NULL,
  `steps_id` int(11) NOT NULL,
  `selected_option_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `steps_in_clients`
--

INSERT INTO `steps_in_clients` (`id`, `clients_id`, `steps_id`, `selected_option_id`) VALUES
(5, 1, 3, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `steps_in_deals`
--

CREATE TABLE `steps_in_deals` (
  `id` int(11) NOT NULL,
  `deals_id` int(11) NOT NULL,
  `steps_id` int(11) NOT NULL,
  `selected_option_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `steps_in_deals`
--

INSERT INTO `steps_in_deals` (`id`, `deals_id`, `steps_id`, `selected_option_id`) VALUES
(1, 1, 2, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `steps_options`
--

CREATE TABLE `steps_options` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL,
  `steps_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `steps_options`
--

INSERT INTO `steps_options` (`id`, `name`, `color`, `weight`, `steps_id`) VALUES
(1, 'Новый лид', '#5883A2', 1, 3),
(2, 'Первый контакт', '#458CC8', 2, 3),
(3, 'Переговоры', '#008996', 3, 3),
(4, 'Принятие решения', '#EEAC56', 4, 3),
(5, 'Согласование', '#E466AE', 5, 3),
(6, 'Успешно', '#118512', 6, 3),
(7, 'Нет результата', '#7F8C8D', 7, 3),
(8, 'Переговоры', '#5883A2', 1, 4),
(9, 'Договор', '#458CC8', 2, 4),
(10, 'Оплата поступила', '#008996', 3, 4),
(11, 'Нет результата', '#EEAC56', 4, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `steps_type`
--

CREATE TABLE `steps_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `steps_type`
--

INSERT INTO `steps_type` (`id`, `name`) VALUES
(1, 'clients'),
(2, 'deals');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `second_name` varchar(255) DEFAULT NULL,
  `patronymic` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `last_login` varchar(255) DEFAULT NULL,
  `last_ip` varchar(20) DEFAULT NULL,
  `reg_date` datetime NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=4096 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `password`, `first_name`, `second_name`, `patronymic`, `email`, `phone`, `country`, `city`, `last_login`, `last_ip`, `reg_date`, `status`, `parent_id`, `position`, `avatar`) VALUES
(1, 'e10adc3949ba59abbe56e057f20f883e', 'Иван Иванов', NULL, NULL, 'admin@inclient.ru', '', NULL, NULL, '2019-07-05 17:26:23', '127.0.0.1', '2019-01-09 09:52:39', 'active', 1, '', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users_files`
--

CREATE TABLE `users_files` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users_groups`
--

INSERT INTO `users_groups` (`id`, `name`) VALUES
(1, 'Главная группа'),
(2, 'Общий доступ');

-- --------------------------------------------------------

--
-- Структура таблицы `users_roles`
--

CREATE TABLE `users_roles` (
  `itemname` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bizrule` text,
  `data` text
) ENGINE=InnoDB AVG_ROW_LENGTH=4096 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users_roles`
--

INSERT INTO `users_roles` (`itemname`, `user_id`, `bizrule`, `data`) VALUES
('admin', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user_in_groups`
--

CREATE TABLE `user_in_groups` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_in_groups`
--

INSERT INTO `user_in_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_right`
--

CREATE TABLE `user_right` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_client` tinyint(1) NOT NULL DEFAULT '0',
  `create_action` tinyint(1) NOT NULL DEFAULT '0',
  `delete_action` tinyint(1) NOT NULL DEFAULT '0',
  `create_deals` tinyint(1) NOT NULL DEFAULT '0',
  `delete_deals` tinyint(1) NOT NULL DEFAULT '0',
  `create_field` tinyint(1) NOT NULL DEFAULT '0',
  `delete_field` tinyint(1) NOT NULL DEFAULT '0',
  `delete_section` tinyint(1) NOT NULL DEFAULT '0',
  `create_label_clients` tinyint(1) NOT NULL DEFAULT '0',
  `create_label_actions` tinyint(1) NOT NULL DEFAULT '0',
  `create_label_deals` tinyint(1) NOT NULL DEFAULT '0',
  `delete_label_clients` tinyint(1) NOT NULL DEFAULT '0',
  `delete_label_actions` tinyint(1) NOT NULL DEFAULT '0',
  `delete_label_deals` tinyint(1) NOT NULL DEFAULT '0',
  `add_files_client` tinyint(1) NOT NULL DEFAULT '0',
  `add_files_action` tinyint(1) NOT NULL DEFAULT '0',
  `add_files_deal` tinyint(1) NOT NULL DEFAULT '0',
  `add_files_user` tinyint(1) NOT NULL DEFAULT '0',
  `delete_files_client` tinyint(1) NOT NULL DEFAULT '0',
  `delete_files_action` tinyint(1) NOT NULL DEFAULT '0',
  `delete_files_deal` tinyint(1) NOT NULL DEFAULT '0',
  `delete_files_user` tinyint(1) NOT NULL DEFAULT '0',
  `create_steps` tinyint(1) NOT NULL DEFAULT '0',
  `delete_steps` tinyint(1) NOT NULL DEFAULT '0',
  `delete_client` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_right`
--

INSERT INTO `user_right` (`id`, `user_id`, `create_client`, `create_action`, `delete_action`, `create_deals`, `delete_deals`, `create_field`, `delete_field`, `delete_section`, `create_label_clients`, `create_label_actions`, `create_label_deals`, `delete_label_clients`, `delete_label_actions`, `delete_label_deals`, `add_files_client`, `add_files_action`, `add_files_deal`, `add_files_user`, `delete_files_client`, `delete_files_action`, `delete_files_deal`, `delete_files_user`, `create_steps`, `delete_steps`, `delete_client`) VALUES
(72, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `version`
--

CREATE TABLE `version` (
  `id` int(11) NOT NULL,
  `version` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `version`
--

INSERT INTO `version` (`id`, `version`) VALUES
(4, '1.0.2.5');

--
-- Структура таблицы `clients_notes`
--

CREATE TABLE `clients_notes` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `edited` int(11) DEFAULT NULL,
  `edit_user_id` int(11) DEFAULT NULL,
   `color` INT(5) NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `actions`
--
ALTER TABLE `actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_actions_client_id` (`client_id`),
  ADD KEY `IDX_actions_action_type_id` (`action_type_id`),
  ADD KEY `IDX_actions_action_status_id` (`action_status_id`),
  ADD KEY `IDX_actions_action_priority_id` (`action_priority_id`),
  ADD KEY `IDX_actions_responsable_id` (`responsable_id`);

--
-- Индексы таблицы `actions_files`
--
ALTER TABLE `actions_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action_id` (`action_id`),
  ADD KEY `file_id` (`file_id`);

--
-- Индексы таблицы `actions_priority`
--
ALTER TABLE `actions_priority`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `actions_statuses`
--
ALTER TABLE `actions_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `actions_types`
--
ALTER TABLE `actions_types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `activations`
--
ALTER TABLE `activations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `IDX_activations_user_id` (`user_id`);

--
-- Индексы таблицы `additional_fields`
--
ALTER TABLE `additional_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`);

--
-- Индексы таблицы `additional_fields_section`
--
ALTER TABLE `additional_fields_section`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `additional_fields_values`
--
ALTER TABLE `additional_fields_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Индексы таблицы `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_clients_group_id` (`group_id`),
  ADD KEY `IDX_clients_city_id` (`city_id`),
  ADD KEY `IDX_clients_goal_id` (`goal_id`),
  ADD KEY `IDX_clients_source_id` (`source_id`),
  ADD KEY `IDX_clients_priority_id` (`priority_id`),
  ADD KEY `IDX_clients_responsable_id` (`responsable_id`),
  ADD KEY `IDX_clients_creator_id` (`creator_id`);

--
-- Индексы таблицы `clients_cityes`
--
ALTER TABLE `clients_cityes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `clients_files`
--
ALTER TABLE `clients_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `file_id` (`file_id`);

--
-- Индексы таблицы `clients_goals`
--
ALTER TABLE `clients_goals`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `clients_groups`
--
ALTER TABLE `clients_groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `clients_priority`
--
ALTER TABLE `clients_priority`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `clients_sources`
--
ALTER TABLE `clients_sources`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `deals`
--
ALTER TABLE `deals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_deals_client_id` (`client_id`),
  ADD KEY `IDX_deals_deal_category_id` (`deal_category_id`),
  ADD KEY `IDX_deals_deal_status_id` (`deal_status_id`),
  ADD KEY `IDX_deals_deal_priority_id` (`deal_priority_id`),
  ADD KEY `IDX_deals_responsable_id` (`responsable_id`);

--
-- Индексы таблицы `deals_categories`
--
ALTER TABLE `deals_categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `deals_files`
--
ALTER TABLE `deals_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deal_id` (`deal_id`),
  ADD KEY `file_id` (`file_id`);

--
-- Индексы таблицы `deals_priority`
--
ALTER TABLE `deals_priority`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `deals_statuses`
--
ALTER TABLE `deals_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `labels`
--
ALTER TABLE `labels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`);

--
-- Индексы таблицы `labels_in_actions`
--
ALTER TABLE `labels_in_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `label_id` (`label_id`),
  ADD KEY `action_id` (`action_id`);

--
-- Индексы таблицы `labels_in_clients`
--
ALTER TABLE `labels_in_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `label_id` (`label_id`);

--
-- Индексы таблицы `labels_in_deals`
--
ALTER TABLE `labels_in_deals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deal_id` (`deal_id`),
  ADD KEY `label_id` (`label_id`) USING BTREE;

--
-- Индексы таблицы `labels_type`
--
ALTER TABLE `labels_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `range_ip`
--
ALTER TABLE `range_ip`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`name`);

--
-- Индексы таблицы `scheduler`
--
ALTER TABLE `scheduler`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sector_in_groups`
--
ALTER TABLE `sector_in_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sector_id_2` (`sector_id`,`group_id`),
  ADD KEY `sector_id` (`sector_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `steps`
--
ALTER TABLE `steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `steps_type` (`steps_type_id`);

--
-- Индексы таблицы `steps_in_clients`
--
ALTER TABLE `steps_in_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clients_id` (`clients_id`),
  ADD KEY `steps_id` (`steps_id`);

--
-- Индексы таблицы `steps_in_deals`
--
ALTER TABLE `steps_in_deals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clients_id` (`deals_id`),
  ADD KEY `steps_id` (`steps_id`);

--
-- Индексы таблицы `steps_options`
--
ALTER TABLE `steps_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `steps_id` (`steps_id`);

--
-- Индексы таблицы `steps_type`
--
ALTER TABLE `steps_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UK_users_id` (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Индексы таблицы `users_files`
--
ALTER TABLE `users_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `file_id` (`file_id`);

--
-- Индексы таблицы `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `users_roles`
--
ALTER TABLE `users_roles`
  ADD PRIMARY KEY (`itemname`,`user_id`),
  ADD KEY `IDX_users_roles_user_id` (`user_id`),
  ADD KEY `itemname` (`itemname`);

--
-- Индексы таблицы `user_in_groups`
--
ALTER TABLE `user_in_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_2` (`user_id`,`group_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Индексы таблицы `user_right`
--
ALTER TABLE `user_right`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_2` (`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `version`
--
ALTER TABLE `version`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `clients_notes`
--
ALTER TABLE `clients_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clients_notes_ibfk_1` (`client_id`),
  ADD KEY `clients_notes_ibfk_2` (`user_id`),
  ADD KEY `clients_notes_ibfk_3` (`edit_user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `actions`
--
ALTER TABLE `actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `actions_files`
--
ALTER TABLE `actions_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `actions_priority`
--
ALTER TABLE `actions_priority`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `actions_statuses`
--
ALTER TABLE `actions_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `actions_types`
--
ALTER TABLE `actions_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `activations`
--
ALTER TABLE `activations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `additional_fields`
--
ALTER TABLE `additional_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `additional_fields_section`
--
ALTER TABLE `additional_fields_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `additional_fields_values`
--
ALTER TABLE `additional_fields_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `clients_cityes`
--
ALTER TABLE `clients_cityes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT для таблицы `clients_files`
--
ALTER TABLE `clients_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `clients_goals`
--
ALTER TABLE `clients_goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `clients_groups`
--
ALTER TABLE `clients_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT для таблицы `clients_priority`
--
ALTER TABLE `clients_priority`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `clients_sources`
--
ALTER TABLE `clients_sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT для таблицы `deals`
--
ALTER TABLE `deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `deals_categories`
--
ALTER TABLE `deals_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `deals_files`
--
ALTER TABLE `deals_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `deals_priority`
--
ALTER TABLE `deals_priority`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `deals_statuses`
--
ALTER TABLE `deals_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `labels`
--
ALTER TABLE `labels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `labels_in_actions`
--
ALTER TABLE `labels_in_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `labels_in_clients`
--
ALTER TABLE `labels_in_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `labels_in_deals`
--
ALTER TABLE `labels_in_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `labels_type`
--
ALTER TABLE `labels_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `range_ip`
--
ALTER TABLE `range_ip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `scheduler`
--
ALTER TABLE `scheduler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `sector_in_groups`
--
ALTER TABLE `sector_in_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `steps`
--
ALTER TABLE `steps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `steps_in_clients`
--
ALTER TABLE `steps_in_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `steps_in_deals`
--
ALTER TABLE `steps_in_deals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `steps_options`
--
ALTER TABLE `steps_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `steps_type`
--
ALTER TABLE `steps_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `users_files`
--
ALTER TABLE `users_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `user_in_groups`
--
ALTER TABLE `user_in_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `user_right`
--
ALTER TABLE `user_right`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT для таблицы `version`
--
ALTER TABLE `version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- AUTO_INCREMENT для таблицы `clients_notes`
--
ALTER TABLE `clients_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `actions`
--
ALTER TABLE `actions`
  ADD CONSTRAINT `FK_actions_action_priority_id` FOREIGN KEY (`action_priority_id`) REFERENCES `actions_priority` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_actions_action_status_id` FOREIGN KEY (`action_status_id`) REFERENCES `actions_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_actions_action_type_id` FOREIGN KEY (`action_type_id`) REFERENCES `actions_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_actions_client_id` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_actions_responsable_id` FOREIGN KEY (`responsable_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `actions_files`
--
ALTER TABLE `actions_files`
  ADD CONSTRAINT `actions_files_ibfk_1` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `actions_files_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `activations`
--
ALTER TABLE `activations`
  ADD CONSTRAINT `FK_activations_users_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `additional_fields`
--
ALTER TABLE `additional_fields`
  ADD CONSTRAINT `additional_fields_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `additional_fields_section` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `additional_fields_values`
--
ALTER TABLE `additional_fields_values`
  ADD CONSTRAINT `additional_fields_values_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `FK_clients_city_id` FOREIGN KEY (`city_id`) REFERENCES `clients_cityes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_clients_creator_id` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_clients_goal_id` FOREIGN KEY (`goal_id`) REFERENCES `clients_goals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_clients_group_id` FOREIGN KEY (`group_id`) REFERENCES `clients_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_clients_priority_id` FOREIGN KEY (`priority_id`) REFERENCES `clients_priority` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_clients_responsable_id` FOREIGN KEY (`responsable_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_clients_source_id` FOREIGN KEY (`source_id`) REFERENCES `clients_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `clients_files`
--
ALTER TABLE `clients_files`
  ADD CONSTRAINT `clients_files_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clients_files_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `deals`
--
ALTER TABLE `deals`
  ADD CONSTRAINT `FK_deals_client_id` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_deals_deal_category_id` FOREIGN KEY (`deal_category_id`) REFERENCES `deals_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_deals_deal_priority_id` FOREIGN KEY (`deal_priority_id`) REFERENCES `deals_priority` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_deals_deal_status_id` FOREIGN KEY (`deal_status_id`) REFERENCES `deals_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_deals_responsable_id` FOREIGN KEY (`responsable_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `deals_files`
--
ALTER TABLE `deals_files`
  ADD CONSTRAINT `deals_files_ibfk_1` FOREIGN KEY (`deal_id`) REFERENCES `deals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `deals_files_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `labels`
--
ALTER TABLE `labels`
  ADD CONSTRAINT `labels_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `labels_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `labels_in_actions`
--
ALTER TABLE `labels_in_actions`
  ADD CONSTRAINT `labels_in_actions_ibfk_1` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `labels_in_actions_ibfk_2` FOREIGN KEY (`label_id`) REFERENCES `labels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `labels_in_clients`
--
ALTER TABLE `labels_in_clients`
  ADD CONSTRAINT `labels_in_clients_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `labels_in_clients_ibfk_2` FOREIGN KEY (`label_id`) REFERENCES `labels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `labels_in_deals`
--
ALTER TABLE `labels_in_deals`
  ADD CONSTRAINT `labels_in_deals_ibfk_1` FOREIGN KEY (`deal_id`) REFERENCES `deals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `labels_in_deals_ibfk_2` FOREIGN KEY (`label_id`) REFERENCES `labels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `sector_in_groups`
--
ALTER TABLE `sector_in_groups`
  ADD CONSTRAINT `sector_in_groups_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `users_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sector_in_groups_ibfk_2` FOREIGN KEY (`sector_id`) REFERENCES `additional_fields_section` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `steps`
--
ALTER TABLE `steps`
  ADD CONSTRAINT `steps_ibfk_1` FOREIGN KEY (`steps_type_id`) REFERENCES `steps_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `steps_in_clients`
--
ALTER TABLE `steps_in_clients`
  ADD CONSTRAINT `steps_in_clients_ibfk_1` FOREIGN KEY (`clients_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `steps_in_clients_ibfk_2` FOREIGN KEY (`steps_id`) REFERENCES `steps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `steps_in_deals`
--
ALTER TABLE `steps_in_deals`
  ADD CONSTRAINT `steps_in_deals_ibfk_1` FOREIGN KEY (`deals_id`) REFERENCES `deals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `steps_in_deals_ibfk_2` FOREIGN KEY (`steps_id`) REFERENCES `steps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `steps_options`
--
ALTER TABLE `steps_options`
  ADD CONSTRAINT `steps_options_ibfk_1` FOREIGN KEY (`steps_id`) REFERENCES `steps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_users_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users_files`
--
ALTER TABLE `users_files`
  ADD CONSTRAINT `users_files_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_files_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users_roles`
--
ALTER TABLE `users_roles`
  ADD CONSTRAINT `FK_users_roles_users_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_roles_ibfk_2` FOREIGN KEY (`itemname`) REFERENCES `roles` (`name`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_in_groups`
--
ALTER TABLE `user_in_groups`
  ADD CONSTRAINT `user_in_groups_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `users_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_in_groups_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_right`
--
ALTER TABLE `user_right`
  ADD CONSTRAINT `user_right_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


CREATE TABLE `accesses` (
`id` INT NOT NULL AUTO_INCREMENT ,
`name` VARCHAR(64) NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE = InnoDB;


ALTER TABLE `users` ADD `common_access` INT NOT NULL AFTER `avatar`;

ALTER TABLE `users` ADD INDEX(`common_access`);

INSERT INTO `accesses` (`id`, `name`) VALUES
(1, 'manager_responsible'),
(2, 'manager'),
(3, 'responsible'),
(4, 'director'),
(5, 'embargo'),
(6, 'all');

UPDATE `users` u inner join users_roles ur on u.id = ur.user_id SET `common_access`= 1  WHERE ur.itemname = 'manager';
UPDATE `users` u inner join users_roles ur on u.id = ur.user_id SET `common_access`= 4  WHERE ur.itemname = 'director';
UPDATE `users` u inner join users_roles ur on u.id = ur.user_id SET `common_access`= 6  WHERE ur.itemname = 'admin';

ALTER TABLE `users` ADD FOREIGN KEY (`common_access`) REFERENCES `accesses`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;


-- Ограничения внешнего ключа таблицы `clients_notes`
--
ALTER TABLE `clients_notes`
  ADD CONSTRAINT `clients_notes_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clients_notes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clients_notes_ibfk_3` FOREIGN KEY (`edit_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

-- выпуск 2.0.2.1
CREATE TABLE `deals_type` (
  `id` int(11) NOT NULL,
  `name` varchar (255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `deals_type`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

INSERT INTO `deals_type` (name) VALUES
('Активно'),
('Выиграно'),
('Проиграно');

ALTER TABLE `deals`
ADD COLUMN `change_date` datetime,
ADD COLUMN `closed_date` datetime,
ADD COLUMN `deal_type_id` int(11) DEFAULT 1,
ADD CONSTRAINT `FK_deals_type_id` FOREIGN KEY (`deal_type_id`) REFERENCES `deals_type`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;


CREATE TABLE `deals_reason` (
  `id` int(11) NOT NULL,
  `name` varchar (255) NOT NULL,
  `weight` int (11) NOT NULL,
  `is_default` int (11) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `deals_reason`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

INSERT INTO `deals_reason` (name, weight, is_default) VALUES
('Нет причины', 1, 1),
('Дорого', 2, 0),
('Не актуально', 3, 0),
('Неудобная оплата', 4, 0);


CREATE TABLE `deal_and_reason` (
  `id` int(11) NOT NULL,
  `deals_id` int(11) NOT NULL,
  `deals_reason_id` int (11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `deal_and_reason`
ADD PRIMARY KEY (`id`), MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1,
ADD CONSTRAINT `FK_deals_deal_and_reason_id` FOREIGN KEY (`deals_id`) REFERENCES `deals`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `FK_deals_reason_deal_and_reason_id` FOREIGN KEY (`deals_reason_id`) REFERENCES `deals_reason`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- выпуск 2.0.2.3

CREATE TABLE `appearance` (
`id` INT NOT NULL,
`menu_name` VARCHAR(256) NOT NULL,
`menu_link` VARCHAR(256) NULL,
`footer_name` VARCHAR(256) NULL,
`footer_link` VARCHAR(256) NULL,
`logo` VARCHAR(256) NOT NULL,
`background_image_type` VARCHAR(256) NOT NULL,
`background_image_type_value` VARCHAR(256) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `appearance`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

INSERT INTO `appearance` (menu_name, menu_link, footer_name, footer_link, logo, background_image_type, background_image_type_value) VALUES
('Инклиент | CRM', 'https://inclient.ru', '© 2022 Инклиент. Все права защищены', '', '/img/logo.svg', '', '');

CREATE TABLE `appearance_links` (
`id` INT NOT NULL,
`link_name` VARCHAR(256) NOT NULL,
`link_value` VARCHAR(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `appearance_links` (id, link_name, link_value) VALUES
(1, 'Об авторе', 'https://inclient.ru/about/'),
(2, 'Обновления срм', 'https://inclient.ru/category/update-crm/'),
(3, 'Хостинг для срм', 'https://www.hostland.ru/?r=7123f00e'),
(4, 'Справка', 'https://inclient.ru/category/help-crm/');

ALTER TABLE `appearance_links`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;


-- выпуск 1.0.2.4
create table client_filters
(
    id          int auto_increment primary key,
    author      int         not null,
    name        varchar(80) not null,
    who_visible varchar(10) not null,
    page_size   int(3)      not null,
    is_files    int(1)      not null,
    is_default  int(1)      not null,
    class_name  varchar(80)     null,
    constraint client_filters_ibfk_1
        foreign key (author) references users (id)
            on update cascade on delete cascade
);

create index author
    on client_filters (author);

create table client_filters_block_type
(
    id   int auto_increment primary key,
    name varchar(10) not null
);

create table client_filters_block_additional_fields
(
    id                           int auto_increment
        primary key,
    client_filters_id            int not null,
    client_filters_block_type_id int not null,
    additional_fields_id         int not null,
    constraint client_filters_block_additional_fields_ibfk_1
        foreign key (client_filters_id) references client_filters (id)
            on update cascade on delete cascade,
    constraint client_filters_block_additional_fields_ibfk_2
        foreign key (additional_fields_id) references additional_fields (id)
            on update cascade on delete cascade,
    constraint client_filters_block_additional_fields_ibfk_3
        foreign key (client_filters_block_type_id) references client_filters_block_type (id)
            on update cascade on delete cascade
);

create index additional_fields_id
    on client_filters_block_additional_fields (additional_fields_id);

create index client_filters_block_type_id
    on client_filters_block_additional_fields (client_filters_block_type_id);

create index client_filters_id
    on client_filters_block_additional_fields (client_filters_id);


create table client_filters_block_info
(
    id                           int auto_increment
        primary key,
    client_filters_id            int              not null,
    client_filters_block_type_id int              not null,
    is_id_client                 int(1) default 0 not null,
    is_last_change               int(1) default 0 not null,
    is_create_date               int(1) default 0 not null,
    is_responsible               int(1) default 0 not null,
    is_step                      int(1) default 0 not null,
    is_option_step               int(1) default 0 not null,
    constraint client_filters_block_info_ibfk_1
        foreign key (client_filters_id) references client_filters (id)
            on update cascade on delete cascade,
    constraint client_filters_block_info_ibfk_2
        foreign key (client_filters_block_type_id) references client_filters_block_type (id)
            on update cascade on delete cascade
);

create index client_filters_block_type_id
    on client_filters_block_info (client_filters_block_type_id);

create index client_filters_id
    on client_filters_block_info (client_filters_id);

create table client_filters_labels
(
    id                int auto_increment
        primary key,
    client_filters_id int not null,
    labels_id         int not null,
    constraint client_filters_labels_ibfk_1
        foreign key (client_filters_id) references client_filters (id)
            on update cascade on delete cascade,
    constraint client_filters_labels_ibfk_2
        foreign key (labels_id) references labels (id)
            on update cascade on delete cascade
);

create index client_filters_id
    on client_filters_labels (client_filters_id);

create index labels_id
    on client_filters_labels (labels_id);

create table client_filters_responsibles
(
    id                int auto_increment
        primary key,
    client_filters_id int not null,
    users_id          int not null,
    constraint client_filters_responsibles_ibfk_1
        foreign key (client_filters_id) references client_filters (id)
            on update cascade on delete cascade,
    constraint client_filters_responsibles_ibfk_2
        foreign key (users_id) references users (id)
            on update cascade on delete cascade
);

create index client_filters_id
    on client_filters_responsibles (client_filters_id);

create index users_id
    on client_filters_responsibles (users_id);

INSERT INTO `client_filters`(`id`, `author`, `name`, `who_visible`, `page_size`, `is_files`, `class_name`) VALUES (1, 1, 'Все контакты', 'all', 30, 0, '');

INSERT INTO `client_filters_block_type`(`id`, `name`) VALUES (1, 'left'), (2, 'right');

create table client_filters_step_options
(
    id                int auto_increment
        primary key,
    client_filters_id int not null,
    steps_options_id  int null,
    constraint client_filters_step_options_ibfk_1
        foreign key (client_filters_id) references client_filters (id)
            on update cascade on delete cascade,
    constraint client_filters_step_options_ibfk_2
        foreign key (steps_options_id) references steps_options (id)
            on update cascade on delete cascade
);

create index client_filters_id
    on client_filters_step_options (client_filters_id);

create index steps_options_id
    on client_filters_step_options (steps_options_id);