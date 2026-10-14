import os
import zipfile

PROJECT_DIR = '/home/zuse/APPs/coopera/cooperatrading'
ZIP_PATH = os.path.join(PROJECT_DIR, 'coopera-deployment-latest.zip')

# Items to include
INCLUDED_FILES = [
    '.htaccess',
    '.env.production',
    '.env.example',
    '.editorconfig',
    '.gitattributes',
    '.gitignore',
    'artisan',
    'composer.json',
    'composer.lock',
    'package.json',
    'postcss.config.js',
    'tailwind.config.js',
    'vite.config.js',
    'DEPLOYMENT_GUIDE.md',
    'deploy-production.sh',
]

INCLUDED_DIRS = [
    'app',
    'bootstrap',
    'config',
    'database',
    'docker',
    'public',
    'resources',
    'routes',
    'scripts',
    'storage',
    'vendor',
]

EXCLUDE_PATTERNS = [
    '.git',
    'node_modules',
    'storage/logs/',
    'storage/framework/sessions/',
    'storage/framework/cache/data/',
    'storage/framework/views/',
    'bootstrap/cache/config.php',
    'bootstrap/cache/routes-v7.php',
    'bootstrap/cache/routes.php',
    'public/hot',
    'hot',
    '.zip',
]

# Ensure required empty storage directories exist with placeholder files
REQUIRED_EMPTY_DIRS = [
    'storage/framework/views',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/logs',
    'storage/app/public',
    'bootstrap/cache',
]

for rdir in REQUIRED_EMPTY_DIRS:
    full_rdir = os.path.join(PROJECT_DIR, rdir)
    os.makedirs(full_rdir, exist_ok=True)
    gitkeep_file = os.path.join(full_rdir, '.gitkeep')
    if not os.path.exists(gitkeep_file):
        with open(gitkeep_file, 'w') as f:
            f.write('')

def should_exclude(rel_path):
    # Always include .gitignore or .gitkeep to maintain folder structure
    if rel_path.endswith('.gitignore') or rel_path.endswith('.gitkeep'):
        return False
    for pattern in EXCLUDE_PATTERNS:
        if pattern in rel_path:
            return True
    return False

if os.path.exists(ZIP_PATH):
    os.remove(ZIP_PATH)

print(f"Creating {ZIP_PATH}...")

with zipfile.ZipFile(ZIP_PATH, 'w', zipfile.ZIP_DEFLATED) as zipf:
    # 1. Add individual files
    for fname in INCLUDED_FILES:
        fpath = os.path.join(PROJECT_DIR, fname)
        if os.path.isfile(fpath):
            zipf.write(fpath, arcname=fname)
            print(f"  + {fname}")

    # 2. Add ready-to-run .env (copied from .env.production)
    env_prod_path = os.path.join(PROJECT_DIR, '.env.production')
    if os.path.isfile(env_prod_path):
        zipf.write(env_prod_path, arcname='.env')
        print("  + .env (ready-to-run zero-terminal config)")

    # 3. Add directories recursively
    for dname in INCLUDED_DIRS:
        dpath = os.path.join(PROJECT_DIR, dname)
        if os.path.isdir(dpath):
            for root, dirs, files in os.walk(dpath):
                for file in files:
                    full_path = os.path.join(root, file)
                    rel_path = os.path.relpath(full_path, PROJECT_DIR)
                    if not should_exclude(rel_path):
                        zipf.write(full_path, arcname=rel_path)
            print(f"  + {dname}/")

print(f"Deployment archive built successfully at {ZIP_PATH} ({os.path.getsize(ZIP_PATH) / (1024*1024):.2f} MB)")
