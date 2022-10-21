	</div>
<?=\Check24\Application\Page::getInstance()->getJS()?>
<script src="/template/js/script.js"></script>
<script>
	window.messageTk = '<?=\Check24\Controller\User::getCurrent()->getCSRFToken()?>';
</script>
	<div style="height: 100px;background-color: black; color: white; line-height: 100px; text-align: center;">Blog</div>
</body>
</html>