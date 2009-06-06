<p>
    <label for="{$name}">{$label}</label><br>
    <input type="{$type}" class="{$class}"
           id="{$name}" name="{$name}" value="{$object->$name}">
    {if $form->haveError($name)}<div class="error">{$form->getError($name)}</div>{/if}
</p>
