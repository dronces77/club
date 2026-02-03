$results = Get-ChildItem -Path "C:\xampp\htdocs\medicalsoft" -Recurse -File | 
Select-String -Pattern "avatar-dr\.png"

if ($results) {
    $results | ForEach-Object {
        Write-Host "========================================" -ForegroundColor Cyan
        Write-Host "Archivo: $($_.Path)" -ForegroundColor Yellow
        Write-Host "Línea: $($_.LineNumber)" -ForegroundColor Green
        Write-Host "Contenido: $($_.Line)" -ForegroundColor White
    }
} else {
    Write-Host "No se encontró la cadena en ningún archivo." -ForegroundColor Red
}

http://localhost/

favicon.ico
<strong>CI4</strong>


$results = Get-ChildItem -Path "C:\xampp\htdocs\medicalsoft" -Recurse -File | 
Select-String -Pattern "Clientes por Institución"

if ($results) {
    $results | ForEach-Object {
        Write-Host "========================================" -ForegroundColor Cyan
        Write-Host "Archivo: $($_.Path)" -ForegroundColor Yellow
        Write-Host "Línea: $($_.LineNumber)" -ForegroundColor Green
        Write-Host "Contenido: $($_.Line)" -ForegroundColor White
    }
} else {
    Write-Host "No se encontró la cadena en ningún archivo." -ForegroundColor Red
}


$results = Get-ChildItem -Path "C:\xampp\htdocs\medicalsoft" -Recurse -File | 
Select-String -Pattern "logohospital"

if ($results) {
    $results | ForEach-Object {
        Write-Host "========================================" -ForegroundColor Cyan
        Write-Host "Archivo: $($_.Path)" -ForegroundColor Yellow
        Write-Host "Línea: $($_.LineNumber)" -ForegroundColor Green
        Write-Host "Contenido: $($_.Line)" -ForegroundColor White
    }
} else {
    Write-Host "No se encontró la cadena en ningún archivo." -ForegroundColor Red
}


EL ARCHIVO VENDOR
$results = Get-ChildItem -Path "C:\xampp\htdocs\pos" -Recurse -File | 
Select-String -Pattern "$_SESSION["categorias"] = "on";"

if ($results) {
    $results | ForEach-Object {
        Write-Host "========================================" -ForegroundColor Cyan
        Write-Host "Archivo: $($_.Path)" -ForegroundColor Yellow
        Write-Host "Línea: $($_.LineNumber)" -ForegroundColor Green
        Write-Host "Contenido: $($_.Line)" -ForegroundColor White
    }
} else {
    Write-Host "No se encontró la cadena en ningún archivo." -ForegroundColor Red
}




$results = Get-ChildItem -Path "C:\xampp\htdocs\jcpos" -Recurse -File -Include *.php | 
Select-String -Pattern '\$_SESSION\["categorias"\] = "off";'

if ($results) {
    $results | ForEach-Object {
        Write-Host "========================================" -ForegroundColor Cyan
        Write-Host "Archivo: $($_.Path)" -ForegroundColor Yellow
        Write-Host "Línea: $($_.LineNumber)" -ForegroundColor Green
        Write-Host "Contenido: $($_.Line)" -ForegroundColor White
    }
} else {
    Write-Host "No se encontró la cadena en ningún archivo." -ForegroundColor Red
}












$results = Get-ChildItem -Path "C:\xampp\htdocs\clubpension" -Recurse -File | 
Select-String -Pattern "Clientes por Institución"

if ($results) {
    $results | ForEach-Object {
        Write-Host "========================================" -ForegroundColor Cyan
        Write-Host "Archivo: $($_.Path)" -ForegroundColor Yellow
        Write-Host "Línea: $($_.LineNumber)" -ForegroundColor Green
        Write-Host "Contenido: $($_.Line)" -ForegroundColor White
    }
} else {
    Write-Host "No se encontró la cadena en ningún archivo." -ForegroundColor Red
}
