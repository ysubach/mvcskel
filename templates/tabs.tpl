<!--{strip}-->

<div class="tabs">
  <a <!--{if $context->getFirst() eq "Main"}-->class="selected"<!--{/if}--> href="<!--{url _name="Main"}-->">Home</a>
  <!--{if $__currentUser->haveAccess('Administrator')}-->
  |<a <!--{if $context->getFirst() eq "User"}-->class="selected"<!--{/if}--> href="<!--{url _name="User/Index"}-->">User Management</a>
  <!--{/if}-->
</div>

<div class="sub">
  <a href="">Sublink1</a>
  |<a href="">Sublink2</a>
  |<strong>Current Sublink</strong>
</div>

<!--{/strip}-->
