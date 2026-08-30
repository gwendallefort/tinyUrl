<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{!! __('emails.verify.html_title', ['app' => config('app.name')]) !!}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin:0;padding:0;background-color:#f4f4f5;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5;padding:40px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;">
                    <tr>
                        <td style="background-color:#ffffff;border-radius:12px;border:1px solid #e4e4e7;padding:40px 40px 32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding-bottom:28px;">
                                        <p style="margin:0;font-size:15px;line-height:1.6;color:#52525b;">
                                            {!! __('emails.common.hello') !!}
                                            <br><br>
                                            {!! __('emails.verify.intro', ['app' => config('app.name')]) !!}
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td align="center" style="padding-bottom:28px;">
                                        <!--[if mso]>
                                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $url }}" style="height:44px;v-text-anchor:middle;width:210px;" arcsize="20%" stroke="f" fillcolor="#18181b">
                                            <w:anchorlock/>
                                            <center style="color:#ffffff;font-family:sans-serif;font-size:14px;font-weight:600;">{!! __('emails.verify.cta') !!}</center>
                                        </v:roundrect>
                                        <![endif]-->
                                        <!--[if !mso]><!-->
                                        <a href="{{ $url }}"
                                           style="display:inline-block;padding:12px 28px;background-color:#18181b;color:#ffffff;text-decoration:none;border-radius:8px;font-size:14px;font-weight:600;letter-spacing:0.1px;line-height:1;">
                                            {!! __('emails.verify.cta') !!}
                                        </a>
                                        <!--<![endif]-->
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding-bottom:28px;">
                                        <p style="margin:0;font-size:15px;line-height:1.6;color:#52525b;">
                                            {!! __('emails.common.expires', ['minutes' => $expiresMinutes]) !!}
                                            <br><br>
                                            {!! __('emails.verify.ignore') !!}
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding-bottom:20px;border-top:1px solid #f4f4f5;"></td>
                                </tr>

                                <tr>
                                    <td>
                                        <p style="margin:0;font-size:12px;line-height:1.6;color:#a1a1aa;">
                                            {!! __('emails.common.fallback') !!}<br>
                                            <a href="{{ $url }}" style="color:#52525b;word-break:break-all;">{{ $url }}</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding-top:24px;">
                            <p style="margin:0;font-size:12px;color:#a1a1aa;">
                                {!! __('emails.verify.footer', ['year' => date('Y'), 'app' => config('app.name')]) !!}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
