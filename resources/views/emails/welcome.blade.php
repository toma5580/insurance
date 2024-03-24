<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ config('insura.name') }} | {{ trans('emails.title.welcome') }}</title>
  </head>
  <body style="margin:0px; background: #f8f8f8; ">
    <div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
      <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
          <tbody>
            <tr>
              <td style="vertical-align: top; padding-bottom:30px;" align="center">
                <a href="{{ action('IndexController@get') }}" target="_blank">
                  <img src="{{ asset('uploads/images/'. config('insura.favicon')) }}" alt="{{ config('insura.name') }} Logo" style="border:none;height:50px;"><br/>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
        <div style="padding: 40px; background: #fff;">
          <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
            <tbody>
              <tr>
                <td style="border-bottom:1px solid #f6f6f6;">
                  <h1 style="font-size:14px; font-family:arial; margin:0px; font-weight:bold;">{{ trans('emails.greeting.welcome') }} {{ $recipient->first_name }},</h1>
                  <p style="margin-top:0px; color:#777777;">
                    <br/>
                    {{ trans('emails.instruction.welcome', array(
                      'company_name'  => $recipient->company->name,
                      'insura_name'   => config('insura.name'),
                      'role'          => trans('emails.role.' . $recipient->role)
                    )) }}
                  </p>
                </td>
              </tr>
              <tr>
                <td style="padding:10px 0 30px 0;">
                  <p>{{ trans('emails.message.welcome') }}</p>
                  <center>
                    <a href="{{ action('Auth\PasswordController@getActivate', array($token)) }}" style="display: inline-block; padding: 11px 30px; margin: 20px 0px 30px; font-size: 15px; color: #fff; background: #00c0c8; border-radius: 60px; text-decoration:none;">{{ trans('emails.button.welcome') }}</a>
                  </center>
                  <p style="color:#444444;">{{ $recipient->company->email_signature }}</p>
                </td>
              </tr>
              <tr>
                <td  style="border-top:1px solid #f6f6f6; color:#bbbbbb; font-size:10px; padding-top:20px;">
                  {{ trans('emails.report.welcome') }} <a href="mailto://{{ $recipient->company->admin->email }}">{{ $recipient->company->admin->email }}</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </body>
</html>
