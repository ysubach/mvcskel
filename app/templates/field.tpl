<div>
    <label for="{$name}">{$label}</label><br>
    <input type="{$type|default:'text'}" class="{$class|default:'text'}"
           id="{$name}" name="{$name}" 
           value="{$object->$name}">
    {if $form->haveError($name)}<div class="error">{$form->getError($name)}</div>{/if}
</div>
