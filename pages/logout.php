<?php

session_start();
session_destroy();
?>
<script>
	localStorage.clear();
	localStorage.setItem('token', '');
	localStorage.removeItem('token');
	document.location.href="/";

</script>


<?php
