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

$module_description = 'Open Graph Support verwalten oder für Module, die das bisher nicht unterstützen, hinzufügen.';

$LANG = array(
    'Add implementation' => 'Lösung hinzufügen',
    'An error occured!' => 'Es ist ein Fehler aufgetreten!',
    'BlackCat CMS can automatically add the appropriate META attributes to the header. (Such as title, description, etc.)' => 'BlackCat CMS ist in der Lage, die entsprechenden META-Attribute im Seitenheader einzufügen.',
    'edit' => 'bearbeiten',
    'File to import' => 'Import-Datei',
    'For WYSIWYG, the needed methods are included in the Core.' => 'Für WYSIWYG sind die entsprechenden Methoden bereits im Core vorhanden.',
    'Import implementation' => 'Lösung importieren',
    'Manage existing implementations' => 'Bestehende Lösungen bearbeiten',
    'Module' => 'Modul',
    'No existing implementations found' => 'Keine bestehenden Lösungen gefunden',
    'No (more) modules of type "page" available.' => 'Keine Module des Typs "Seite" (mehr) verfügbar.',
    'Note: You may install the EditArea module to have this code syntax highlighted!' => 'Hinweis: Für Syntax Highlighting das Modul EditArea installieren!',
    'Please change the code only if you know what you are doing!' => 'Bitte den Code nur ändern wenn Sie sicher sind, dass Sie das Richtige tun!',
    'Please note: Implementations are only needed for modules of function "page".' => 'Hinweis: Lösungen sind nur für Module des Typs "Seite" (page) nötig.',
    'remove' => 'löschen',
    'The Open Graph protocol enables developers to integrate their pages into the social graph.' => 'Das Open Graph Protocol bietet Entwicklern Zugang zur Facebook-API.',
    'This module allows to manage the module specific implementations to retrieve the first image from the content for use with &lt;meta property="og:image" ... /&gt;'
        => 'Dieses Modul erlaubt die Verwaltung der modulspezifischen Lösungen zum Auslesen des ersten Bildes aus dem Inhalt, zur Verwendung in &lt;meta property="og:image" ... /&gt;',
);