<?php
ob_start();
/* Main page with two forms: sign up and log in */
require __DIR__ . '/../start.php';

session_start();
$kluby = $clubsManager->FindAllClubs();
$refPositions = $usersManager->FindAllRefereeRanks();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign-Up/Login Form</title>
    <?php include 'css/css.html'; ?>
</head>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) { //user logging in

        require 'login.php';

    } elseif (isset($_POST['register'])) { //user registering

        require 'register.php';

    }
}
?>
<body>
<div class="form">

    <ul class="tab-group">
        <li class="tab"><a href="#signup">Sign Up</a></li>
        <li class="tab active"><a href="#login">Log In</a></li>
    </ul>

    <div class="tab-content">

        <div id="login">
            <h1>Welcome Back!</h1>

            <form action="index.php" method="post" autocomplete="off">

                <div class="field-wrap">
                    <label>
                        Email Address<span class="req">*</span>
                    </label>
                    <input type="email" required autocomplete="off" name="email"/>
                </div>

                <div class="field-wrap">
                    <label>
                        Password<span class="req">*</span>
                    </label>
                    <input type="password" required autocomplete="off" name="password"/>
                </div>

                <p class="forgot"><a href="forgot.php">Forgot Password?</a></p>

                <button class="button button-block" name="login"/>
                Log In</button>

            </form>

        </div>

        <div id="signup">
            <h1>Sign Up for Free</h1>

            <form action="index.php" method="post" autocomplete="off">

                <div class="top-row">
                    <div class="field-wrap">
                        <label>
                            First Name<span class="req">*</span>
                        </label>
                        <input type="text" required autocomplete="off" name='firstname'/>
                    </div>

                    <div class="field-wrap">
                        <label>
                            Last Name<span class="req">*</span>
                        </label>
                        <input type="text" required autocomplete="off" name='lastname'/>
                    </div>
                </div>

                <div class="field-wrap">
                    <label>
                        Email Address<span class="req">*</span>
                    </label>
                    <input type="email" required autocomplete="off" name='email'/>
                </div>

                <!--doskinovat-->
                <div class="field-wrap">
                    <label class="active">
                        Klubov?? p????slu??nost<span class="req">*</span>
                    </label>

                    <select name="klub">
<?php
    $sql = "SELECT id, name FROM `kluby`";
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0)
    {
        while ($row = $result->fetch_assoc()) {
            echo "<option value=\"" . $row["id"] . "\">" . $row["name"] . "</option>";
        }
    }
    else
    {
    echo "0 results";
    }
    $mysqli->close();
?>
                    </select>
                </div>

                <div class="field-wrap">
                    <select name="pozice">
                        <option value="0">Jsem plaveck?? rozhod????</option>
                        <option value="1">Jsem vedouc??m klubu</option>
                    </select>
                    <label class="active">
                        Role<span class="req">*</span> </br>
                    </label>
                </div>

                <!--<div class="field-wrap-in-hundred">-->
                <div class="field-wrap">
<?php foreach ($refPositions as $refPosition): ?>
    <option value="<?= h($refPosition->id)?>"><?= h($refPosition->rank_name)?></option>
<?php endforeach; ?>
                    </select>
                    <label class="active">
                        Rozhodcovsk?? t????da<span class="req">*</span> </br>
                    </label>
                </div>


                <div class="field-wrap">
                    <label>
                        Set A Password<span class="req">*</span>
                    </label>
                    <input type="password" required autocomplete="off" name='password'/>
                </div>

                <button type="submit" class="button button-block" name="register"/>
                Register</button>

            </form>

        </div>

    </div><!-- tab-content -->

</div> <!-- /form -->
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

<script src="js/index.js"></script>

</body>
</html>
