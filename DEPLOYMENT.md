Deployment and S3 storage setup (Railway)

This project is configured to store media using Laravel's filesystem disk specified by FILESYSTEM_DISK. For production (Railway) use S3 or an S3-compatible provider so uploads persist across deploys.

1) Configure S3 on Railway

- Create an AWS S3 bucket (or use another S3-compatible provider).
- In Railway project Settings → Environment Variables add:
  - FILESYSTEM_DISK=s3
  - AWS_ACCESS_KEY_ID=your_key
  - AWS_SECRET_ACCESS_KEY=your_secret
  - AWS_DEFAULT_REGION=your_region (e.g. us-east-1)
  - AWS_BUCKET=your_bucket_name
  - Optional: AWS_URL or AWS_ENDPOINT if using a custom endpoint (e.g., DigitalOcean Spaces or other provider)

2) S3 CORS (important for direct browser video playback)

Set bucket CORS policy similar to:

<?xml version="1.0" encoding="UTF-8"?>
<CORSConfiguration>
  <CORSRule>
    <AllowedOrigin>*</AllowedOrigin>
    <AllowedMethod>GET</AllowedMethod>
    <AllowedMethod>HEAD</AllowedMethod>
    <AllowedHeader>*</AllowedHeader>
    <MaxAgeSeconds>3000</MaxAgeSeconds>
  </CORSRule>
</CORSConfiguration>

Note: Replace AllowedOrigin with your domain for tighter security (e.g., https://aizapcreatives.up.railway.app).

3) Deploying to Railway

Option A — Use the included Dockerfile (recommended)

- The repository now includes a multi-stage Dockerfile that runs composer install in a builder stage and produces a runtime image with PHP and required extensions.
- In Railway, choose Docker deployment or let Railway detect the Dockerfile automatically. This avoids running `php artisan` during the platform build step and ensures `vendor/autoload.php` is present in the image.
- Ensure these environment variables are set in Railway before starting the container (APP_KEY is required if you want to run `php artisan config:cache`):
  - APP_KEY (or run `php artisan key:generate` locally and set it in Railway)
  - DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD (if using a DB)
  - FILESYSTEM_DISK and S3 credentials (if using S3)

Option B — If you prefer Railway's Build Commands (not Docker)

- Make sure the following build commands run in this order and that composer is available in the build environment:
  1. composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
  2. (Optional) php artisan key:generate --force   # or set APP_KEY in Railway env
  3. php artisan migrate --force
  4. php artisan storage:link   # if you rely on public disk
  5. npm ci && npm run build   # if you need to build frontend assets

Important: Do NOT run php artisan config:cache during the Railway build unless APP_KEY and all env variables required by your config are already set in Railway. Running `php artisan config:cache` without vendor present or without APP_KEY will fail the build with errors like "Failed to open stream: No such file or directory in /app/vendor/autoload.php".

If you previously configured Railway Build Commands to run `php artisan config:cache` or other artisan commands during build, remove them and either rely on the Dockerfile or run artisan commands at container start time where vendor is present.

4) Migrating existing local files to S3

After deployment and with env vars in place, run the migration command:

  php artisan storage:migrate-to-s3 --path=projects --visibility=public

This will copy files under storage/app/public/projects to the configured S3 disk. The command is registered as an artisan command and streams files to S3 (uses streams to avoid memory issues).

5) Verify

- Upload a new video via admin UI. Verify Storage::disk('s3')->url('projects/videos/your.mp4') returns an S3 URL and that the video plays from the browser.
- Confirm previously-missing video URLs now resolve and return 200.

6) Notes about repository updates

- With FILESYSTEM_DISK=s3, files will be stored on S3 and persist across code pushes and deploys — preventing 404s caused by ephemeral Railway filesystem.
- Do NOT commit S3 credentials to the repository. Use Railway environment variables.

If you want help executing any of the steps above in your Railway project (setting env vars, running the migration command, or performing the S3 upload), tell me which step to run or provide access details and I can provide the exact commands to execute.