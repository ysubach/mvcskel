<p>
    <label for="{$name}">{$label}</label><br/>
    <input type="{$type}" class="{$class}"
    id="{$name}" name="{$name}" value="{$object->$name}" />
    {include file='err.tpl' fid=$name}
</p>
