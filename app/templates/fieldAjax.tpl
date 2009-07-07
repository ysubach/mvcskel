<div>
    <label for="{$name}">{$label}</label><br>
    <input type="{$type}" class="{$class}"
           id="{$name}" name="{$name}" 
           value="{$object->$name}">
    <div class="error" id="error-{$name}" style="display:none;"></div>
</div>
