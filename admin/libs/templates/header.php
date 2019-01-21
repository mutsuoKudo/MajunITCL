<?php

include (dirname(__FILE__) . "/../gl_logincheck.php");
//include('/libs/gl_logincheck.php');

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>エンジニアマッチングサイト管理システ</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/timeline.css" rel="stylesheet">
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- DataTables CSS -->
    <link href="libs/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="libs/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
</head>
<body>
<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="top.php">エンジニアマッチングサイト管理システム</a>
        </div>
        <!-- /.navbar-header -->
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
<!--                <a class="dropdown-toggle" data-toggle="dropdown" href="#">-->
<!--                    <i class="fa fa-envelope fa-fw"></i>  <i class="fa fa-caret-down"></i>-->
<!--                </a>-->
<!--                <ul class="dropdown-menu dropdown-messages">-->
<!--                    <li>-->
<!--                        <a href="#">-->
<!--                            <div>-->
<!--                                <strong>John Smith</strong>-->
<!--                                    <span class="pull-right text-muted">-->
<!--                                        <em>Yesterday</em>-->
<!--                                    </span>-->
<!--                            </div>-->
<!--                            <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="divider"></li>-->
<!--                    <li>-->
<!--                        <a href="#">-->
<!--                            <div>-->
<!--                                <strong>John Smith</strong>-->
<!--                                    <span class="pull-right text-muted">-->
<!--                                        <em>Yesterday</em>-->
<!--                                    </span>-->
<!--                            </div>-->
<!--                            <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="divider"></li>-->
<!--                    <li>-->
<!--                        <a href="#">-->
<!--                            <div>-->
<!--                                <strong>John Smith</strong>-->
<!--                                    <span class="pull-right text-muted">-->
<!--                                        <em>Yesterday</em>-->
<!--                                    </span>-->
<!--                            </div>-->
<!--                            <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="divider"></li>-->
<!--                    <li>-->
<!--                        <a class="text-center" href="#">-->
<!--                            <strong>Read All Messages</strong>-->
<!--                            <i class="fa fa-angle-right"></i>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                </ul>-->
                <!-- /.dropdown-messages -->
            </li>
            <!-- /.dropdown -->
            <li class="dropdown">
<!--                <a class="dropdown-toggle" data-toggle="dropdown" href="#">-->
<!--                    <i class="fa fa-tasks fa-fw"></i>  <i class="fa fa-caret-down"></i>-->
<!--                </a>-->
<!--                <ul class="dropdown-menu dropdown-tasks">-->
<!--                    <li>-->
<!--                        <a href="#">-->
<!--                            <div>-->
<!--                                <p>-->
<!--                                    <strong>Task 1</strong>-->
<!--                                    <span class="pull-right text-muted">40% Complete</span>-->
<!--                                </p>-->
<!--                                <div class="progress progress-striped active">-->
<!--                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">-->
<!--                                        <span class="sr-only">40% Complete (success)</span>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="divider"></li>-->
<!--                    <li>-->
<!--                        <a href="#">-->
<!--                            <div>-->
<!--                                <p>-->
<!--                                    <strong>Task 2</strong>-->
<!--                                    <span class="pull-right text-muted">20% Complete</span>-->
<!--                                </p>-->
<!--                                <div class="progress progress-striped active">-->
<!--                                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">-->
<!--                                        <span class="sr-only">20% Complete</span>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="divider"></li>-->
<!--                    <li>-->
<!--                        <a href="#">-->
<!--                            <div>-->
<!--                                <p>-->
<!--                                    <strong>Task 3</strong>-->
<!--                                    <span class="pull-right text-muted">60% Complete</span>-->
<!--                                </p>-->
<!--                                <div class="progress progress-striped active">-->
<!--                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">-->
<!--                                        <span class="sr-only">60% Complete (warning)</span>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="divider"></li>-->
<!--                    <li>-->
<!--                        <a href="#">-->
<!--                            <div>-->
<!--                                <p>-->
<!--                                    <strong>Task 4</strong>-->
<!--                                    <span class="pull-right text-muted">80% Complete</span>-->
<!--                                </p>-->
<!--                                <div class="progress progress-striped active">-->
<!--                                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">-->
<!--                                        <span class="sr-only">80% Complete (danger)</span>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="divider"></li>-->
<!--                    <li>-->
<!--                        <a class="text-center" href="#">-->
<!--                            <strong>See All Tasks</strong>-->
<!--                            <i class="fa fa-angle-right"></i>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                </ul>-->
                <!-- /.dropdown-tasks -->
            </li>
            <!-- /.dropdown -->
