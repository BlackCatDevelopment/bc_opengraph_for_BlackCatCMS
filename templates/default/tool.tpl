<div id="mod_bc_opengraph">
    <div class="fc_info">
    {translate('The Open Graph protocol enables developers to integrate their pages into the social graph.')}
    {translate('BlackCat CMS can automatically add the appropriate META attributes to the header. (Such as title, description, etc.)')}<br />
    {translate('This module allows to manage the module specific implementations to retrieve the first image from the content for use with &lt;meta property="og:image" ... /&gt;')}<br />
    {translate('For WYSIWYG, the needed methods are included in the Core.')}
    </div><br />
    <h1>{translate('Manage existing implementations')}</h1>
    {if is_array($modules)}
    {translate('Module')}:<br />
    <ul>
        {foreach $modules mod}<li> <a href="{$CAT_ADMIN_URL}/admintools/tool.php?tool=bc_opengraph&amp;bc_og_edit={$mod}">{$mod}</a></li>{/foreach}
    </ul>
    {else}{translate('No existing implementations found')}{/if}<br /><br />

    {if $module_list}
    <h1>{translate('Import implementation')}</h1>
    <form action="{$CAT_ADMIN_URL}/admintools/tool.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="tool" value="bc_opengraph" />
        <label for="mod_id">{translate('Module')}</label>
        <select id="mod_id" name="mod_id">
            {foreach $module_list mod}
            <option value="{$mod.addon_id}">{$mod.name}</option>
            {/foreach}
        </select><br />
        <label for="bc_og_file">{translate('File to import')}</label>
        <input type="file" name="bc_og_file" />
        <input type="submit" name="bc_og_add" value="{translate('Import')}" />

    <h1>{translate('Add implementation')}</h1>
    {translate('Please note: Implementations are only needed for modules of function "page".')}<br /><br />
    <form action="{$CAT_ADMIN_URL}/admintools/tool.php" method="get">
        <input type="hidden" name="tool" value="bc_opengraph" />
        <label for="mod_id">{translate('Module')}</label>
        <select id="mod_id" name="mod_id">
            {foreach $module_list mod}
            <option value="{$mod.addon_id}">{$mod.name}</option>
            {/foreach}
        </select>
        <input type="submit" name="bc_og_add" value="{translate('Add')}" />
    </form>
    {else}
    <p>{translate('No (more) modules of type "page" available.')}</p>
    {/if}
</div>