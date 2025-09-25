$ErrorActionPreference = 'Stop'
$path = 'q:\laragon\www\DATN-WD109\resources\views\layouts\admin\index.blade.php'

# Read file
$content = Get-Content -LiteralPath $path -Raw

# The welcome div we want to insert
$welcomeDiv = @"
<div class="d-flex align-items-center justify-content-center text-center bg-light rounded-4 shadow p-5 mx-auto" style="min-height: 70vh; max-width: 1000px; background-image: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%); font-size: clamp(24px, 4vw, 48px); font-weight: 700; color: #0d6efd;">
    Chào Mừng Bạn Đến Với Shop TechZone
</div>
"@

# Regex to find the content section
$regex = [regex]'(?s)(@section\(''content''\')\s*.*?(@endsection)'

# Replace the content between @section('content') and @endsection with the welcome div
$newContent = $regex.Replace($content, { param($m) $m.Groups[1].Value + "`r`n" + $welcomeDiv + "`r`n" + $m.Groups[2].Value })

# Write back to file
Set-Content -LiteralPath $path -Value $newContent -Encoding UTF8

Write-Output "Updated: $path"
