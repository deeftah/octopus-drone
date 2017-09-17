<?php
/**
 * Custom WordPress configurations on "wp-config.php" file.
 *
 * This file has the following configurations: MySQL settings, Table Prefix, Secret Keys, WordPress Language, ABSPATH and more.
 * For more information visit {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php} Codex page.
 * Created using {@link http://generatewp.com/wp-config/ wp-config.php File Generator} on GenerateWP.com.
 *
 * @package WordPress
 * @generator GenerateWP.com
 */


/* MySQL settings */
define( 'DB_NAME',     'octopusdrones' );
define( 'DB_USER',     'root' );
define( 'DB_PASSWORD', 'Msham83@' );
define( 'DB_HOST',     'localhost' );
define( 'DB_CHARSET',  'utf8mb4' );
define( 'DB_COLLATE',   'utf8mb4_general_ci');


/* MySQL database table prefix. */
$table_prefix = 'shop_';


/* Authentication Unique Keys and Salts. */
define('AUTH_KEY',         'OnlB-:]6sxMCQ2:z(VB/-both*(O|icPBz{tjA`cJEw7P]x5ZBD0.n9hKJaVmb&.');
define('SECURE_AUTH_KEY',  'X#:suH-kuW|ole}ZhCe_Yfbj{$S#ccj)y0wS8,wf++|?4#YNJKP3w=YC/`3+Vijs');
define('LOGGED_IN_KEY',    ')1>yG%Es:X:iETAJ&3,H7/YcJ.6]}|1JaJXqW]77F1-F=.%C.qn|02!& SV9Re6|');
define('NONCE_KEY',        'q bv=lSH?9}x?J0jx.h{8$/03rwuV{e/iIG |7z5qUkKa3rgfgz9gR|v**Y=ZMi2');
define('AUTH_SALT',        '^rjfCn|UqQj2(E2@sbtP1oe=pJurI`Vs5[d-F(]-3P|ZZL~O|:v.S9M+!oXh22c ');
define('SECURE_AUTH_SALT', '@-zP&FS&7-gM}8z{f 6IF,7{v4CM|!aviqNTW%sq^?BEp 6LLeJq%ue#.MW_>/Wm');
define('LOGGED_IN_SALT',   'xWMe_fs.FuN0,e[q-+fYX|IE_t:TD;`]_@qD/}ez;q& tdXd?E?B+CcCeoC*LSRN');
define('NONCE_SALT',       'N/ tLo.gyWI<)6ew5>OVRP5>pHwZo;/;,|+Mj46NZlw8eg-z2%XMLCNxxQ]![y>S');


/* Specify maximum number of Revisions. */
define( 'WP_POST_REVISIONS', '3' );
/* Media Trash. */
define( 'MEDIA_TRASH', true );

/* Language */
define( 'WPLANG', 'fr_FR' );

/* PHP Memory */


/* WordPress Cache */
define( 'WP_CACHE', true );

/* Compression */
define( 'COMPRESS_CSS',        true );
define( 'COMPRESS_SCRIPTS',    true );
define( 'CONCATENATE_SCRIPTS', true );
define( 'ENFORCE_GZIP',        true );

/* FTP */
// define( 'FS_METHOD', 'ssh2' );
// define( 'FTP_USER', 'devlcdr' );
// define( 'FTP_PASS', 'slicshig2' );
// define( 'FTP_HOST', 'lcdr.evolix.net' );
// define( 'FTP_SSL', false );

/* Updates */
define( 'WP_AUTO_UPDATE_CORE', true );
define( 'DISALLOW_FILE_EDIT', false );

define( 'JETPACK_DEV_DEBUG', true );

/* Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/* Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
