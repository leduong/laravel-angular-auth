<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <title>
            Laravel
        </title>
        <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css"/>
        <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap-theme.css" rel="stylesheet" type="text/css"/>
        <link href="//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/0.4.15/toaster.min.css" rel="stylesheet" type="text/css"/>
        <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.5/leaflet.css" />
        <script src="http://cdn.leafletjs.com/leaflet-0.7.5/leaflet.js"></script>
        <style type="text/css">
        .page {
            padding: 15px;
        }
        .divider {
            margin-top: 5px;
        }
        #map { height: 300px; min-width: 300px; }
        </style>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body ng-controller="AppCtrl">
        <nav class="navbar navbar-default" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button class="navbar-toggle" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                <a class="navbar-brand" href="#">Laravel 5 RESTful</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="#/users">Users</a>
                    </li>
                    <li ng-hide="isLogged()">
                        <a href="#/signin">Sign in</a>
                    </li>
                    <li ng-hide="!isLogged()">
                        <a href="#/signout">Sign out</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>
        <div class="view-container">
            <section class="animate-fade-up" data-ng-view="" id="content">
            </section>
        </div>
        <toaster-container></toaster-container>
        <!-- Scripts -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.20/angular.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.20/angular-route.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.20/angular-cookies.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.20/angular-resource.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/0.4.15/toaster.min.js"></script>
        <!-- // <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.4/ui-bootstrap-tpls.min.js"></script> -->
        <script src="/js/app.js"></script>
        <!-- begin "user" -->
        <script src="js/user/controller.js"></script>
        <script src="js/user/route.js"></script>
        <script src="js/user/service.js"></script>
        <!-- end "user" -->
    </body>
</html>