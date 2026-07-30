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

- Ensure Railway has these env vars set (see above).
- Build commands (Railway):
  - Install PHP deps: composer install --no-dev --optimize-autoloader
  - Run migrations: php artisan migrate --force
  - If still using local public disk for assets in any environment, create symlink: php artisan storage:link
  - Build frontend (if needed): npm ci && npm run build

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