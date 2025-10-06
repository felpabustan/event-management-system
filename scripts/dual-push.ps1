# Dual Repository Push Script (PowerShell)
# This script helps you push to both GitHub and Bitbucket repositories

Write-Host "🚀 Starting dual repository push..." -ForegroundColor Green

# Check if we're in a git repository
try {
    $null = git rev-parse --git-dir 2>$null
} catch {
    Write-Host "❌ Error: Not in a git repository" -ForegroundColor Red
    exit 1
}

# Get current branch name
$currentBranch = git branch --show-current
Write-Host "📍 Current branch: $currentBranch" -ForegroundColor Yellow

# Push to GitHub (main branch)
Write-Host "🐙 Pushing to GitHub..." -ForegroundColor Cyan
git push origin $currentBranch
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ GitHub push successful" -ForegroundColor Green
} else {
    Write-Host "❌ GitHub push failed" -ForegroundColor Red
    exit 1
}

# Push to Bitbucket (master branch if current is main, otherwise same branch)
Write-Host "🪣 Pushing to Bitbucket..." -ForegroundColor Cyan
if ($currentBranch -eq "main") {
    git push bitbucket main:master
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Bitbucket push successful (main -> master)" -ForegroundColor Green
    } else {
        Write-Host "❌ Bitbucket push failed" -ForegroundColor Red
        exit 1
    }
} else {
    git push bitbucket $currentBranch
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Bitbucket push successful" -ForegroundColor Green
    } else {
        Write-Host "❌ Bitbucket push failed" -ForegroundColor Red
        exit 1
    }
}

Write-Host "🎉 All repositories updated successfully!" -ForegroundColor Green
Write-Host ""
Write-Host "📍 Repository Links:" -ForegroundColor Yellow
Write-Host "   GitHub: https://github.com/felpabustan/event-management-system" -ForegroundColor Blue
Write-Host "   Bitbucket: https://bitbucket.org/mapletreemedia/event-management" -ForegroundColor Blue