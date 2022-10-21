<?php
if (!defined('DOC_ROOT'))
{
	exit(0);
}
require_once DOC_ROOT.'/core/prolog.php';

include DOC_ROOT.'/template/header.php';

?><h1>Login</h1>
<form action="/login/" method="post">
	<input type="hidden" name="tk" value="<?=\Check24\Controller\User::getCurrent()->getCSRFToken()?>">
	<label for="login">
		<input type="text" name="login" value="" placeholder="Enter your login">
	</label>
	<label for="password">
		And password
		<input type="password" id="password" name="password">
	</label>
	<input type="button" data-action="login" id="login" value="Log In">
</form>
<?php
include DOC_ROOT.'/template/footer.php';
