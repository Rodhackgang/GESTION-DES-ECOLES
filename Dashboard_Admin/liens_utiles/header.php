<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- PAGE TITLE HERE -->
    <?php
    // Obtenez le nom de la page actuelle sans l'extension .php
    $currentPage = basename($_SERVER['PHP_SELF'], ".php");
    ?>
    <title><?php echo $currentPage; ?></title>

    <link href="vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">
    <link rel="stylesheet" href="vendor/nouislider/nouislider.min.css">
    <!-- Style css -->
    <link href="css/style.css" rel="stylesheet">
</head>


<style>
    .dlabnav-scroll {
        max-height: 900px;
        /* Hauteur maximale du menu avant d'activer le scroll */
        overflow-y: auto;
        /* Activation du scroll vertical */
    }
</style>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="waviy">
            <span style="--i:1">C</span>
            <span style="--i:2">H</span>
            <span style="--i:3">A</span>
            <span style="--i:4">R</span>
            <span style="--i:5">G</span>
            <span style="--i:6">E</span>
            <span style="--i:7">M</span>
            <span style="--i:8">E</span>
            <span style="--i:9">N</span>
            <span style="--i:10">T</span>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="index.html" class="brand-logo">
                <svg class="logo-abbr" width="53" height="53" viewBox="0 0 53 53">
                    <path class="svg-logo-primary-path" d="M48.3418 41.8457H41.0957C36.8148 41.8457 33.332 38.3629 33.332 34.082C33.332 29.8011 36.8148 26.3184 41.0957 26.3184H48.3418V19.2275C48.3418 16.9408 46.4879 15.0869 44.2012 15.0869H4.14062C1.85386 15.0869 0 16.9408 0 19.2275V48.8594C0 51.1462 1.85386 53 4.14062 53H44.2012C46.4879 53 48.3418 51.1462 48.3418 48.8594V41.8457Z" fill="#5BCFC5" />
                    <path class="svg-logo-primary-path" d="M51.4473 29.4238H41.0957C38.5272 29.4238 36.4375 31.5135 36.4375 34.082C36.4375 36.6506 38.5272 38.7402 41.0957 38.7402H51.4473C52.3034 38.7402 53 38.0437 53 37.1875V30.9766C53 30.1204 52.3034 29.4238 51.4473 29.4238ZM41.0957 35.6348C40.2382 35.6348 39.543 34.9396 39.543 34.082C39.543 33.2245 40.2382 32.5293 41.0957 32.5293C41.9532 32.5293 42.6484 33.2245 42.6484 34.082C42.6484 34.9396 41.9532 35.6348 41.0957 35.6348Z" fill="#5BCFC5" />
                </svg>

                <p class="brand-title" width="124px" height="33px" style="font-size: 30px;">Admin</p>
            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>

        <!--**********************************
            Nav header end
        ***********************************-->



        <!--**********************************
            Header start
        ***********************************-->
   
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                
                                <?php
                               
                                // Obtenez le nom de la page actuelle sans l'extension .php
                                $currentPage = basename($_SERVER['PHP_SELF'], ".php");
                                ?>
                                <div><?php echo $currentPage; ?></div>
                            </div>
                        </div>
                        <ul class="navbar-nav header-right">
                            <li class="nav-item">
                                <div class="input-group search-area">
                                    <input type="text" class="form-control" placeholder="Recherche...">
                                    <span class="input-group-text"><a href="javascript:void(0)"><i class="flaticon-381-search-2"></i></a></span>
                                </div>
                            </li>
                           
                           
                           
                            <li class="nav-item">
                                <a href="javascript:void(0);" class="btn btn-primary d-sm-inline-block d-none">Koumbaya Production<i class="las la-signal ms-3 scale5"></i></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="dlabnav">
            <div class="dlabnav-scroll">
                <ul class="metismenu" id="menu">

                    <li><a href="./../../Dashboard_Admin/index.php" aria-expanded="false">
                            <i class="flaticon-025-dashboard"></i>
                            <span class="nav-text">Tableau de bord</span>
                        </a>
                    </li>
                    <li><a href="./../../Dashboard_Admin/anne.php" aria-expanded="false">
                            <i class="flaticon-025-dashboard"></i>
                            <span class="nav-text">Gestion années</span>
                        </a>
                    </li>
                    <li><a href="./../../Dashboard_Admin/gestion_eleves.php" aria-expanded="false">
                            <i class="flaticon-050-info"></i>
                            <span class="nav-text">Gestion des Élèves</span>
                        </a>
                    </li>
                    
                    <li><a href="./../../Dashboard_Admin/gestion_personnel.php" aria-expanded="false">
                            <i class="flaticon-041-graph"></i>
                            <span class="nav-text">Gestion Personnel</span>
                        </a>
                    </li>
                    <li><a href="./../../Dashboard_Admin/gestion_matiere.php" aria-expanded="false">
                            <i class="flaticon-086-star"></i>
                            <span class="nav-text">Gestion Matières</span>
                        </a>
                    </li>
                    <li><a href="./../../Dashboard_Admin/gestion_salle.php" aria-expanded="false">
                            <i class="flaticon-045-heart"></i>
                            <span class="nav-text">Gestion Salles</span>
                        </a>
                    </li>
                    <li><a href="./../../Dashboard_Admin/gestion_attribution.php" aria-expanded="false">
                            <i class="flaticon-050-info"></i>
                            <span class="nav-text">Gestion des attributions</span>
                        </a>
                    </li>
                    <li><a href="./../../Dashboard_Admin/gestion_note.php" class="ai-icon" aria-expanded="false">
                            <i class="flaticon-013-checkmark"></i>
                            <span class="nav-text">Gestion des Notes</span>
                        </a>
                    </li>
                    <li><a href="./../../Dashboard_Admin/gestions_emplois.php" aria-expanded="false">
                            <i class="flaticon-072-printer"></i>
                            <span class="nav-text">Gestion Emplois</span>
                        </a>
                    </li>
                    <li><a href="./../../Dashboard_Admin/messagerie.php" aria-expanded="false">
                            <i class="flaticon-043-menu"></i>
                            <span class="nav-text">Messagerie</span>
                        </a>
                    </li>
                    <li><a href="./../../Dashboard_Admin/gestion_evaluation.php" aria-expanded="false">
                            <i class="flaticon-022-copy"></i>
                            <span class="nav-text">Evaluations</span>
                        </a>
                    </li>
                    <li><a href="./../../Dashboard_Admin/suivis_abscence.php" aria-expanded="false">
                            <i class="flaticon-022-copy"></i>
                            <span class="nav-text">Suivi des Absences</span>
                        </a>
                    </li>
                    <li><a href="./../../Dashboard_Admin/gestion_bulletins.php" aria-expanded="false">
                            <i class="flaticon-022-copy"></i>
                            <span class="nav-text">Gestion Bulletins</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>