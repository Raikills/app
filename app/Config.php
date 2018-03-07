<?php

/**
 * Config - the Global Configuration loaded BEFORE the Nova Application starts.
 *
 * @author Virgil-Adrian Teaca - virgil@giulianaeassociati.com
 * @version 3.0
 *
 */


/**
 * PREFER to be used in Database calls or storing Session data, default is 'nova_'
 */
define('PREFIX', 'nova_');

/**
 * Setup the Config API Mode.
 *
 * For using the 'database' mode, you need a database having the table 'nova_options'
 */
define('CONFIG_STORE', 'files'); // Supported: "files", "database"
