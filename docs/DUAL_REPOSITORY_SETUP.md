# Dual Repository Setup Documentation

This project is configured to push to both GitHub and Bitbucket repositories simultaneously.

## Repository Configuration

### GitHub Repository
- **URL**: `https://github.com/felpabustan/event-management-system.git`
- **Remote Name**: `origin`
- **Default Branch**: `main`

### Bitbucket Repository
- **URL**: `https://t0nchie@bitbucket.org/mapletreemedia/event-management.git`
- **Remote Name**: `bitbucket`
- **Default Branch**: `master`

## Current Git Remote Setup

```bash
git remote -v
```

Output:
```
bitbucket    https://t0nchie@bitbucket.org/mapletreemedia/event-management.git (fetch)
bitbucket    https://t0nchie@bitbucket.org/mapletreemedia/event-management.git (push)
origin       https://github.com/felpabustan/event-management-system.git (fetch)
origin       https://github.com/felpabustan/event-management-system.git (push)
```

## Manual Push Commands

### Push to GitHub
```bash
git push origin main
```

### Push to Bitbucket
```bash
git push bitbucket main:master
```

### Push to Both Repositories
```bash
git push origin main && git push bitbucket main:master
```

## Automated Scripts

Two scripts are provided for automated dual pushing:

### 1. Bash Script (Linux/Mac/WSL)
```bash
./scripts/dual-push.sh
```

### 2. PowerShell Script (Windows)
```powershell
./scripts/dual-push.ps1
```

## Branch Mapping

| Local Branch | GitHub Branch | Bitbucket Branch |
|-------------|---------------|------------------|
| `main`      | `main`        | `master`         |
| `develop`   | `develop`     | `develop`        |
| `feature/*` | `feature/*`   | `feature/*`      |

## Setup Instructions

If you need to recreate this setup on a new machine:

1. **Clone from GitHub** (primary repository):
   ```bash
   git clone https://github.com/felpabustan/event-management-system.git
   cd event-management-system
   ```

2. **Add Bitbucket remote**:
   ```bash
   git remote add bitbucket https://t0nchie@bitbucket.org/mapletreemedia/event-management.git
   ```

3. **Verify remotes**:
   ```bash
   git remote -v
   ```

## Common Workflows

### Making Changes and Pushing
```bash
# Make your changes
git add .
git commit -m "Your commit message"

# Push to both repositories
./scripts/dual-push.ps1  # Windows
# or
./scripts/dual-push.sh   # Linux/Mac
```

### Creating a New Branch
```bash
# Create and switch to new branch
git checkout -b feature/new-feature

# Push to both repositories
git push origin feature/new-feature
git push bitbucket feature/new-feature
```

### Pulling Latest Changes
```bash
# Pull from GitHub (primary)
git pull origin main

# Optional: Verify Bitbucket is in sync
git fetch bitbucket
```

## Troubleshooting

### Authentication Issues
If you encounter authentication issues:

1. **GitHub**: Use Personal Access Token or SSH keys
2. **Bitbucket**: Use App Passwords or SSH keys

### Merge Conflicts
If repositories get out of sync:

1. Always pull from GitHub first (primary repository)
2. Resolve any conflicts
3. Push to both repositories

### Branch Differences
To check if branches are in sync:

```bash
git fetch origin
git fetch bitbucket
git log --oneline --graph --all
```

## Security Notes

- The Bitbucket URL includes the username (`t0nchie@`)
- Consider using SSH keys for both repositories
- Keep both repositories private if the project contains sensitive information

## Repository Links

- **GitHub**: [https://github.com/felpabustan/event-management-system](https://github.com/felpabustan/event-management-system)
- **Bitbucket**: [https://bitbucket.org/mapletreemedia/event-management](https://bitbucket.org/mapletreemedia/event-management)

---

*Last updated: October 6, 2025*