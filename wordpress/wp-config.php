<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать файл в "wp-config.php"
 * и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки базы данных
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры базы данных: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'shop' );

/** Имя пользователя базы данных */
define( 'DB_USER', 'admin' );

/** Пароль к базе данных */
define( 'DB_PASSWORD', '06051971Gg' );

/** Имя сервера базы данных */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу. Можно сгенерировать их с помощью
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}.
 *
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными.
 * Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '!&g=iP`tPo`@8u3+:<lMk]FC3`9tAUa:Aj48-a&ey/rJ)26k$~Kv#D8PFh_DGxWJ' );
define( 'SECURE_AUTH_KEY',  'L=Tqs<5H(<5i3CjZ1goQcHb#BOXCAB<@-s2|&[_h_QTX=5*WBwwYU`M|)k>-gj9*' );
define( 'LOGGED_IN_KEY',    '>1t-41(9yt<[cF,s/h/9o`G?rmU&A_7{>*O#vb):|%Pas%4)Gb{w|m%|IDTq%W!@' );
define( 'NONCE_KEY',        'm6Yf=qcKw?v/rSkGNr<&~QdibhpUS`mgA+K+7oO[&aql`F07X;X@X?{&~oYEg;L]' );
define( 'AUTH_SALT',        'dn-h7Ke]G|SOVV92w_!K&b$HS)xAPKf7pJ:(:3Q7{#2:k2zL[GbLfI},oWWHFzmG' );
define( 'SECURE_AUTH_SALT', 'jZGO/Qzfv.M*KqAdiP)UoF;N+&8^b:.ig=}?W|IW@6vv6c1vh$ODK=q%2R7Qqha%' );
define( 'LOGGED_IN_SALT',   '(!#}vAO;TGn;E>~v=N[K$BX~,y5Ad4EMH6R+ZPt2MQ 2X9?(2?,JJ}><N3655cuE' );
define( 'NONCE_SALT',       's-=W)}g`1d^{bA,,T9zV?`M*tHH4=[>>9vU1b:Od+`?}:eLM<L$O7XSXCil6FGC@' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */



/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
