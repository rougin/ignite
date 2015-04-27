<?php

/**
 * Ignite
 *
 * A simple PHP script that deletes unwanted files and folders then installs  
 * Combustor (a CRUD generator) for CodeIgniter 3 be ready for writing web applications.
 *
 * @author 	Rougin Gutib <rougingutib@gmail.com>
 * @link 	https://github.com/rougin/ignite.php
 */

/**
 * ---------------------------------------------------------------------------------------------
 * Contents of CodeIgniter.php
 * ---------------------------------------------------------------------------------------------
 */

$codeigniter_core = file_get_contents('system/core/CodeIgniter.php');

/**
 * ---------------------------------------------------------------------------------------------
 * Contents for the composer.json
 * ---------------------------------------------------------------------------------------------
 */

$composer =
'{
	"description" : "The CodeIgniter framework",
	"name" : "codeigniter/framework",
	"license": "MIT",
	"require": {
		"php": ">=5.2.4",
		"rougin/combustor": "dev-master"
	}
}';

/**
 * ---------------------------------------------------------------------------------------------
 * Files and folder to be deleted
 * ---------------------------------------------------------------------------------------------
 */

$files_to_be_deleted = array(
	'.gitignore',
	'.travis.yml',
	'composer.json',
	'contributing.md',
	'DCO.txt',
	'license.txt',
	'license_afl.txt',
	'phpdoc.dist.xml',
	'readme.rst'
);

$folders_to_be_deleted = array(
	'tests',
	'user_guide_src'
);

/**
 * Remove the directory including its files
 * 
 * @param  string $dir
 */
function remove_directory($dir)
{
	if ( ! is_dir($dir))
	{
		return 0;
	}

	$objects = scandir($dir);
	
	foreach ($objects as $object)
	{
		if ($object == '.' || $object == '..')
		{
			continue;
		}

		if (filetype($dir . '/' . $object) == 'dir')
		{
			remove_directory($dir . '/' . $object);
		}
		else
		{
			unlink($dir . '/' . $object);
		}
	}

	reset($objects);
	rmdir($dir);
}

/**
 * ---------------------------------------------------------------------------------------------
 * Deleting unwanted files
 * ---------------------------------------------------------------------------------------------
 */

echo 'Deleting unwanted files... ', PHP_EOL;

foreach ($files_to_be_deleted as $file)
{
	if (file_exists($file))
	{
		echo '  Deleting ', $file, '... ', PHP_EOL;
		
		unlink($file);
	}
}

/**
 * ---------------------------------------------------------------------------------------------
 * Deleting unwanted folders
 * ---------------------------------------------------------------------------------------------
 */

echo 'Deleting unwanted folders... ', PHP_EOL;

foreach ($folders_to_be_deleted as $folder)
{
	if (is_dir($folder))
	{
		echo '  Deleting ', $folder, '... ', PHP_EOL;
		
		remove_directory($folder);
	}
}

/**
 * ---------------------------------------------------------------------------------------------
 * Adding the Composer either in autoload.php (in 3.0dev) or in the index.php
 * ---------------------------------------------------------------------------------------------
 */

if (strpos($codeigniter_core, 'define(\'CI_VERSION\', \'3.0') === FALSE)
{
	$index = file_get_contents('index.php');
	
	if (strpos($index, 'include_once \'vendor/autoload.php\';') === FALSE)
	{
		$search   = ' * LOAD THE BOOTSTRAP FILE';
		$replace  = ' * LOAD THE COMPOSER AUTOLOAD FILE' . "\n";
		$replace .= ' * --------------------------------------------------------------------' . "\n";
		$replace .= ' */' . "\n";
		$replace .= 'include_once \'vendor/autoload.php\';' . "\n";
		$replace .= '/*' . "\n";
		$replace .= ' * --------------------------------------------------------------------' . "\n";
		$replace .= ' * LOAD THE BOOTSTRAP FILE';

		$index = str_replace($search, $replace, $index);

		$file = fopen('index.php', 'wb');
		file_put_contents('index.php', $index);
		fclose($file);
	}
}
else
{
	$config = file_get_contents('application/config/config.php');

	$search  = '$config[\'composer_autoload\'] = FALSE;';
	$replace = '$config[\'composer_autoload\'] = \'vendor/autoload.php\';';

	$config = str_replace($search, $replace, $config);

	$file = fopen('application/config/config.php', 'wb');
	file_put_contents('application/config/config.php', $config);
	fclose($file);
}

/**
 * ---------------------------------------------------------------------------------------------
 * Install Composer and its dependencies
 * ---------------------------------------------------------------------------------------------
 */

if ( ! file_exists('composer.json'))
{
	$file = fopen('composer.json', 'wb');
	file_put_contents('composer.json', $composer);
	fclose($file);
}

system('composer install');

echo 'Combustor is now integrated to CodeIgniter!', PHP_EOL;