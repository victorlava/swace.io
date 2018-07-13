@extends('layouts.email')

@section('title')
    Order made
@endsection

@section('header')
    Order Made
@endsection

@section('content')
    Your order is being processed.<br/> <br/>

    If you haven’t completed your purchase and want to continue, you can do that by logging into your dashboard, then clicking ‘View order’ or the link to the order you wish to complete under ‘Your transaction history’.
    
    <br/>
    
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnButtonBlock" style="min-width:100%;">
        <tbody class="mcnButtonBlockOuter">
        <tr>
            <td style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:0" valign="top" align="left" class="mcnButtonBlockInner">
                <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-radius: 4px;background-color: #5B00DF;">
                    <tbody>
                    <tr>
                        <td align="center" valign="middle" class="mcnButtonContent" style="font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 16px; padding: 18px;">
                        <a class="mcnButton " title="Continue order" href="{{ $order_link }}"
                             target="_blank"
                             style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Continue the order you started</a>

                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
    
    If you did not initiate this request, please contact the Swace team immediately at <a href="mailto:{{ env("MAIL_SUPPORT") }}">{{ env("MAIL_SUPPORT") }}</a>.
@endsection