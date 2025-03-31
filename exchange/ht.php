<?php
session_start();
$variables = array(
);
// Function to fetch the Doge to BTC exchange rate from CoinGecko API
function getExchangeRate() {
    if (!isset($_SESSION['dogeData'])) {
        
        $dogeData = json_decode(file_get_contents('https://api.coingecko.com/api/v3/simple/price?ids=dogecoin&vs_currencies=usd'), true);

        $_SESSION['dogeData'] = $dogeData;
    } else {
        $dogeData = $_SESSION['dogeData'];
    }

    if ( isset($dogeData['dogecoin']['usd'])) {
        return $dogeData['dogecoin']['usd'];
    } else {
        return null;
    }
}

// Function to convert Doge to BTC using the fetched exchange rate
function convertToDoge($btcAmount) {
    $exchangeRate = getExchangeRate();

    if ($exchangeRate !== null) {
        $dogeAmount = (0.95 * $btcAmount) * $exchangeRate;
        return number_format($dogeAmount, 2, '.', '');
    } else {
        return null;
    }
}


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $btcAmount = isset($_POST['btcAmount']) ? floatval($_POST['btcAmount']) : 0;

    if ($btcAmount > 0) {
        $dogeAmount = convertToDoge($btcAmount);

        sleep(1);

        $formSubmitted = true;
    } else {
        
        $incompleteForm = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Swap</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        h1 {
            color: #007bff;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #495057;
        }
        input {
            width: 352px;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ced4da;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        button {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
<h1>Crypto Swap</h1>
    <form id="hideIt" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" <?php if (isset($formSubmitted) && $formSubmitted) echo 'style="display: none;"'; ?>>
        <div class="form-group">
            <label for="btcAmount">Doge Amount:</label>
            <input type="number" name="btcAmount" id="btcAmount" placeholder="Doge Amount" step="100" min="1000" max="100000" require>
        </div>
        <button type="submit">Swap</button>
    </form>

    <?php if (isset($formSubmitted) && $formSubmitted): ?>
    <br>
    <div class="form-group">
        <label for="dogeAmount">USDT Amount:</label>
        <input type="text" id="dogeAmount" value="<?php echo $dogeAmount; ?>" readonly>
    </div>
			<p>Mentioned U GET</p>
<?php endif; ?>
</div>

</body>
</html>
