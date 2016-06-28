<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_controller_constructor'][] = array(
                                'class'    => 'Preload',
                                'function' => 'loadSettings',
                                'filename' => 'preload.php',
                                'filepath' => 'hooks'
                                );

$hook['post_controller_constructor'][] = array(
                                'class'    => 'Preload',
                                'function' => 'checkModule',
                                'filename' => 'preload.php',
                                'filepath' => 'hooks'
                                );

$hook['post_controller_constructor'][] = array(
                                'class'    => 'Preload',
                                'function' => 'loadLanguages',
                                'filename' => 'preload.php',
                                'filepath' => 'hooks'
                                );

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */