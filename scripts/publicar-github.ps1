param(
    [Parameter(Mandatory = $true)]
    [ValidatePattern('^https://github\.com/.+/.+(\.git)?$')]
    [string]$RepositoryUrl
)

$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

if (-not (Get-Command git -ErrorAction SilentlyContinue)) {
    throw 'Git no está instalado o no está disponible en PATH.'
}

if (-not (Test-Path '.git')) {
    throw 'Esta carpeta no contiene el repositorio Git preparado.'
}

$originExists = git remote | Where-Object { $_ -eq 'origin' }
if ($originExists) {
    git remote set-url origin $RepositoryUrl
} else {
    git remote add origin $RepositoryUrl
}

# Descarga las dependencias oficiales antes de probar el proyecto localmente.
git submodule update --init --recursive

git branch -M main
git push -u origin main

Write-Host 'Botica LyP fue publicada en GitHub.' -ForegroundColor Green
