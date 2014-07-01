<?php

/**
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 3 of the License, or (at
 *   your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful, but
 *   WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 *   General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program; if not, see <http://www.gnu.org/licenses/>.
 *
 *   @author          Black Cat Development
 *   @copyright       2014, Black Cat Development
 *   @link            http://blackcat-cms.org
 *   @license         http://www.gnu.org/licenses/gpl.html
 *   @category        CAT_Modules
 *   @package         Open Graph Support Manager
 *
 */

if (defined('CAT_PATH')) {
    if (defined('CAT_VERSION')) include(CAT_PATH.'/framework/class.secure.php');
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php')) {
    include($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php');
} else {
    $subs = explode('/', dirname($_SERVER['SCRIPT_NAME']));    $dir = $_SERVER['DOCUMENT_ROOT'];
    $inc = false;
    foreach ($subs as $sub) {
        if (empty($sub)) continue; $dir .= '/'.$sub;
        if (file_exists($dir.'/framework/class.secure.php')) {
            include($dir.'/framework/class.secure.php'); $inc = true;    break;
        }
    }
    if (!$inc) trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
}

$parser->setPath(CAT_PATH.'/modules/bc_opengraph/templates/default');

global $skip;
$skip = array('wysiwyg','menu_link');

// ---------- Add ----------
if(CAT_Helper_Validate::sanitizeGet('bc_og_add'))
{
    $mod_id = CAT_Helper_Validate::sanitizeGet('mod_id');
    $addon  = CAT_Helper_Addons::getAddonByID($mod_id);
    if(is_array($addon) && isset($addon['directory']) && $addon['directory'] !== '')
    {
        if(file_exists(CAT_PATH.'/modules/'.$addon['directory'].'/getfirstimage.php'))
            CAT_Backend::getInstance()->print_error('File getfirstimage.php already exists!');
        $in  = fopen(CAT_PATH.'/modules/bc_opengraph/templates/default/getfirstimage.tpl','r');
        $tpl = fread($in, filesize(CAT_PATH.'/modules/bc_opengraph/templates/default/getfirstimage.tpl'));
        $tpl = str_replace('%%modulename%%',$addon['directory'],$tpl);
        fclose($in);
        $out = fopen(CAT_PATH.'/modules/'.$addon['directory'].'/getfirstimage.php','w');
        fwrite($out,$tpl);
        fclose($out);
    }
    $parser->output(
        'edit.tpl',
        array('code'=>$tpl)
    );
}
// ---------- Modify ----------
elseif(CAT_Helper_Validate::sanitizeGet('bc_og_edit'))
{
    echo bc_og_edit(CAT_Helper_Validate::sanitizeGet('bc_og_edit'));
}
// ---------- Save ----------
elseif(CAT_Helper_Validate::sanitizePost('bc_og_save'))
{
    $mod_id = CAT_Helper_Validate::sanitizePost('mod_id');
    $addon  = CAT_Helper_Addons::getAddonByID($mod_id);
    $code   = CAT_Helper_Validate::sanitizePost('code');
    if ( ( $result = CAT_Helper_Droplet::check_syntax($val->get('_POST','code')) ) === true )
    {
        $out = fopen(CAT_PATH.'/modules/'.$addon['directory'].'/getfirstimage.php','w');
        fwrite($out,$code);
        fclose($out);
    }
    else
    {
        echo $parser->get(
            'error.tpl',
            array('msg'=>'PHP syntax error! Unable to save the code!')
        ) . bc_og_edit($addon['directory'],$code);
    }
}
// ---------- Delete ----------
elseif(CAT_Helper_Validate::sanitizeGet('bc_og_del'))
{
    // in fact, we just rename the file
    $mod_id = CAT_Helper_Validate::sanitizeGet('bc_og_del');
    $addon  = CAT_Helper_Addons::getAddonDetails($mod_id);
    if(file_exists(CAT_PATH.'/modules/'.$addon['directory'].'/getfirstimage.php'))
        rename(CAT_PATH.'/modules/'.$addon['directory'].'/getfirstimage.php',CAT_PATH.'/modules/'.$addon['directory'].'/getfirstimage.bak');
    bc_og_show();
}
// ---------- Import ----------
elseif(isset($_FILES['bc_og_file']))
{
    $file  = CAT_Helper_Upload::getInstance($_FILES['bc_og_file']);
    $file->process(CAT_PATH.'/temp');
    $zip   = CAT_Helper_Zip::getInstance($file->file_dst_pathname);
    $files = $zip->extract();
    if(!$zip->errorCode() == 0)
        echo $parser->get(
            'error.tpl',
            array('msg'=>'Upload failed! ZIP error: '.$zip->errorInfo(1))
        );
    else
    {
        if(isset($files[0]) && $files[0]['stored_filename'] == 'getfirstimage.php')
        {
            copy($files[0]['filename'],CAT_PATH.'/modules/'.CAT_Helper_Validate::sanitizePost('mod_id').'/getfirstimage.php');
        }
        $file->clean();
        unlink($files[0]['filename']);
    }
    bc_og_show();
}
else
{
    bc_og_show();
}

function bc_og_show()
{
    global $parser, $skip;
    $modules = $module_list = array();
    // installed modules of type 'page'
    $module_list = CAT_Helper_Addons::get_addons(0,'module','page','name',true);
    // find existing getfirstimage.php files
    $files = CAT_Helper_Directory::getInstance(1)
             ->maxRecursionDepth(2)
             ->findFiles('getfirstimage.php',CAT_PATH.'/modules',CAT_PATH.'/modules/');
    if(count($files))
        foreach($files as $file)
            $modules[] = str_replace('/','',pathinfo($file,PATHINFO_DIRNAME));
    if(count($modules))
        foreach($module_list as $i => $mod)
            if(in_array($mod['directory'],$modules) || in_array($mod['directory'],$skip))
                unset($module_list[$i]);

    $parser->output(
        'tool.tpl',
        array( 'modules' => $modules, 'module_list' => $module_list )
    );
}

function bc_og_edit($dir,$code=NULL)
{
    global $parser;
    $addon = CAT_Helper_Addons::getAddonDetails($dir);
    if(is_array($addon) && isset($addon['directory']) && $addon['directory'] !== '')
    {
        if(!$code)
        {
            $in   = fopen(CAT_PATH.'/modules/'.$addon['directory'].'/getfirstimage.php','r');
            $code = fread($in, filesize(CAT_PATH.'/modules/'.$addon['directory'].'/getfirstimage.php'));
            fclose($in);
        }
        $js  = NULL;
        if(file_exists(CAT_PATH.'/modules/edit_area/include.php'))
        {
            include_once CAT_PATH.'/modules/edit_area/include.php';
            $js   = show_wysiwyg_editor('code', 'code', $code, '100%', '350px', false);
            $code = NULL;
        }
        return $parser->get(
            'edit.tpl',
            array('mod_id'=>$addon['addon_id'],'code'=>$code,'js'=>$js)
        );
    }
}