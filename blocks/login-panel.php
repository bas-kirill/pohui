<?php

echo <<<_END
<style>
    #login-panel {
        background-color: darkgray;
    }
</style>

<div id="login-panel">
    <div>Login</div>
    <div id="username-password-panel">
        <form id="login-form">
            Username: <input type="text">
            <br>
            Password: <input type="password">
        </form>
    </div>
    <div id="login-signup-panel">
        <button onclick="redirect('http://localhost:8888/web/signup.php')">Sign Up</button>
        <input type="submit" form="login-form" value="Login">
    </div>
</div>

<script>
    function redirect(url) {
        window.location.href = url;
    }
</script>
_END;

?>