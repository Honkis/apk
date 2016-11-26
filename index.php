<?php namespace apkCalculator; ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Alkohol Per krona</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.4/paper/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<?php 
// Include apkSerivce
include_once('apkService.php');
$apk = new apkService;
?>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <img src="image/apk-mini-logo-vit.svg" class="apk-nav-logo" alt="Alkoholperkrona logotyp">
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <!-- Jumbotron Header -->
        <header class="jumbotron hero-spacer">
            <img src="image/apk-logo-vit.svg" class="apk-logo" alt="Alkoholperkrona logotyp">
            <p>Använd oss för att få ut mest av pengarna när bankkontot är tomt!</p>
            <p><a href="#apk-toplist" class="btn btn-primary btn-large">Se Toplistan</a>
            </p>
        </header>

        <hr>

        <!-- Title -->
        <div class="row">
            <div class="col-sm-8 col-xs-12">
                <h3>APK-listan</h3>
            </div>
            <div class="col-sm-4 col-xs-12">
                <select name="apk-category-select" class="apk-category-menu">
                    <option value="toplist">Alla</option>
                    <option value="öl">Öl</option>
                    <option value="cider">Cider</option>
                    <option value="sprit">Sprit</option>
                    <option value="blanddrycker">Blanddrycker</option>
                    <option value="rött vin">Rött vin</option>
                    <option value="vitt vin">Vitt vin</option>
                    <option value="rosé">Rosévin</option>
                    <option value="mousserande vin">Mousserande vin</option>
                    <option value="whisky">Whisky</option>
                    <option value="sake">Sake</option>
                    <option value="övrigt">Övrigt</option>
                </select>
            </div>
        </div>
        <!-- /.row -->

        <!-- apk toplist -->
        <div id="apk-toplist" class="row">
            <div class="col-lg-12 hero-feature">
                <div class="thumbnail">
                    <table class="table table-hover table-striped apk-table table-condensed">
                        <thead>
                            <tr>
                                <th class="apk-table-rank">#</th>
                                <th class="apk-table-name">Namn</th>
                                <th class="apk-table-percent">%</th>
                                <th class="apk-table-cost"><i class="fa fa-credit-card"></i></th>
                                <th class="apk-table-group">Varugrupp</th>
                                <th class="apk-table-packaging"><i class="fa fa-flask"></i></th>
                                <th class="apk-table-volume">Ml</th>
                                <th class="apk-table-apk">Apk</th>
                            </tr>
                        </thead>
                        <tbody class="apk-data-body">
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <!-- /.row -->
        <!-- load more button -->
        <div class="row">
            <div class="col-lg-12">
                <button type="button" class="btn btn-primary" id="apk-toplist__loadmorebtn">Se fler</button>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-sm-8 col-xs-12">
                <h3>Minst alkohol för pengarna</h3>
            </div>
        </div>
        <!-- apk toplist -->
        <div id="apk-worstlist" class="row">
            <div class="col-lg-12 hero-feature">
                <div class="thumbnail">
                    <table class="table table-hover table-striped apk-table2 table-condensed">
                        <thead>
                            <tr>
                                <th class="apk-table-rank">#</th>
                                <th class="apk-table-name">Namn</th>
                                <th class="apk-table-percent">%</th>
                                <th class="apk-table-cost"><i class="fa fa-credit-card"></i></th>
                                <th class="apk-table-group">Varugrupp</th>
                                <th class="apk-table-packaging"><i class="fa fa-flask"></i></th>
                                <th class="apk-table-volume">Ml</th>
                                <th class="apk-table-apk">Apk</th>
                            </tr>
                        </thead>
                        <tbody class="apk-data-body">
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; alkoholperkrona.ninja <?php echo date('Y'); ?></p>
                    <p>Utvecklat av Oscar West och Anton Honkavaara Dahl</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- APK custom -->
    <script src="js/apkcustom.js"></script>


</body>

</html>
