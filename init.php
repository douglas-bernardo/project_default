<?php

/**
 * APP no servidor
 * na raiz do projeto criar a pasta storage e sub-pasta sessions
 */

/**
 * DATES
 */
 define("CONF_DATE_BR", "d/m/Y H:i:s");
 define("CONF_DATE_APP", "Y-m-d H:i:s");


/**
 * SESSION
 */
define("CONF_SES_PATH", __DIR__ . "/storage/sessions/");


/**
 * PASSWORD
 */
define("CONF_PASSWD_MIN_LEN", 8);
define("CONF_PASSWD_MAX_LEN", 40);


/**
 * URL
 */
 define("CONF_URL_BASE", "http://localhost/project-default");
 define("CONF_URL_SERVICE", "http://localhost/project-default/");
 define("CONF_URL_CM_SERVICE", "http://localhost/wser_cm/");