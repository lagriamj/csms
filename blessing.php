<?php
session_start();
require_once 'php/connection.php';
try {
    $pdo = new PDO(DSN, DB_USR, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare(
        "SELECT * FROM schedule_list
            WHERE
            (Status = 'For Schedule' or Status = 'For Verification') and cancel_delete is null
            "
    );
    $stmt->execute();
    $total = $stmt->rowCount();
    $stmt = $pdo->prepare(
        "SELECT * FROM requested_document
            WHERE
            request_status = 'For Received'
            "
    );
    $stmt->execute();
    $totaldoc = $stmt->rowCount();
} catch (PDOExeption $e) {
    echo $e->getMessage();
}
//echo $sample2;
$pdo = null;
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/styless.css">
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blessing</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link href="css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/jquery.datetimepicker.css">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>


</head>
<style>
    body {
        font-family: Arial, sans-serif;
    }

    .form-box {
        border: 2px solid #ccc;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-left: 20px;
    }

    .form-title {
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: left;
        color: #333;
        position: relative;
        padding-bottom: 10px;

    }

    .form-title::before {
        content: '';
        position: absolute;
        width: 1208px;
        height: 4px;
        background-color: #007bff;
        /* Choose your desired color */
        bottom: 0;
        left: 0;
    }

    .form-title {
        font-size: 32px;
        font-family: 'Arial', sans-serif;
        /* Change the font-family here */
        font-weight: bold;
        margin-bottom: 20px;
        text-align: left;
        color: #333;
        position: relative;
        padding-bottom: 10px;
    }

    .btn-info.text-light:hover,
    .btn-info.text-light:focus {
        background: #000;
    }

    table,
    tbody,
    td,
    tfoot,
    th,
    thead,
    tr {
        border-color: powderblue !important;
        border-style: solid;
        border-width: 1px !important;
    }

    :root {
        --bs-success-rgb: 71, 222, 152 !important;
    }

    html,
    body {
        height: 100%;
        width: 100%;
        overflow: hidden;
        /* Hide scrollbars */
    }

    .dashboard-container {
        display: flex;
        flex-direction: row;
    }

    .sidebar {
        width: 290px;
    }

    .flashited {
        color: #f2f;
        -webkit-animation: flash linear 1s infinite;
        animation: flash linear 1s infinite;
    }

    @-webkit-keyframes flash {
        0% {
            opacity: 1;
        }

        50% {
            opacity: .1;
        }

        100% {
            opacity: 1;
        }
    }

    @keyframes flash {
        0% {
            opacity: 1;
        }

        50% {
            opacity: .1;
        }

        100% {
            opacity: 1;
        }
    }

    .short-btn {
        width: 15px;
        /* Adjust the width as needed */
        white-space: nowrap;
        /* Prevent text wrapping */
        overflow: hidden;
        /* Hide overflowed content */
        text-overflow: ellipsis;
        /* Display an ellipsis (...) when text overflows */
    }

    .custom-button {
        background-color: #8BECEC;
        color: black;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        font-weight: 800;
    }

    .custom-button:hover {
        background-color: #45a049;
    }
</style>

<body>
    <div class="main-container">
        <div class="dashboard-container">
            <div class="sidebar">
                <div class="company-logo">
                    <img src="image/logo.png" alt="Company Logo">
                </div>
                <div style="text-align: center;">
                    <?php
                    if (isset($_SESSION["user"]["firstName"])) {
                        echo '<h2 style="font-family: Helvetica, sans-serif;">Welcome ' . $_SESSION["user"]["firstName"] . '!</h2>';
                        echo '<p style="color: yellow; font-size: 12px; font-family: Helvetica, sans-serif;">You are logged in as a ' . $_SESSION["user"]["accountType"] . '</p>';
                    } else {
                        echo '<h2>Welcome Guest!</h2>';
                    }
                    ?>
                </div>
                <ul>
                    <hr style="border-top: 2px solid black;">
                    <li>
                        <a href="client_dashboard.php" class="dashboard-link"> <img src="image/dashboard.png" alt="Dashboard Logo" class="dashboard-img">Home</a>
                    </li>
                    <li>
                        <a href="services.php" class="dashboard-link"> <img src="image/services.png" alt="Services Logo" class="dashboard-img">Services</a>
                    </li>
                    <li>
                        <a href="request_docs.php" class="dashboard-link"> <img src="image/payments.png" alt="Docs Logo" class="dashboard-img">Documents</a>
                    </li>
                    </li>
                    <li>
                        <a href="transactions.php" class="dashboard-link"> <img src="image/transactions.png" alt="Docs Logo" class="dashboard-img">Transactions</a>
                    </li>

                    <li>
                        <a href="client_chatbot.php" class="dashboard-link"><img src="image/chatbot.png" alt="chatbot Logo" class="dashboard-img">Chatbot</a>
                    </li>
                </ul>
                <?php
                if (isset($_SESSION['user'])) { ?>
                    <button class="logout-button" onclick="window.location.href='logout.php'">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                <?php } else { ?>
                    <button class="logout-button" style="background-color:green" onclick="window.location.href='login.php'">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                <?php } ?>
                </a>
            </div>
            <div class="main-content">
                <div class="top-bar">
                    <div class="profile">
                        <span>BLESSING</span>
                        <div>
                            <?php if (@$_SESSION["user"]["accountType"] == 'Admin' and $total > '0') { ?>
                                <span class="fa fa-bell noti" style="color:red"><sup style="color:red;" class="flashited"><?php echo $total; ?></sup></span>
                            <?php } else {
                            }
                            if (!isset($_SESSION['user'])) { ?>
                                <img src="picture_data/profile.png" alt="Profile Image">
                            <?php    } else {
                            ?>
                                <img src="picture_data/<?php echo $_SESSION["user"]["picture_data"]; ?>" alt="Profile Image" id="profile">
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="container py-5" id="page-container">

                    <?php
                    if (!isset($_SESSION['user'])) { ?>
                        <h6 style="color:red"><b>Note:</b> You must log in to view and fill out this form.</h6>
                    <?php } else {
                    } ?>
                    <?php
                    if (isset($_SESSION['user'])) { ?>

                        <div class="container py-5" id="page-container">
                            <div class="col-sm-12">
                                <div class="form-box">
                                    <div class="form-title">BLESSING REQUEST FORM<img src="image/logo.png" alt="Company Logo" width="50" height="50" style="float: right;"></div>
                                    <a href="services.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Back</a>
                                    <form method="post" action="php/insert_blessing.php" enctype="multipart/form-data">
                                        <div>
                                            <div class="row">
                                                <h4><b>Blessings Information:</b></h4>
                                                <div class="col-sm-6">
                                                    <label for="fullname">Full Name:<span style="color:red"> *</span></label>
                                                    <input type="text" name="reserve_by" class="form-control" required placeholder="Reserve by (ex. Juan Cruz)" />
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="email">Email Address:<span style="color:red"> *</span></label>
                                                    <input type="text" name="email" class="form-control" required placeholder="(ex. juan@gmail.com)" />
                                                </div>
                                            </div> <br>

                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label for="mass_type_of_mass">Select Type of Blessing<span style="color:red"> *</span></label>
                                                    <select id="mass_type_of_mass" name="mass_type_of_mass" class="form-control" required>
                                                        <option value="For Living Person">For House</option>
                                                        <option value="For Sick Person">For Car/Motorcycle</option>
                                                        <option value="Others">Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="datedeath">Preferred Date & Time:<span style="color:red"> *</span></label>
                                                    <input type="text" id="date_and_time" name="date_of_event" class="form-control" required placeholder="Date & Time" />
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="contact_no">Contact Number:<span style="color:red"> *</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">+63</span>
                                                        <input type="tel" id="mobileNumber" name="contact_no" placeholder="XXXXXXXXX" pattern="^\d{10}$" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label for="eventLocation">Blessing Location:<span style="color:red"> *</span></label>
                                                    <input type="text" name="event_location" required placeholder="Location of Blessing (Specific Address)" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-sm-12 d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-lg custom-button">Submit</button>
                                                </div>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                    <?php } else {
                    } ?>
                </div>
                </form>


            </div>
        </div>

</body>

<script>
    $(document).ready(function() {
        $("#date_and_time").datetimepicker({
            format: 'Y-m-d g:i A',
            step: 30,
            timepicker: true,
            minDate: new Date(),
            allowTimes: [
                '06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00',
                '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30',
                '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00',
                '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00'
            ],
            onSelectDate: function(ct, $i) {
                var minTime = new Date();
                var maxTime = new Date();
                minTime.setHours(6);
                maxTime.setHours(20);
                if (ct.getHours() < 6 || (ct.getHours() == 20 && ct.getMinutes() > 0)) {
                    $i.val('');
                } else {
                    if (ct.getHours() < 6) {
                        $i.val('');
                    }
                }
            }
        });
    });
</script>


<script src="./js/my_script.js"></script>
<script src="./js/notification.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="js/jquery.datetimepicker.full.js"></script>


</html>