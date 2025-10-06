#!/bin/bash

# Dual Repository Push Script
# This script helps you push to both GitHub and Bitbucket repositories

echo "🚀 Starting dual repository push..."

# Check if we're in a git repository
if ! git rev-parse --git-dir > /dev/null 2>&1; then
    echo "❌ Error: Not in a git repository"
    exit 1
fi

# Get current branch name
CURRENT_BRANCH=$(git branch --show-current)
echo "📍 Current branch: $CURRENT_BRANCH"

# Push to GitHub (main branch)
echo "🐙 Pushing to GitHub..."
git push origin "$CURRENT_BRANCH"
if [ $? -eq 0 ]; then
    echo "✅ GitHub push successful"
else
    echo "❌ GitHub push failed"
    exit 1
fi

# Push to Bitbucket (master branch if current is main, otherwise same branch)
echo "🪣 Pushing to Bitbucket..."
if [ "$CURRENT_BRANCH" = "main" ]; then
    git push bitbucket main:master
    if [ $? -eq 0 ]; then
        echo "✅ Bitbucket push successful (main -> master)"
    else
        echo "❌ Bitbucket push failed"
        exit 1
    fi
else
    git push bitbucket "$CURRENT_BRANCH"
    if [ $? -eq 0 ]; then
        echo "✅ Bitbucket push successful"
    else
        echo "❌ Bitbucket push failed"
        exit 1
    fi
fi

echo "🎉 All repositories updated successfully!"
echo ""
echo "📍 Repository Links:"
echo "   GitHub: https://github.com/felpabustan/event-management-system"
echo "   Bitbucket: https://bitbucket.org/mapletreemedia/event-management"