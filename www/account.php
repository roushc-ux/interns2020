<style>
    <?php include 'style.css';?>
</style>
<body>
<div class="page-wrap">
    <div class="header">Create New Account </div>
    <form class="form" action="/action_page.php">
        <label for="uname">New Username: </label>
        <input type="text" id = "uname" name="uname"><br><br>
        <label for="password">New Password: </label>
        <input type="text" id = "password" name="password"><br><br>
        <label for="email">New Email: </label>
        <input type="text" id = "email" name="email"><br><br>
        <input type="button" onclick="newUser()" value = "Create New Account">
    </form>
    <script>
        function newUser() {
            document.getElementById("form")
        }
    </script>
    <a href = "index.php"> Back</a>
</div>
</body>
