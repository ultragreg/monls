<?php include("composants/head.php"); ?>

<body>
    <!-- Page Content -->
    <div id="page-content-wrapper" class="page-login">
        <div class="login-widget text-center">
            <div class="account-wall">
                <form class="form-signin">
                    <h2 class="form-signin-heading">MonLS v2</h2>
                    <br>

                    <label class="sr-only" for="inputuCode">Login</label>
                    <input type="text" autofocus="" required="" placeholder="Login" class="form-control" id="efLoginConnexion">
                    <label class="sr-only" for="inputPassword">Mot de passe</label>
                    <input type="password" required="" placeholder="Mot de passe" class="form-control" id="efPassWordConnexion">

                    <label class="checkbox">
                        <input type="checkbox" value="remember-me" name="auto" id="chkAuto"> Se souvenir de moi
                    </label> 

                    <p  id="loginMessage">
                    </p>
                    <div role="group" aria-label="...">
                        <a class="btn btn-lg btn-primary" id="btnAnnulerConnexion">Annuler</a>
                        <button class="btn btn-lg btn-primary" id="btnConnexion">Connexion</button>
                    </div>
                  </form>                           
            </div>
        </div>
    </div>
    <!-- /#page-content-wrapper -->


    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Javascript MonLs -->
    <script src="../dist/js/monls.js"></script>

</body>

</html>
