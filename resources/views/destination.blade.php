<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Hotel Destinations</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="/bootstrap/css/dataTables.bootstrap.min.css">
        <!-- Datepicker -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <style>#example1_filter{display:none}</style>
    </head>
    <body>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Hotel
                    <small>Destinations</small>
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-4">Hotel Name & Destination: <input id="name" name="name" type="text"></div>
                    <div class="col-md-4">Date Range: <input id="from" name="from" type="text" size=10> and <input id="to" name="to" type="text" size=10></div>
                    <div class="col-md-4">Price Range: <input id="min" name="min" type="text" size=10> and <input id="max" name="max" type="text" size=10></div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <!-- /.box-header -->
                            <div class="box-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>City</th>
                                            <th>From</th> 
                                            <th>To</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($destination['hotels'] as $hotel) { ?>

                                        <?php foreach ($hotel['availability'] as $key=>$dt) { ?>
                                            <tr>
                                            <td>{{$hotel['name']}}</td>
                                            <td>{{$hotel['price']}}</td>
                                            <td>{{$hotel['city']}}</td>
                                            <td>{{date('Y/m/d', strtotime($dt['from']))}}</td>
                                            <td>{{date('Y/m/d', strtotime($dt['to']))}}</td>
                                            </tr>
                                            <?php } ?>
                                            
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>

                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->	

        <!-- jQuery 2.2.3 -->
        <script src="/bootstrap/js/jquery.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="/bootstrap/js/bootstrap.min.js"></script>
        <!-- DataTables -->
        <script src="/bootstrap/js/jquery.dataTables.min.js"></script>
        <script src="/bootstrap/js/dataTables.bootstrap.min.js"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js"></script>
        <!-- Datepicker -->
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<!-- Custom Javscript Functions -->
        <script src="/js/common.js"></script>
        
    </body>

</html>
