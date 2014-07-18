<div id="mod_bc_opengraph">
    <div class="fc_info">
    {translate('Please change the code only if you know what you are doing!')}
    {if $code}
    <br />{translate('Note: You may install the EditArea module to have this code syntax highlighted!')}
    {/if}
    </div>
    <form action="{$CAT_ADMIN_URL}/admintools/tool.php" method="post">
        <input type="hidden" name="tool" value="bc_opengraph" />
        <input type="hidden" name="mod_id" value="{$mod_id}" />
        
        <label for="code">{translate('Code')}:</label>
        {if $code}
        <textarea name="code">{$code}</textarea>
        {else}
        {$js}
        {/if}
        <br /><br />
        <input type="submit" name="bc_og_save" value="{translate('Save')}" />
        <input type="submit" name="bc_og_cancel" value="{translate('Cancel')}" />
    </form>
</div>
