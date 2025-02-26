<?php
    $data = './Data/history.xml';
    $xml = simplexml_load_file($data) or die("Error: Cannot create object");
    if ($xml === false) {
        die("Error: Cannot load XML file.");
    }
    $histories_array = [];
    if (isset($xml->history)) {
        foreach($xml->history as $history) {
            $history_data = [];
            foreach($history->children() as $key => $value) {
                $history_data[$key] = (string)$value;
            }
            $histories_array[] = $history_data;
        }
    }
    if (isset($_POST['delete_order'])) {
            $order_id = $_POST['order_id'];
            $xml = simplexml_load_file($data);
            if ($xml === false) {
                die("Error: Cannot load XML file.");
            }
            foreach ($xml->history as $order) {
                if ((string) $order->order_id === $order_id) {
                    $dom = dom_import_simplexml($order);
                    $dom->parentNode->removeChild($dom);
                    break;
                }
            }
            $xml->asXML($data);
            header("Location: http://localhost/orderSystem/history.php");
            exit();
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./Style/history.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <title>History</title>
</head>
<body>
    <header>
        <h1>MARSERVE</h1>
    </header>
    <div id="container">
        <a href="http://localhost/orderSystem/cashier.php" class="material-symbols-outlined arrow">arrow_back</a>
        <div class="h">
            <h2>Order History:</h2>
            <?php
            foreach ($histories_array as $order_data) {
                echo "
                <div id='card-container'>
                    <p class='table-number'>Table {$order_data['table_number']}</p>
                    <p class='table-orders'>Orders: {$order_data['total_orders']}</p>
                    <p class='table-bill'>Bill: {$order_data['total_bill']} php</p>
                    <button id='delete-button' data-aydi='{$order_data['order_id']}'>Delete</button>
                    <dialog id='delete-dialog-{$order_data['order_id']}'>
                        <form method='post'>
                            <input type='hidden' name='order_id' value='{$order_data['order_id']}' />
                            <p>Are you sure you want to delete this order?</p>
                            <button type='button' id='cancel'>No</button>
                            <button type='submit' name='delete_order'>Yes</button>
                        </form>
                    </dialog>
                </div>";
            }
            ?>
        </div>
    </div>
    <script>
        // let lastData = '';
        // function checkForChanges() {
        //     fetch('./Data/orders.xml')
        //         .then(response => response.text())
        //         .then(data => {
        //             if (data !== lastData) {
        //                 window.location.reload();
        //             }
        //             lastData = data;
        //         })
        //         .catch(error => {
        //             console.error('Error:', error);
        //         });
        // }
        // function startLongPolling() {
        //     setInterval(checkForChanges, 3000);
        // }
        // window.addEventListener('load', () => {
        //     startLongPolling();
        // });
        let dogs = document.querySelectorAll('#delete-button');
        dogs.forEach(element => {
            element.addEventListener('click', () => {
                let dialog = document.querySelector(`#delete-dialog-${element.getAttribute('data-aydi')}`);
                dialog.showModal();
            });
        });
        document.querySelectorAll('#cancel').forEach(element => {
            element.addEventListener('click', () => {
                element.closest('dialog').close();
            });
        });
    </script>
</body>
</html>