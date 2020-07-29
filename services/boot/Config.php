<?php

/**
 * DATABASE ORACLE
 */

// *** PRODUÇÃO ***
// define("CONF_DB_OCI_HOST", "bparkto-cluster.bpark.com.br");
// define("CONF_DB_OCI_PORT", '1521');
// define("CONF_DB_OCI_USER", "silvino");
// define("CONF_DB_OCI_PASS", "silvino");
// define("CONF_DB_OCI_SERVICE_NAME", "bdbp");

// *** DESENVOLVIMENTO ***
define("CONF_DB_OCI_HOST", "dbclone.bpark.com.br");
define("CONF_DB_OCI_PORT", '1521');
define("CONF_DB_OCI_USER", "douglas_feijao");
define("CONF_DB_OCI_PASS", "douglas_feijao");
define("CONF_DB_OCI_SERVICE_NAME", "desenv");

/**
 * DATABASE MYSQL
 */
define("CONF_DB_HOST", "localhost");
define("CONF_DB_USER", "developer");
define("CONF_DB_PASS", "developer");
define("CONF_DB_NAME", "bp_cobranca");