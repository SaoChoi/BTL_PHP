<!DOCTYPE html>
<html>

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="Style\WebTemplate.css">
        <link rel="stylesheet" type="text/css" href="FontAwesome\css\all.css">
        <link rel="icon" href="Images\Logo.PNG" type="image/x-icon">
        <link rel="stylesheet" href="./Style/style.css">
        <link rel="stylesheet" href="./Style/page/index.css">
        <link rel="stylesheet" href="./bootstrap/css//bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <title>Home</title>
        <?php
        session_start();
        require __DIR__ . '.\common\configdb.php';
        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $database);
        mysqli_set_charset($conn, "utf8");
        // Check connection
        if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
        exit();
        }

        if ($_SESSION['isLogin']) {
                $sql = "SELECT s.*, COUNT(p.StockId) AS TotalProduct FROM stock s RIGHT JOIN product p ON s.Id = p.StockId GROUP BY s.Id";
                echo $sql;
                $result = mysqli_query($conn, $sql);
            } else {
                echo '<script language ="javascript">alert("Phiên đăng nhập hết hạn");</script>';
                header("Location: index.php");
            }
        

        $sqlProduct = "SELECT * from product p;";
        $dataProduct = mysqli_query($conn, $sqlProduct);

        mysqli_close($conn);
        ?>
</head>

<body>
        <div id="container">
                <div id="menu">
                        <ul>
                                <li><a href="TrangChu.php"><img src="Images\Logo.PNG" alt="Company's Logo"></a></li>
                                <li><a href="TrangChu.php" class="active"><i class="fa fa-home"></i> Home</a></li>
                                <li><a href="NhaPhanPhoi.php"><i class="fa fa-industry"></i> Supplier</a></li>
                                <li><a href="HangHoa.php"><i class="fa fa-clipboard-list"></i> Products</a></li>
                                <li><a href="Kho.php"><i class="fa fa-arrow-alt-circle-down"></i> Warehouse</a></li>
                                <li><a href="Nhap.php"><i class="fa fa-arrow-alt-circle-down"></i> Import/ Export</a></li>
                                <li><a href="Logout.php"><i class="fa fa-sign-out-alt"></i> Sign out</a></li>
                        </ul>
                </div>

                <div class="content">
                        <div class="grid-item" style="    padding-top: 110px;">
                                <canvas id="myChart" width="400" height="100"></canvas>
                        </div>
                        <div class="grid-item" style="width: 100%;margin-left: 150px; width: 500px; display: flex; justify-content: center;">
                                <canvas id="productChart" width="400" height="100"></canvas>
                        </div>


                </div>


        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="./js/trangchu.js"></script>
        <script>
                var data = []
                <?php while ($item = mysqli_fetch_assoc($result)) {
                        echo  "data.push(" . json_encode($item) . ");";
                }   ?>

                setData(data)

                var dataProduct = []

                <?php while ($itemProduct = mysqli_fetch_assoc($dataProduct)) {
                        echo  "dataProduct.push(" . json_encode($itemProduct) . ");";
                }   ?>
                setProductChart(dataProduct)
        </script>
</body>

</html>