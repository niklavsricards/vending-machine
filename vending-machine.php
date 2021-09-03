<?php

function createProduct(string $name, int $price): stdClass {
    $product = new stdClass();
    $product->name = $name;
    $product->price = $price;
    return $product;
}

$products = [
    1 => createProduct('Black Coffee', 150),
    2 => createProduct('Latte', 170),
    3 => createProduct('Cappuccino', 180)
];

function displayAllProducts(array $products): void {
    echo "This is the list of available drinks: " . PHP_EOL;
    foreach ($products as $key => $product) {
        echo "{$key} | Product: {$product->name} | Price: {$product->price}" . PHP_EOL;
        echo "-----------------" . PHP_EOL;
    }
}

$customer = new stdClass();
$customer->money = [
    5 => 10,
    10 => 10,
    20 => 10,
    50 => 10,
    100 => 10,
    200 => 10
];

function displayWallet(stdClass $customer): void {
    foreach ($customer->money as $coin => $amount) {
        if ($amount <= 0) {
            $amount = 0;
        }

        if ($coin >= 100) {
            $money = $coin / 100;
            echo "$$ You have {$money} EUR coin x {$amount}" . PHP_EOL;
        } else {
            echo "$$ You have {$coin} CENT coin x {$amount}" . PHP_EOL;
        }
    }
}

echo "Add coins in the vending machine, then choose what you want to buy" . PHP_EOL;

$addingCoins = true;
$moneyAdded = 0;

while($addingCoins) {
    displayWallet($customer);

    $selection = (int) readline("Enter which coin (5 CENT/10 CENT and so on) you want to add: ");

    if (!isset($customer->money[$selection])) {
        echo "Invalid coin number" . PHP_EOL;
        continue;
    }

    if ($customer->money[$selection] === 0) {
        echo "You dont have this coin anymore!" . PHP_EOL;
        continue;
    }

    $moneyAdded += $selection;
    $customer->money[$selection] -= 1;

    $money = $moneyAdded / 100;
    echo "************************" . PHP_EOL;
    echo "Your balance added in vending machine = {$money} EUR" . PHP_EOL;
    echo "************************" . PHP_EOL;

    $input = readline("Do you want to add more money?: y/n ");
    if ($input === "y") {
        continue;
    } else if ($input === "n") {
        $addingCoins = false;
    } else {
        echo "Invalid option";
    }
}

$shopping = true;
$moneyToRefund= 0;


while ($shopping) {
    displayAllProducts($products);

    $selection = (int) readline("Enter which product (number) you want to add to basket: ");

    if (!isset($products[$selection])) {
        echo "Invalid product number";
        continue;
    }

    $price = $products[$selection]->price / 100;
    $money = $moneyAdded / 100;

    echo "You have to pay {$price} EUR" . PHP_EOL;
    echo "Your balance added in vending machine = {$money} EUR" . PHP_EOL;
    $shopping = false;
}

$return = $moneyAdded - $products[$selection]->price;

echo "The vending machine will return {$return} to your wallet" . PHP_EOL;


foreach (array_reverse(array_keys($customer->money)) as $coin) {
    $quantity = intdiv($return, $coin);

    if ($quantity > 0) {
        $customer->money[$coin] += $quantity;
        $return -= $coin * $quantity;
    }

    if ($return <= 0) break;
}

displayWallet($customer);