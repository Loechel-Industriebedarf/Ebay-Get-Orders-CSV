@ECHO OFF
cd C:\eNVenta-ERP\BMECat\eBay\Debug
:loop
ECHO Executing script...
ECHO.
php\php.exe GetOrders.php
ECHO.
move ebayOrder.csv C:\eNVenta-ERP\BMECat\eBay
ECHO.
ECHO Waiting 30 Minutes...
ECHO.
ECHO.
ECHO.
TIMEOUT 1800
GOTO :loop