<!--            <li class="dropdown">-->
<!--                <a class="dropdown-toggle" data-toggle="dropdown" href="#">-->
<!--                    <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>-->
<!--                </a>-->
<!--                <ul class="dropdown-menu dropdown-alerts">-->
<!--                    <li>-->
<!--                        <a href="#">-->
<!--                            <div>-->
<!--                                <i class="fa fa-comment fa-fw"></i> New Comment-->
<!--                                <span class="pull-right text-muted small">4 minutes ago</span>-->
<!--                            </div>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="divider"></li>-->
<!--                    <li>-->
<!--                        <a href="#">-->
<!--                            <div>-->
<!--                                <i class="fa fa-twitter fa-fw"></i> 3 New Followers-->
<!--                                <span class="pull-right text-muted small">12 minutes ago</span>-->
<!--                            </div>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="divider"></li>-->
<!--                    <li>-->
<!--                        <a href="#">-->
<!--                            <div>-->
<!--                                <i class="fa fa-envelope fa-fw"></i> Message Sent-->
<!--                                <span class="pull-right text-muted small">4 minutes ago</span>-->
<!--                            </div>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="divider"></li>-->
<!--                    <li>-->
<!--                        <a href="#">-->
<!--                            <div>-->
<!--                                <i class="fa fa-tasks fa-fw"></i> New Task-->
<!--                                <span class="pull-right text-muted small">4 minutes ago</span>-->
<!--                            </div>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="divider"></li>-->
<!--                    <li>-->
<!--                        <a href="#">-->
<!--                            <div>-->
<!--                                <i class="fa fa-upload fa-fw"></i> Server Rebooted-->
<!--                                <span class="pull-right text-muted small">4 minutes ago</span>-->
<!--                            </div>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="divider"></li>-->
<!--                    <li>-->
<!--                        <a class="text-center" href="#">-->
<!--                            <strong>See All Alerts</strong>-->
<!--                            <i class="fa fa-angle-right"></i>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                </ul>-->
                <!-- /.dropdown-alerts -->
<!--            </li>-->
            <!-- /.dropdown -->
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
<!--                    <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>-->
<!--                    </li>-->
<!--                    <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>-->
<!--                    </li>-->
<!--                    <li class="divider"></li>-->
                    <li><a href="login.php?logoff=1"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-top-links -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
<!--                    <li class="sidebar-search">-->
<!--                        <div class="input-group custom-search-form">-->
<!--                            <input type="text" class="form-control" placeholder="Search..." value="">-->
<!--                                <span class="input-group-btn">-->
<!--                                <button class="btn btn-default" type="button">-->
<!--                                    <i class="fa fa-search"></i>-->
<!--                                </button>-->
<!--                            </span>-->
<!--                        </div>-->
<!--                    </li>-->
                    <li>
                        <a href="top.php"><i class="fa fa-dashboard fa-fw"></i> TOP</a>
                    </li>
                    <li>
                        <a href="main.php"><i class="fa fa-table fa-fw"></i> 案件一覧</a>
                    </li>
                    <li>
                        <a href="banner_list.php"><i class="fa fa-list-alt fa-fw"></i> バナー一覧</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-edit fa-fw"></i> データ登録<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="regit.php">案件登録</a>
                            </li>
                            <li>
                                <a href="banner.php">バナー登録</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-user fa-fw"></i> エンジニア管理<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
<!--                            <li>-->
<!--                                <a href="libs/csv_export.php">一覧CSVダウンロード</a>-->
<!--                            </li>-->
                            <li>
                                <a href="users.php">応募者一覧</a>
                            </li>
                            <li>
                                <a href="list.php">案件別応募者一覧</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-tags fa-fw"></i> マスタ管理<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="mdm.php?m=area">エリア</a>
                            </li>
                            <li>
                                <a href="mdm.php?m=lang">スキル１</a>
                            </li>
                            <li>
                                <a href="mdm.php?m=other2">スキル２</a>
                            </li>
                            <li>
                                <a href="mdm.php?m=type">業種</a>
                            </li>
                            <li>
                                <a href="mdm.php?m=other">その他</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-folder-open fa-fw"></i> CSV管理<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="libs/csv_export.php" id="csvex">CSVエクスポート</a>
                            </li>
                            <li>
                                <a href="csvimport.php">CSVインポート</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
<!--                    <li>-->
<!--                        <a href="#"><i class="fa fa-cogs fa-fw"></i> 管理機能<span class="fa arrow"></span></a>-->
<!--                        <ul class="nav nav-second-level">-->
<!--                            <li>-->
<!--                                <a href="flot.html">お知らせメール設定</a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </li>-->
                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>