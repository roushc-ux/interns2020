<style>
<?php include 'style.css';?>
</style>
<body>
    <div class="page-wrap">
        <div class="header">Login </div>
        <form class="form" action="/action_page.php">
            <label for="uname">Username: </label>
            <input type="text" id = "uname" name="uname"><br><br>
            <label for="password">Password: </label>
            <input type="text" id = "password" name="password"><br><br>
            <input type="button" onclick="login()" value = "Login">
        </form>
        <script>
            function login() {
                document.getElementById("form")
            }
        </script>
        <a href = "account.php"> Create New Account</a>
    </div>
    <script type="text/javascript" src="deck.js"></script>
</body>
