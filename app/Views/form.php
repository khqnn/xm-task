<!DOCTYPE html>

<head>

    <title>XM Trading</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
</head>

<body>


    <form name="basic-form" id="basic-form" class="row m-2">
        <?php $company_symbols = $data['company_symbols']; ?>
        <div class="col-sm">
            <select class="form-control" id="symbol" name="symbol">
                <?php for ($i = 0; $i < sizeof($company_symbols); $i++) {
                    $symbol =  $company_symbols[$i]['Symbol']; ?>

                    <option value=<?php echo $symbol ?>><?php echo $symbol; ?></option>

                <?php } ?>
            </select>
        </div>
        <div class="col-sm">
            <input class="form-control" type="text" id="email" name="email" placeholder="Email" required>
            <p id="email-error" class="text-danger"></p>

        </div>
        <div class="col-sm">

            <input class="form-control" type="date" id="date-from" name="datefrom">
        </div>
        <div class="col-sm">

            <input class="form-control" type="date" id="date-to" name="dateto">
        </div>
        <div class="col-sm">
            <button class="form-control btn btn-primary" id="submit-button" type="button">click</button>
        </div>

        <p class="text-danger" id="error-message"></p>
    </form>


    <div class="row m-2">
        <div class="col-sm">
            <canvas id="myChart" style="width:100%;max-width:600px"></canvas>


        </div>
        <div class="col-sm">
            <table id="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Open</th>
                        <th>High</th>
                        <th>Low</th>
                        <th>Close</th>
                        <th>Volume</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Date</th>
                        <th>Open</th>
                        <th>High</th>
                        <th>Low</th>
                        <th>Close</th>
                        <th>Volume</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


    <script type="text/javascript">
        $(document).ready(function() {
            $("form[name='basic-form']").validate({
                // Specify validation rules
                'rules': {
                    'date-from': {
                        'required': true,
                        'date': true
                    },
                    'date-to': {
                        'required': true,
                        'date': true
                    },

                    'email': {
                        'required': true,
                        'email': true
                    },
                    'symbol': {
                        'required': true
                    }
                },
                'messages': {
                    'email': "Please enter a valid email address",
                    'date-from': "Please enter a valid date",
                    'date-to': "Please enter a valid date",
                    'symbol': 'The symbol is required'
                },

            });
        });

        /**
         * Display empty graph and table
         */
        new Chart("myChart", {
            type: "bar"
        })
        $("#table").DataTable()


        /**
         * Populate data in chart and table
         */
        function displayChartAndTable(data) {

            const companyName = data['data']['company_details']['Company Name']
            const historicalData = data['data']['filtered_prices']


            var closeValues = [];
            var openValues = [];
            var xValues = [];
            const tableData = []
            for (let point of historicalData) {

                const date = new Date(point['date'] * 1000)
                const dateFormat = date.getFullYear() + '-' + date.getMonth() + '-' + date.getDate()


                tableData.push([dateFormat, point['open'], point['high'], point['low'], point['close'], point['volume']])
                xValues.push(dateFormat)
                closeValues.push(point['close'])
                openValues.push(point['open'])
            }


            $("#table").DataTable({
                destroy: true,
                data: tableData
            })


            new Chart("myChart", {
                type: "bar",
                data: {
                    labels: xValues,
                    datasets: [{
                            data: closeValues,
                            backgroundColor: '#FFB1C1',
                        },
                        {
                            data: openValues,
                            backgroundColor: '#9BD0F5',
                        }
                    ]
                },
                options: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: "Open and Closed prices for \" " + companyName + " \""
                    }
                }
            });


        }


        /**
         * Validate and process form
         */
        $("#submit-button").on('click', async ($args) => {


            if (!$("form[name='basic-form']").valid()) return

            const symbol = $("#symbol").val()
            const email = $("#email").val()
            const fromdate = $("#date-from").val()
            const todate = $("#date-to").val()

            $.ajax({
                url: "<?= site_url("api/process-form") ?>",
                type: "post",
                data: {
                    symbol,
                    email,
                    from_date: fromdate,
                    to_date: todate
                },
                success: function(response) {
                    $("#error-message").text('')
                    if (response.code != 200) {
                        $("#error-message").text(response.message)
                        return
                    }

                    displayChartAndTable(response)

                }
            });

        })
    </script>
</body>

</html>