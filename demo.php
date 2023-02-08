<?php
$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$fixcode = 'FnF010';
$randomcode = substr(str_shuffle($chars), 0, 10);
$bcode = $fixcode.$randomcode;
echo $bcode;
?>

array("Item Accepted by Courier","Ready to Pickup","Picked-up","Collected","Shipped","In-Transit","Hold","Arrived At Destination","Delivered","Unsuccessfull Delivery Attempt/Return");

case '1':
echo "<span class='badge badge-pill badge-info'> Item <br> Accepted <br> by Courier</span>";
break;
case '2':
echo "<span class='badge badge-pill badge-primary'> Ready <br> to Pickup</span>";
break;
case '3':
echo "<span class='badge badge-pill badge-success'> Picked-up</span>";
break;
case '4':
echo "<span class='badge badge-pill badge-info'> Collected</span>";
break;
case '5':
echo "<span class='badge badge-pill badge-info'> Shipped</span>";
break;
case '6':
echo "<span class='badge badge-pill badge-primary'>In-Transit</span>";
break;
case '7':
echo "<span class='badge badge-pill badge-primary'>Hold</span>";
break;
case '8':
echo "<span class='badge badge-pill badge-primary'> Arrived at <br> Destination</span>";
break;
case '9':
echo "<span class='badge badge-pill badge-success'>Delivered</span>";
break;
case '10':
echo "<span class='badge badge-pill badge-danger'> Unsuccessfull <br> Delivery <br> Attempt/Return</span>";
break;