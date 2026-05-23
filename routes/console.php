<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:production-check', function () {
    $failed = false;
    $warned = false;

    $pass = fn (string $message) => $this->line("<fg=green>PASS</> {$message}");
    $warn = function (string $message) use (&$warned): void {
        $warned = true;
        $this->line("<fg=yellow>WARN</> {$message}");
    };
    $fail = function (string $message) use (&$failed): void {
        $failed = true;
        $this->line("<fg=red>FAIL</> {$message}");
    };

    $this->info('Production readiness check');

    App::environment('production')
        ? $pass('APP_ENV=production')
        : $warn('APP_ENV bukan production. Gunakan APP_ENV=production saat go-live.');

    config('app.debug')
        ? $fail('APP_DEBUG masih true. Set APP_DEBUG=false di production.')
        : $pass('APP_DEBUG=false');

    filled(config('app.key'))
        ? $pass('APP_KEY tersedia')
        : $fail('APP_KEY kosong. Jalankan php artisan key:generate --force.');

    Str::startsWith((string) config('app.url'), 'https://')
        ? $pass('APP_URL memakai HTTPS')
        : $warn('APP_URL belum HTTPS. Gunakan domain HTTPS saat production.');

    config('app.locale') === 'id'
        ? $pass('Locale aplikasi Bahasa Indonesia')
        : $warn('APP_LOCALE bukan id.');

    try {
        DB::connection()->getPdo();
        $pass('Koneksi database berhasil');
    } catch (Throwable $exception) {
        $fail('Koneksi database gagal: '.$exception->getMessage());
    }

    try {
        $pendingMigrations = Artisan::call('migrate:status', ['--pending' => true]);

        $pendingMigrations === 0
            ? $pass('Tidak ada migration pending')
            : $fail('Masih ada migration pending. Jalankan php artisan migrate --force.');
    } catch (Throwable $exception) {
        $warn('Status migration tidak bisa dicek: '.$exception->getMessage());
    }

    foreach ([storage_path(), base_path('bootstrap/cache')] as $path) {
        is_writable($path)
            ? $pass("Writable: {$path}")
            : $fail("Tidak writable: {$path}");
    }

    try {
        $defaultPasswordUsers = User::query()
            ->get()
            ->filter(fn (User $user): bool => Hash::check('password', $user->password))
            ->pluck('email')
            ->all();

        empty($defaultPasswordUsers)
            ? $pass('Tidak ada user dengan password default')
            : $fail('Masih ada user dengan password default: '.implode(', ', $defaultPasswordUsers));
    } catch (Throwable $exception) {
        $warn('Password user tidak bisa dicek: '.$exception->getMessage());
    }

    File::exists(public_path('build/manifest.json'))
        ? $pass('Asset production sudah dibuild')
        : $warn('Asset build belum ditemukan. Jalankan npm ci && npm run build.');

    config('session.encrypt')
        ? $pass('Session encryption aktif')
        : $warn('SESSION_ENCRYPT belum true.');

    if ($failed) {
        $this->newLine();
        $this->error('Production check selesai dengan FAIL. Perbaiki item FAIL sebelum go-live.');

        return 1;
    }

    if ($warned) {
        $this->newLine();
        $this->warn('Production check selesai dengan WARN. Aplikasi bisa dites, tapi cek catatan sebelum go-live.');

        return 0;
    }

    $this->newLine();
    $this->info('Production check bersih. Aplikasi siap masuk tahap deploy server.');

    return 0;
})->purpose('Check whether the app is ready for production deployment');

Artisan::command('app:set-user-password {email} {--password=} {--generate}', function (string $email) {
    $user = User::query()->where('email', $email)->first();

    if (! $user) {
        $this->error("User tidak ditemukan: {$email}");

        return 1;
    }

    if ($this->option('generate')) {
        $password = Str::password(18, letters: true, numbers: true, symbols: true, spaces: false);
    } else {
        $password = $this->option('password') ?: $this->secret('Password baru');
    }

    if (! is_string($password) || strlen($password) < 12) {
        $this->error('Password minimal 12 karakter.');

        return 1;
    }

    $user->forceFill([
        'password' => $password,
    ])->save();

    $this->info("Password berhasil diperbarui untuk {$user->email}.");

    if ($this->option('generate')) {
        $this->warn('Simpan password ini sekarang. Password tidak bisa dilihat lagi setelah command selesai.');
        $this->line($password);
    }

    return 0;
})->purpose('Set or generate a password for an application user');
