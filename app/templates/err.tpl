{* Show error message for a field, 
    $fid contains fields identifier *}
{if $form->haveError($fid)}<div class="error">{$form->getError($fid)}</div>{/if}
