$files = Get-ChildItem -Path resources\views -Recurse -Filter *.blade.php
$patternLink = [regex] 'href="\{\{\s*url\(\'/contact\'\)\s*\}\}"'
$patternClass = [regex] '(<a[^>]*href="#"[^>]*?)class="([^"]*)"'
$patternNoClass = [regex] '(<a[^>]*href="#"(?![^>]*class=)[^>]*?)>'

foreach ($file in $files) {
    $text = Get-Content -Path $file.FullName -Raw
    if (-not $patternLink.IsMatch($text)) { continue }
    $new = $patternLink.Replace($text, 'href="#"')
    $new = $patternClass.Replace($new, { param($m)
        $attrs = $m.Groups[1].Value
        $classes = $m.Groups[2].Value
        if ($classes -match '\bcontact-toggle\b') { return $m.Value }
        return "$attrs`class=\"$classes contact-toggle\""
    })
    $new = $patternNoClass.Replace($new, '$1 class="contact-toggle">')
    if ($new -ne $text) {
        Set-Content -Path $file.FullName -Value $new -Encoding UTF8
        Write-Output "Updated: $($file.FullName)"
    }
}

Remove-Item -Path resources\views\pages\contact.blade.php -Force -ErrorAction SilentlyContinue
Remove-Item -Path public\css\contact.css -Force -ErrorAction SilentlyContinue
Write-Output 'Cleanup complete.'
