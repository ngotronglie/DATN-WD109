# Tải Mailpit cho Windows (nếu chưa có)
$MailpitUrl = "https://github.com/axllent/mailpit/releases/latest/download/mailpit-windows-amd64.exe"
$MailpitExe = "$PSScriptRoot\mailpit.exe"

if (-Not (Test-Path $MailpitExe)) {
    Write-Host "Đang tải Mailpit..."
    Invoke-WebRequest -Uri $MailpitUrl -OutFile $MailpitExe
    Write-Host "Tải xong mailpit.exe"
} else {
    Write-Host "Đã có mailpit.exe"
}

# Chạy Mailpit trên port 1025 (SMTP) và 8025 (Web UI)
Start-Process -NoNewWindow -FilePath $MailpitExe -ArgumentList "--listen 0.0.0.0:1025 --ui 0.0.0.0:8025"
Write-Host "Mailpit đã chạy! Truy cập http://localhost:8025 để xem email test." 