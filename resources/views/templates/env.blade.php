APP_ENV={{ $env['app_env'] or app()->environment() }}
APP_DEBUG=false
APP_KEY={!! config('app.key') !!}
APP_LOCALE_DEFAULT={{ $env['app_locale_default'] or config('app.locale') }}
APP_LOCALE_FALLBACK=en_US
APP_URL={!! $env['app_url'] or config('app.url') !!}

DB_CONNECTION={{ $env['db_connection'] or config('database.default') }}
DB_HOST={!! $env['db_host'] or config('database.connections.' . config('database.default') . '.host') !!}
DB_DATABASE={!! $env['db_database'] or config('database.connections.' . config('database.default') . '.database') !!}
DB_USERNAME={!! $env['db_username'] or config('database.connections.' . config('database.default') . '.username') !!}
DB_PASSWORD={!! $env['db_password'] or config('database.connections.' . config('database.default') . '.password') !!}

QUEUE_DRIVER={{ $env['queue_driver'] or config('queue.default') }}

MAIL_DRIVER={{ $env['mail_driver'] or config('mail.driver') }}
MAIL_ENCRYPTION={{ $env['mail_encryption'] or config('mail.encryption') }}
MAIL_USERNAME={!! $env['mail_username'] or config('mail.username') !!}

MAILGUN_DOMAIN={!! $env['mailgun_domain'] or config('services.mailgun.domain') !!}
MAILGUN_SECRET={!! $env['mailgun_secret'] or config('services.mailgun.secret') !!}

MANDRILL_SECRET={!! $env['mandrill_secret'] or config('services.mandrill.secret') !!}

SENDMAIL_PATH={!! $env['sendmail_path'] or config('mail.sendmail') !!}

SES_KEY={!! $env['ses_key'] or config('services.ses.key') !!}
SES_SECRET={!! $env['ses_secret'] or config('services.ses.secret') !!}
SES_REGION={!! $env['ses_region'] or config('services.ses.region') !!}

SMTP_HOST={!! $env['smtp_host'] or config('mail.host') !!}
SMTP_PORT={{ $env['smtp_port'] or config('mail.port') }}
SMTP_PASSWORD={!! $env['smtp_password'] or config('mail.password') !!}

BASE_HREF=/

INSURA_CURRENCY_DEFAULT={{ $env['insura_currency_default'] or config('insura.currencies.default') }}
INSURA_FAVICON={{ $env['insura_favicon'] or config('insura.favicon') }}
INSURA_LOGO={{ $env['insura_logo'] or config('insura.logo') }}
INSURA_NAME={{ $env['insura_name'] or config('insura.name') }}
