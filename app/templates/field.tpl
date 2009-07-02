<div>
    <label for="{$name}">{$label}</label><br>
    <input type="{$type}" class="{$class}"
           id="{$name}" name="{$name}" 
           value="{if $object->$name}{$object->$name}{/if}">
    {if $form->haveError($name)}<div class="error">{$form->getError($name)}</div>{/if}
</div>
