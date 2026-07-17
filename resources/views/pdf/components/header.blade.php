<table width="100%" style="border-bottom:2px solid #000;padding-bottom:10px;margin-bottom:15px;">

    <tr>

        <td width="85" style="text-align:center;vertical-align:top;">

            @if (\App\Support\SettingHelper::schoolOfficialLogo())
                <img src="{{ public_path('storage/' . \App\Support\SettingHelper::schoolOfficialLogo()) }}"
                    style="width:70px;height:auto;">
            @endif

        </td>

        <td style="vertical-align:top;">

            <div style="
                    font-size:18px;
                    font-weight:bold;
                ">

                {{ \App\Support\SettingHelper::schoolName() }}

            </div>

            <div
                style="
                    font-size:13px;
                    font-weight:bold;
                    margin-top:2px;
                ">

                Sistem Informasi Global MTsN 3 Tulungagung (SIGMA 3)

            </div>

            <div style="
                    font-size:11px;
                    margin-top:6px;
                ">

                {{ \App\Support\SettingHelper::schoolAddress() }}

            </div>

            <div style="
                    font-size:11px;
                ">

                Telp.
                {{ \App\Support\SettingHelper::schoolPhone() }}

                |

                Email :
                {{ \App\Support\SettingHelper::schoolEmail() }}

            </div>

        </td>

    </tr>

</table>
